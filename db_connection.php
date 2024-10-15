<?php
$servername = "localhost";
$username = "root";  // Default XAMPP MySQL username is 'root'
$password = "";      // Default password is empty
$dbname = "seedling_portal";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>