<?php
session_start();
require 'db_connection.php'; // MySQL connection file

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect form data and sanitize
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $contactNumber = htmlspecialchars(trim($_POST['contactNumber']));
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

// Check if passwords match
if ($password !== $cpassword) {
    // Separate error message for admin or farmer
    if (isset($_POST['idNumber'])) {
        $_SESSION['admin_error'] = "Passwords do not match."; // Admin error
        header("Location: index.php?signup=admin");
    } else {
        $_SESSION['farmer_error'] = "Passwords do not match."; // Farmer error
        header("Location: index.php?signup=farmer");
    }
    exit;
}





    // Encrypt password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if it's admin or farmer registration
    if (isset($_POST['idNumber'])) {
        // Admin Registration
        $idNumber = htmlspecialchars(trim($_POST['idNumber']));
        $query = "INSERT INTO admins (first_name, last_name, id_number, contact_number, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssss", $firstName, $lastName, $idNumber, $contactNumber, $hashedPassword);
    } else {
        // Farmer Registration
        $query = "INSERT INTO farmers (first_name, last_name, contact_number, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $firstName, $lastName, $contactNumber, $hashedPassword);
    }


// Other error handling with the same logic
if ($stmt->execute()) {
    $_SESSION['success'] = "Registration successful!";
    header("Location: index.php"); 
    exit;
} else {
    if (isset($_POST['idNumber'])) {
        $_SESSION['admin_error'] = "Error: " . $stmt->error; // Admin-specific error
        header("Location: index.php?signup=admin");
    } else {
        $_SESSION['farmer_error'] = "Error: " . $stmt->error; // Farmer-specific error
        header("Location: index.php?signup=farmer");
    }
    exit;
}
}
?>
