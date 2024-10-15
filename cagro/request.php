<?php 
session_start(); // Start session
include "config.php"; // Database configuration

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Initialize the basket items
$basketItems = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];

// Handle post request for adding/updating items...
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['userName']) && isset($_POST['userNumber']) && isset($_POST['selectedItems'])) {
        $userName = $_POST['userName'];
        $userNumber = $_POST['userNumber'];
        $selectedItems = json_decode($_POST['selectedItems'], true); // Decode the JSON array

        // Insert data into the `requests` table
        $itemsRequested = implode(', ', $selectedItems); // Convert array to a string
        
        $stmt = $pdo->prepare("INSERT INTO requests (name, contact_number, items_requested, status) 
                               VALUES (?, ?, ?, 'Pending')");
        $stmt->execute([$userName, $userNumber, $itemsRequested]);

        echo json_encode(['success' => true]);
        exit;
    }
}
?>
