// get the client
const mysql = require('mysql2');

// create the connection to database
const mysqlConnection = mysql.createConnection({
    host: 'localhost',
    user: 'root',
    database: 'reseau_social'
});

module.exports = mysqlConnection