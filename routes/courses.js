const db = require('../db');
const express = require('express');
const router = express.Router();
const { findOrCreateCourse } = require('../services/courseService')

// GET /api/courses/search?q=...
// Checks local db first, falls back to external API and caches results
// This is done to limit api calls
router.get('/search', async (req, res) => {
  const query = req.query.q;

  if (!query) {
    return res.status(400).json({ error: 'Search query is required.' });
  }

  try {
    // 1. Check local db firstt
    const [localResults] = await db.query(
      `SELECT
      c.course_id, c.course_name, c.club_name, c.city, c.state, c.external_id,
      ts.tee_set_id, ts.tee_name, ts.gender, ts.total_yardage, ts.total_par
      FROM Courses c
      JOIN TeeSets ts ON c.course_id = ts.course_id
      WHERE c.course_name LIKE ? OR c.club_name LIKE ?`,
      [`%${query}%`, `%${query}%`]
    );

    if (localResults.length > 0) {
      return res.json({ source: 'local', results: localResults });
    }

    // 2. Fall back to external API
    const response = await fetch(
      `https://api.golfcourseapi.com/v1/search?search_query=${encodeURIComponent(query)}`,
      {
        headers: { 'Authorization': `Key ${process.env.GOLF_API_KEY}`}
      }
    );

    if (!response.ok) {
      return res.status(502).json({ error: 'External API request failes.' });
    }

    const data = await response.json();

    // For debug purposes
    console.log(JSON.stringify(data.courses?.[0], null, 2));

    const courses = data.courses || [];

    if (courses.length === 0) {
      return res.json({ source: 'api', results: [] });
    }

    // 3. Cache all results in local DB
    for (const course of courses) {
      await findOrCreateCourse({
        external_id: course.id,
        club_name: course.club_name,
        course_name: course.course_name,
        city: course.location?.city,
        state: course.location?.state,
        tees: course.tees
      });
    }

    // 4. Re-query local DB so response shape is always consistent
    const [cachedResults] = await db.query(
      `SELECT
      c.course_id, c.course_name, c.club_name, c.city, c.state, c.external_id,
      ts.tee_set_id, ts.tee_name, ts.gender, ts.total_yardage, ts.total_par
      FROM Courses c
      JOIN TeeSets ts ON c.course_id = ts.course_id
      WHERE c.course_name LIKE ? OR c.club_name LIKE ?`,
      [`%${query}%`, `%${query}%`]
    );

    res.json({ source: 'api', results: cachedResults });
  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Search failed.' });
  }
});

// GET /api/courses/:id - get a single course with all tee sets and holes
router.get('/:id', async (req, res) => {
  const courseId = req.params.id;

  try {
    const [courses] = await db.query(
      'SELECT * FROM courses WHERE course_id = ?',
      [courseId]
    );

    if (rows.length === 0) {
      return res.status(404).json({ error: 'Course not found.' });
    }

    const [teeSets] = await db.query(
      `SELECT * FROM TeeSets WHERE course_id = ?`,
      [courseId]
    );

    for (const teeSet of teeSets) {
      const [holes] = await db.query(
        `SELECT hole_number, par, yardage FROM Holes WHERE tee_set_id = ?
        ORDER BY hole_number`, 
        [teeSet.tee_set_id]
      );
    }

    res.json({ ...courses[0], tee_sets: teeSets });

  } catch (error) {
    console.error(error);
    console.error(500).json({ error: 'Failed to fetch course.' });
  }
});

module.exports = router;