require('dotenv').config();

var db = require('mysql2')

const pool = db.createPool({
  host: process.env.DB_HOST,
  port: process.env.PORT,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD,
  database: process.env.DB_NAME
});

module.exports = pool.promise();