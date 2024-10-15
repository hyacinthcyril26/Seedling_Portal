<?php
session_start();
require 'db_connection.php'; // Ensure this file establishes the connection properly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $contactNumber = $_POST['contactNumber'];
    $password = $_POST['password'];

    // Admin login check
    $admin_query = "SELECT * FROM admins WHERE contact_number = ?";
    $stmt = $conn->prepare($admin_query);
    $stmt->bind_param("s", $contactNumber);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    if ($admin_result->num_rows > 0) {
        $admin = $admin_result->fetch_assoc();
        // Verify admin password
        if (password_verify($password, $admin['password'])) {
            // Set session variables
            $_SESSION['user_type'] = 'admin';
            $_SESSION['user_id'] = $admin['id'];
            $_SESSION['user_name'] = $admin['first_name']; // Store the first name
            header("Location: admin/dashboard.php");
            exit();
        } else {
            $_SESSION['error1'] = "Invalid contact number or password!";
            header("Location: index.php"); 
            exit();
        }
    } else {
        // Farmer login check
        $farmer_query = "SELECT * FROM farmers WHERE contact_number = ?";
        $stmt->prepare($farmer_query);
        $stmt->bind_param("s", $contactNumber);
        $stmt->execute();
        $farmer_result = $stmt->get_result();

        if ($farmer_result->num_rows > 0) {
            $farmer = $farmer_result->fetch_assoc();
            // Verify farmer password
            if (password_verify($password, $farmer['password'])) {
                // Set session variables
                $_SESSION['user_type'] = 'farmer';
                $_SESSION['user_id'] = $farmer['id'];
                $_SESSION['user_name'] = $farmer['first_name']; 
                header("Location: cagro/cagro.php");
                exit();
            } else {
                $_SESSION['error1'] = "Invalid contact number or password!";
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['error1'] = "Invalid contact number or password!";
            header("Location: index.php");
            exit();
        }
    }
}


?>
