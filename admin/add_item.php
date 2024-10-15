<?php
session_start();
include('../cagro/config.php');

// Check database connection
if (!$con) {
    die('Database connection failed: ' . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $group = $_POST['groups'];
    $category = $_POST['category'] ?: $_POST['other-category']; // Use other-category if selected
    $description = $_POST['description'];
    $seeds = $_POST['seeds'];
    $quantity = $_POST['qty'];

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageSize = $_FILES['image']['size'];
        $imageType = $_FILES['image']['type'];

        // Define allowed file types and max file size (e.g., 2MB)
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxFileSize = 2 * 1024 * 1024; // 2MB

        // Check file type and size
        if (in_array($imageType, $allowedFileTypes) && $imageSize <= $maxFileSize) {
            // Read the image file as binary data
            $imageData = file_get_contents($imageTmpPath);

            // Prepare the SQL statement
            $query = "INSERT INTO items (groups, category, Seeds, description, qty, image_data) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            // Note: Use 'sssssb' for the last parameter as it is binary data
            $stmt->bind_param('ssssis', $group, $category, $seeds, $description, $quantity, $imageData);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Item added successfully!';
            } else {
                $_SESSION['error_message'] = 'Database error: ' . $stmt->error;
            }
        } else {
            $_SESSION['error_message'] = 'Invalid file type or size exceeded.';
        }
    } else {
        // Handle file upload errors
        switch ($_FILES['image']['error']) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $_SESSION['error_message'] = 'File size exceeds the limit.';
                break;
            case UPLOAD_ERR_NO_FILE:
                $_SESSION['error_message'] = 'No file was uploaded.';
                break;
            default:
                $_SESSION['error_message'] = 'An unknown error occurred during file upload.';
                break;
        }
    }

    header('Location: cat_mgmt.php'); // Redirect back to the catalog management page
    exit();
}
?>
