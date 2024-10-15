<?php
session_start();
include('../cagro/config.php');

if (isset($_GET['id'])) {
    $id = (int)$_GET['id']; // Cast to integer for safety

    // Prepare the delete statement
    $sql = "DELETE FROM items WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success_message'] = "Item deleted successfully!";
        } else {
            $_SESSION['error_message'] = "Error deleting item: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error_message'] = "Statement preparation failed: " . mysqli_error($con);
    }

    mysqli_close($con);
}

// Redirect back to the catalog management page
header("Location: cat_mgmt.php");
exit;
?>
