<?php
$servername = "localhost";
$username = "root";  // Adjust to your database credentials
$password = "";
$dbname = "seedling_portal";  // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch data with join
$sql = "
    SELECT r.*, CONCAT(f.first_name, ' ', f.last_name) AS full_name, f.contact_number
    FROM requests r
    JOIN farmers f ON r.user_id = f.id
";

$result = $conn->query($sql);
$requests = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $requests[] = $row;
    }
}

$conn->close();
echo json_encode($requests);
?>
