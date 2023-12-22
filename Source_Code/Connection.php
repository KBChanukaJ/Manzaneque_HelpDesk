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

// Uncomment the line below if you want to verify the connection message
// echo "Connected successfully";

// Do not close the connection here; close it in the files where it's no longer needed
// $conn->close();
?>
