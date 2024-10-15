<?php
include "config.php";

header('Content-Type: application/json');

if(isset($_GET['query'])) {
    $search = mysqli_real_escape_string($con, $_GET['query']);
    $sql = "SELECT DISTINCT category, groups FROM items WHERE category LIKE '%$search%' OR groups LIKE '%$search%'";
    $result = mysqli_query($con, $sql);

    $categories = array();
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $categories[] = array(
                'category' => $row['category'],
                'group' => $row['groups']
            );
        }
    }

    echo json_encode($categories);
}

mysqli_close($con);
?>
