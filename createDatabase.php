<?php
include 'config.php';
// Create database
$sql = "CREATE DATABASE gsm";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

$sql = "CREATE DATABASE gsm";

mysqli_select_db($conn, 'gsm');

// sql to create table
$sql = "CREATE TABLE cell_tower (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
mnc INT(3),
mcc INT(3),
lac INT(3),
ci INT(5)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cell_tower created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE cell_phone (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
tmsi INT(3),
last_seen TIMESTAMP,
signal_strength INT(3)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cell_tower created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}


$conn->close();

?>
