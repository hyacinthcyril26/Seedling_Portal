<?php
session_start();
include('../cagro/config.php');
// Adjust path if needed

// Test database connection
if ($con) {
    // Connection is successful, proceed to fetch data
    $query = "SELECT id AS `id`, groups AS `Groups`, category AS `Categories`, seeds AS `Seeds`, description AS `Description`, qty AS `Quantity` FROM items"; 
    $result = mysqli_query($con, $query);
} else {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Requests</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container">
    <div class="container">
        <!-- Hamburger Menu Icon -->
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="cagro_logo.png" alt="Company Logo">
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="dashboard.php">
                            <i class="fa-solid fa-house"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="request.php">
                            <i class="fas fa-file-alt"></i>Request
                        </a>
                    </li>
                    <li>
                        <a href="cat_mgmt.php" class="active"><i class="fas fa-cogs"></i>Catalog Management</a>
                    </li>
                    <li>
                        <a href="reports.html">
                            <i class="fas fa-chart-bar"></i>Reports
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Success/Error Modal -->
    <div class="modal" id="message-modal" style="display: none;">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p id="modal-message"></p>
        </div>
    </div>

        <main class="main-content">
            <i class="fas fa-circle-user top-right-icon"></i>  
            <!-- Profile Modal -->
            <div class="profile-modal" id="profile-modal">
                <div class="profile-modal-content">
                    <div class="profile-picture">
                        <i class="fas fa-user"></i>
                        <span>Hi Admin</span>
                    </div>
                    <div class="profile-options">
                        <div class="option">
                            <i class="fas fa-pencil-alt"></i>
                            <span>Edit Profile</span>
                        </div>
                        <div class="option logout" id="logout-option">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Log Out</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <header>
                <h1></h1>
                <!-- Search Bar -->
                <div class="search-bar">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search">
                </div>
            </header>
            <!-- Add Button -->
            <button class="filter-btn">
                <i class="fas fa-plus"></i> Add
            </button>

            <div class="modal" id="add-modal">
                <div class="modal-content">         
                    <span class="close">&times;</span>
                    <h2>Add Form</h2><br>

                    <form action="add_item.php" enctype="multipart/form-data" method="POST">
                        <div class="form-group">
                            <label for="group">Group</label>
                            <select id="group" name="groups" required>
                                <option value="">Select Group</option>
                                <option value="Fruits">Fruits</option>
                                <option value="Vegetables">Vegetables</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select id="category" name="category" onchange="showOtherCategoryInput()">
                                <option value="">Select Category</option>
                                <option value="Leafy Vegetables">Leafy Vegetables</option>
                                <option value="Fruit Vegetables">Fruit Vegetables</option>
                                <option value="Root Vegetables">Root Vegetables</option>
                                <option value="Others">Others</option>
                            </select>
                            <input type="text" id="other-category" name="other-category" placeholder="Enter Category" style="display: none;">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4" placeholder="Enter Description" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="seeds">Seeds</label>
                            <input type="text" id="seeds" name="seeds" placeholder="Enter Seeds">
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" name="qty" placeholder="Enter Quantity" required>
                        </div>
                        <div class="form-group">
        <label for="image">Add Image</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>
                        
                        <button type="submit" class="add-button">Add</button>
                    </form>
                </div>
            </div>

            <div class="table-wrapper">    
                <table>
                    <thead>
                        <tr>
                            <th>Group</th>
                            <th>Categories</th>
                            <th>Seeds</th>
                            <th>Description</th>
                            <th>Quantity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Check if any rows were returned and output them
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['Groups']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Categories']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Seeds']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['Quantity']) . "</td>";
                                echo "<td>
                                        <a href='#'><i class='fa-solid fa-pencil'></i></a>
                                        <a href='delete_item.php?id=" . htmlspecialchars($row['id']) . "' onclick='return confirm(\"Are you sure you want to delete this item?\")'><i class='fa-solid fa-trash'></i></a>
                                     </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No data available</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
<script src="script.js"></script>
<script>
    // Logout Functionality
    const logoutOption = document.getElementById('logout-option');
    logoutOption.addEventListener('click', () => {
        window.location.href = 'http://localhost/SEEDLING_PORTAL/index.php'; 
    });

    // category others
    function showOtherCategoryInput() {
        const categorySelect = document.getElementById('category');
        const otherCategoryInput = document.getElementById('other-category');

        if (categorySelect.value === 'Others') {
            otherCategoryInput.style.display = 'block';
        } else {
            otherCategoryInput.style.display = 'none';
        }
    }

    // Function to close the modal
function closeModal() {
    document.getElementById('message-modal').style.display = 'none';
}

// Check for PHP session messages and display the modal
window.onload = function() {
    <?php if (isset($_SESSION['success_message'])): ?>
        const message = "<?php echo $_SESSION['success_message']; ?>";
        document.getElementById('modal-message').textContent = message;
        document.getElementById('message-modal').style.display = 'block';
        <?php unset($_SESSION['success_message']); ?>
    <?php elseif (isset($_SESSION['error_message'])): ?>
        const message = "<?php echo $_SESSION['error_message']; ?>";
        document.getElementById('modal-message').textContent = message;
        document.getElementById('message-modal').style.display = 'block';
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    // Close modal when the overlay is clicked
    const modalOverlay = document.getElementById('modal-overlay');
    modalOverlay.addEventListener('click', closeModal);
};



</script>
</html>