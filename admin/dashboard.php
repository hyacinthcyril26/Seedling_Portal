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
<?php session_start(); ?>
    <div class="container">
        <!-- Hamburger Menu Icon -->
        <div class="hamburger" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </div>

        <aside class="sidebar">
            <div class="logo">
                <img src="cagro_logo.png" alt="Company Logo">
            </div>
            <nav>
                <ul>
                    <li>
                        <a href="dashboard.php" class="active">
                            <i class="fa-solid fa-house"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="request.php">
                            <i class="fas fa-file-alt"></i>Request
                        </a>
                    </li>
                    <li>
                        <a href="cat_mgmt.php"><i class="fas fa-cogs">
                            </i>Catalog Management
                        </a>
                    </li>
                    <li>
                        <a href="reports.html">
                            <i class="fas fa-chart-bar"></i>Reports
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <i class="fas fa-solid fas fa-circle-user top-right-icon"></i>  
                <!-- Profile Modal -->
                <div class="profile-modal" id="profile-modal">
                    <div class="profile-modal-content">
                        <div class="profile-picture">
                            <i class="fas fa-user"></i>
                            <span id="greeting">
                Hi, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : ''; ?>
            </span>

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

            <div class="container">
                <div class="status-buttons" >
                    <div class="button active">
                        <span>Total Request</span>
                    </div>
                    <div class="button">
                        <span class="span1">Completed</span>
                    </div>
                    <div class="button">
                    <span class="span2">Pending</span>
                    </div>
                    <div class="button">
                    <span class="span3">In Progress</span>
                    </div>
                </div> 
            </div>
            <div class="content-area">  
                <h2>Summary</h2>
                <p>Most Requested Seeds</p>
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Type of Seed</th>
                            <th>No. of Times Requested</th>
                            <th>No. of Seeds Available</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Tuber Vegetables</td>
                            <td>Patatas</td>
                            <td>76</td>
                            <td>21 pcs</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
<script>
    // Profile Modal Toggle
    const profileIcon = document.querySelector('.top-right-icon');
    const profileModal = document.getElementById('profile-modal');

    // Add click event listener to the profile icon
    profileIcon.addEventListener('click', () => {
        // Toggle the modal's visibility when the icon is clicked
        profileModal.style.display = profileModal.style.display === 'block' ? 'none' : 'block';
    });

    // Close modal if clicked outside
    window.addEventListener('click', (e) => {
        if (!profileModal.contains(e.target) && !profileIcon.contains(e.target)) {
            profileModal.style.display = 'none';
        }
    });
    
    // hamburger
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        sidebar.classList.toggle('active');
    }

    // Logout Functionality
    const logoutOption = document.getElementById('logout-option');

    logoutOption.addEventListener('click', () => {
        window.location.href = 'http://localhost/SEEDLING_PORTAL/index.php'; 
    });
</script>

</html>