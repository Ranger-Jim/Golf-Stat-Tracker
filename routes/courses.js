const db = require('../db');
const express = require('express');
const router = express.Router();

router.get('/:id', async (req, res) => {
  const courseId = req.params.id;

  try {
    const [rows] = await db.query(
      'SELECT * FROM courses WHERE course_id = ?',
      [courseId]
    );

    if (rows.length === 0) {
      return res.status(404).json({ error: 'Course not found.' });
    }

    res.json(rows[0]);
  } catch (error) {
    console.error(500).json({ error: error.message });
  }
});

module.exports = router;