<?php
include "config.php";

// Fetch items based on the category and type (vegetables/fruits)
$type = $_GET['type']; // 'Vegetables' or 'Fruits'
$category = $_GET['category']; // Category passed in the request

// Update SQL query to select fields: seeds, qty, description, image_data
$sql = "SELECT seeds, qty, description, image_data FROM items WHERE groups = ? AND category = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("ss", $type, $category);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    // If the image_data is not empty, convert it to Base64
    if (!empty($row['image_data'])) {
        // Assuming the images are stored as binary data, convert to Base64
        $row['image_data'] = 'data:image/jpeg;base64,' . base64_encode($row['image_data']);
    }
    $items[] = $row; // Collect all rows into the $items array
}

$stmt->close();
$con->close();

// Return the items as JSON
header('Content-Type: application/json');
echo json_encode($items);
?>
