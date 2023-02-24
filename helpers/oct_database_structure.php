<?php
/*
 * Copyright [2022] [Jason Alexander Cleeland, Melbourne Australia]
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */
// Replace these values with your own database credentials
$servername = "localhost";
$username = "yourusername";
$password = "yourpassword";
$dbname = "yourdatabase";

// Set the path to your SQL file
$sql_file = "/path/to/your/exported/sql/file.sql";

// Connect to the MySQL server
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create the database if it does not already exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

// Select the newly created database
$conn->select_db($dbname);

// Read the SQL file and execute each statement
$sql_contents = file_get_contents($sql_file);
$sql_statements = explode(";", $sql_contents);

foreach ($sql_statements as $sql_statement) {
    if (trim($sql_statement) !== "") {
        if ($conn->query($sql_statement) !== TRUE) {
            echo "Error executing statement: " . $conn->error;
        }
    }
}

echo "Database created successfully";

// Close the database connection
$conn->close();
?>
