<?php
require ("config.inc.php");

//*************************  DROP DATABASE
// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "DROP DATABASE ". $dbname ;
if ($conn->query($sql) === TRUE) {
    echo "Existing Database ". $dbname . " dropped successfully.<br><br>";
} 

$conn->close();



//*************************  CREATE DATABASE

// Create connection
$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE ". $dbname;
if ($conn->query($sql) === TRUE) {
    echo "Database ". $dbname . " created successfully.<br><br>";
} else {
    die("Error creating database: " . $conn->error);
}

$conn->close();

//********************** CREATE TABLE

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE Activity (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
Activity VARCHAR(200) NOT NULL,
Region VARCHAR(50) NOT NULL,
District VARCHAR(50) NOT NULL,
Ward VARCHAR(50) NOT NULL,
Organisation VARCHAR(50) NOT NULL,
Pcode VARCHAR(50),
Org_type VARCHAR(100),
reg_date TIMESTAMP
)";


if ($conn->query($sql) === TRUE) {
    echo "Table ". $tableName . " created successfully.<br><br>";
} else {
    die("Error creating table: " . $conn->error);
}

$conn->close();
?> 