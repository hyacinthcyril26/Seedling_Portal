<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['basket'])) {
    $_SESSION['basket'] = [];
}

// Return the current basket
echo json_encode($_SESSION['basket']);
exit;
?>