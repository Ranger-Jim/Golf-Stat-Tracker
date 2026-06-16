require('dotenv').config();

const express = require('express');

const app = express();
const port = 3000;
app.use(express.json());

const db = require('./db');

//routing
app.use('/api/courses', require('./routes/courses'));
app.use('/api/auth', require('./routes/auth'));
//app.use('/api/rounds', require('./routes/rounds'));

app.get('/test-db', async (req, res) => {
  try {
    const [rows] = await db.query('SELECT 1 + 1 AS result');
    res.json({ success: true, result: rows[0].result});
  } catch (error) {
    res.status(500).json({ success: false, error: error.message });
  }
});

app.listen(port, () => {
  console.log(`Example app listening on port http://localhost:${port}`);
});