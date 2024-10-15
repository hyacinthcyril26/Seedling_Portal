<?php
session_start();
include "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Fetch user information once at the start
$resultf = mysqli_query($con, "SELECT * FROM farmers WHERE id='" . $_SESSION['user_id'] . "'");
$rowf = mysqli_fetch_array($resultf);

// Fetch vegetable categories
$vegetables_categories = [];
$vegetables_result = $con->query("SELECT DISTINCT category FROM items WHERE groups = 'Vegetables'");
if ($vegetables_result) {
    while ($row = $vegetables_result->fetch_assoc()) {
        $vegetables_categories[] = $row['category'];
    }
} else {
    echo "ERROR: Could not execute query: $con->error";
}

// Fetch fruit categories
$fruits_categories = [];
$fruits_result = $con->query("SELECT DISTINCT category FROM items WHERE groups = 'Fruits'");
if ($fruits_result) {
    while ($row = $fruits_result->fetch_assoc()) {
        $fruits_categories[] = $row['category'];
    }
} else {
    echo "ERROR: Could not execute query: $con->error";
}

// Close the database connection
$con->close();

// Function to get image path
function getImagePath($group) {
    $imageName = str_replace(' ', '_', $group) . '.png';
    $imagePath = "images/{$imageName}";
    return file_exists($imagePath) ? $imagePath : "images/default.jpg";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seedling Portal</title>
    <link rel="stylesheet" href="styles/cagro_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="navbar">
    <div class="logo">Seedling Portal</div>
    <ul class="nav-links">
        <li><a href="#" class="active">Home</a></li>
        <li><a href="basket.php">Basket</a></li>
        <li><a href="history.php">History</a></li>
        <li><a href="about_us.php">About Us</a></li>
        <!-- Profile Icon -->
        <li class="profile-icon">
            <i class="fas fa-circle-user top-right-icon" style="font-size: 32px; cursor: pointer;"></i>
        </li>
    </ul>
    
    <!-- Profile Modal -->
    <div class="profile-modal" id="profile-modal">
        <div class="profile-modal-content">
            <div class="profile-picture">
                <i class="fas fa-user"></i>
                <span>Hi <?php echo htmlspecialchars($rowf["first_name"] ?? 'User'); ?></span>
            </div>
            <div class="profile-options">
                <div class="option">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Edit Profile</span>
                </div>
                <div class="option logout" id="logout-option">
                    <i class="fas fa-sign-out-alt"></i>
                    <a href="../logout.php" style="text-decoration: none; color: inherit;">
                    <span>Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="search-container">
    <form id="search-form">
        <input type="text" id="search-input" placeholder="Search...">
    </form>
</div>

<div class="categories">
    <h2>Vegetables</h2>
    <div class="grid-container" id="vegetables-container">
        <?php foreach ($vegetables_categories as $category) : ?>
            <div class="grid-item" data-category="<?php echo htmlspecialchars($category); ?>" data-group="Vegetables" onclick="openModal('Vegetables', '<?php echo $category; ?>')">
                <img src="<?php echo getImagePath($category, 'Vegetables'); ?>" alt="<?php echo htmlspecialchars($category); ?>">
                <span><?php echo htmlspecialchars($category); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Fruits</h2>
    <div class="grid-container" id="fruits-container">
        <?php foreach ($fruits_categories as $category) : ?>
            <div class="grid-item" data-category="<?php echo htmlspecialchars($category); ?>" data-group="Fruits" onclick="openModal('Fruits', '<?php echo $category; ?>')">
                <img src="<?php echo getImagePath($category, 'Fruits'); ?>" alt="<?php echo htmlspecialchars($category); ?>">
                <span><?php echo htmlspecialchars($category); ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2 id="modal-title"></h2>
        <div id="items-container"></div>
    </div>
</div>

<script src="scripts/cagro.js"></script>
</body>
</html>
