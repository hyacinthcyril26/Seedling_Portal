<?php
$servername = "localhost";
$username = "root";  // Adjust to your database credentials
$password = "";
$dbname = "seedling_portal";  // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests
$sql = "SELECT status FROM requests";  // Assuming `status` field exists in your table
$result = $conn->query($sql);
$requests = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

$conn->close();

// Return JSON data
echo json_encode($requests);
?>
