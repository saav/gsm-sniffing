<?php
include 'config.php';
// Create database
$sql = "CREATE DATABASE gsm";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully";
} else {
    echo "Error creating database: " . $conn->error;
}

mysqli_select_db($conn, 'gsm');

// sql to create table
$sql = "CREATE TABLE cell_tower (
mnc INT(6) NOT NULL,
mcc INT(6) NOT NULL,
lac INT(6) NOT NULL,
ci INT(6) NOT NULL,
PRIMARY KEY(lac, ci)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cell_tower created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE cell_phone (
tmsi VARCHAR(10),
last_seen DATETIME NOT NULL,
signal_strength INT(3) NOT NULL,
lac INT(6) NOT NULL,
ci INT(6) NOT NULL,
FOREIGN KEY(lac, ci) REFERENCES cell_tower(lac, ci),
PRIMARY KEY(tmsi, lac, ci)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cell_tower created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}

$sql = "CREATE TABLE cell_connection (
lac INT(6),
ci INT(6),
stamp DATETIME,
new INT(4) NOT NULL,
repeated INT(4) NOT NULL,
FOREIGN KEY(lac, ci) REFERENCES cell_tower(lac, ci),
PRIMARY KEY(lac, ci, stamp)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table cell_connection created successfully";
} else {
    echo "Error creating table: " . $conn->error;
}
$conn->close();

?>
