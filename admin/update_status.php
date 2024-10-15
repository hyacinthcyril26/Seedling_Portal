<?php
$servername = "localhost";
$username = "root";  // Adjust to your database credentials
$password = "";
$dbname = "seedling_portal";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_POST['id'];
$status = $_POST['status'];
$claimedDate = isset($_POST['claimedDate']) ? $_POST['claimedDate'] : null;

// Prepare SQL to update status and handle date_received based on status
if ($status === 'Claimed') {
    // If the status is 'Claimed', set the date_received to the claimed date
    $sql = "UPDATE requests SET status='$status', date_received='$claimedDate' WHERE id=$id";
} else {
    // If the status is 'Approved' or 'Rejected', clear the date_received
    $sql = "UPDATE requests SET status='$status', date_received=NULL WHERE id=$id";
}

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
