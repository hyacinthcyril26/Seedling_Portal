<?php 
session_start();
include "config.php";

// Fetch user information once at the start
$resultf = mysqli_query($con, "SELECT * FROM farmers WHERE id='" . $_SESSION['user_id'] . "'");
$rowf = mysqli_fetch_array($resultf);

// Initialize the basket items
$basketItems = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];

// Handle post request for seed request submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['send_request'])) {
    // Get farmer details
    $name = $rowf['first_name'] . ' ' . $rowf['last_name'];
    $contact_num = $rowf['contact_number'];

    // Convert basket items into a JSON string for easy storage
    $items_requested = json_encode($basketItems);

    // Insert the request into the request_form table
    $query = "INSERT INTO request_form (Name, Contact_Num, items_requested, status) 
              VALUES ('$name', '$contact_num', '$items_requested', 'pending')";
    if (mysqli_query($con, $query)) {
        // Clear the basket after successful submission
        unset($_SESSION['basket']);
        echo "Seed request submitted successfully!";
    } else {
        echo "Error submitting request. Please try again.";
    }
}
?>