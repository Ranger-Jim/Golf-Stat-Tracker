require('dotenv').config();

const express = require('express');

const app = express();
const port = 3000;

const db = require('./db');

//routing
const coursesRouter = require('./routes/courses');
app.use('/api/courses', coursesRouter);

app.get('/search', async (req, res) => {
  const query = req.query.q; // ex. /search?q=pb+dye

  try {
    const response = await fetch(
      `https://api.golfcourseapi.com/v1/search?search_query=${query}`,
      {
        headers: {
          'Authorization': `Key ${process.env.GOLF_API_KEY}`
        }
      }
    );

    const data = await response.json();
    res.json(data);
  } catch (error) {
    console.error('Error:', error);
    res.status(500).json({ error: 'Failed to fetch courses' });
  }
});

app.listen(port, () => {
  console.log(`Example app listening on port http://localhost:${port}`);
});



app.get('/test-db', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT 1 + 1 AS result');
    res.json({ success: true, result: rows[0].result});
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});