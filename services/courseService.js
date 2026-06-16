const pool = require('../db');

async function findOrCreateCourse(courseData) {
  const { external_id, club_name, course_name, city, state, tees } = courseData;

  // Check if course already exists in db
  if (external_id) {
    const [existing] = await pool.query(
      'SELECT course_id FROM Courses WHERE external_id = ?',
      [external_id]
    );
    if (existing.length > 0) {
      return existing[0].course_id;
    }
  }

  // Insert the course
  const [courseResult] = await pool.query(
    'INSERT INTO Courses (external_id, course_name, club_name, city, state) VALUES (?, ?, ?, ?, ?)',
    [external_id || null, course_name || null, club_name || null, city || null, state || null]
  );

  const courseId = courseResult.insertId;

  // Insert all tee setts and their holes
  const genders = ['male', 'female'];

  for (const gender of genders) {
    const teeSets = tees?.[gender] || [];

    for (const teeSet of teeSets) {
      // Here I calculate the amount of par 3's (for stats)
      const par3Count = teeSet.holes.filter(h => h.par === 3).length;

      const [teeResult] = await pool.query(
        'INSERT INTO TeeSets (course_id, tee_name, gender, total_yardage, total_par, par3_count) VALUES (?, ?, ?, ?, ?, ?)',
        [courseId, teeSet.tee_name, gender, teeSet.total_yards, teeSet.par_total, par3Count]
      );

      const teeSetId = teeResult.insertId;

      // Insert all 18 holes for this tee set
      for (let i = 0; i < teeSet.holes.length; i++) {
        const hole = teeSet.holes[i];
        await pool.query(
          'INSERT INTO Holes (tee_set_id, hole_number, par, yardage) VALUES (?, ?, ?, ?)',
          [teeSetId, i + 1, hole.par, hole.yardage]
        );
      }
    }
  }

  return courseId;
}

module.exports = { findOrCreateCourse }