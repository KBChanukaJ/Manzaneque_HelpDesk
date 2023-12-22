<?php
// Database connection parameters
$servername = "localhost";  // Assuming the database is on the same server
$username = "root";
$password = "";  // Enter your database password here
$database = "ManzanequeHelpdesk";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully";

// Close connection when done
$conn->close();
?>
