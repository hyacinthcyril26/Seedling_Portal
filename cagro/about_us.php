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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Tagum City Agricultural Office</title>
    <link rel="stylesheet" href="styles/cagro_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .content {
            max-width: 76%;
            margin: 0 auto;
            padding: 20px;  
            display: block;
            align-items: center;
            
        }
        .office-info {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            justify-content: center;
        }
        .office-logo {
            width: 150px;
            height: 150px;
            margin-right: 20px;
            object-fit: cover;
        }
        .mission-vision {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .mission, .vision {
            width: 46%;
        }
        h2{
            text-align: center;
        }
        p{
            text-align: justify;
        }
        .mission img {
            max-width: 100%;
            width: 420px;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
            margin-top: 70px;
        }
        .vision img {
            max-width: 100%;
            width: 420px;
            height: 200px;
            object-fit: cover;
            margin-bottom: 10px;
            margin-top: 14px;
        }
        .events {
            display: flex;
            justify-content: space-between;
        }
        .event-image {
            width: 32%;
            height: 250px;
            object-fit: cover;
            margin-bottom: 150px;
        }
        footer {
            background-color: #4CAF50;
            color: white;
            padding: 20px 0;
            
        }
        .footer-content {
            margin-left: 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .footer-content img{
            margin-left: 40px;
        }
        .footer-logo {
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
            padding: 10px;
            margin-right: 20px;
        }
        .footer-section {
            flex: 1;
        }
        .footer-section h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .footer-section p {
            margin: 5px 0;
        }
        .social-icon {
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
            filter: invert(1);
        }
        .footer-section .social{
            margin-left: 42px;
        }   
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">SEEDLING PORTAL</div>
        <ul class="nav-links">
            <li><a href="cagro.php">Home</a></li>
            <li><a href="basket.php">Basket</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="about_us.php" class="active">About Us</a></li>
            <li class="profile-icon">
                <i class="fas fa-user-circle" id="profileIcon"></i>
            </li>
        </ul>
    </nav>

    <div class="profile-modal" id="profileModal">
        <div class="profile-modal-content">
            <div class="profile-picture">
                <i class="fas fa-user-circle"></i>
                <span id="greeting">
                                Hi, <?php echo htmlspecialchars($rowf["first_name"] ?? ''); ?>s
                            </span>
            </div>
            <div class="profile-options">
                <div class="option">
                    <i class="fas fa-user-edit"></i>
                    <span>Edit Profile</span>
                </div>
                <div class="option logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <a href="../logout.php" style="text-decoration: none; color: inherit;">
                        <span>Log Out</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="office-info">
            <img src="images/logo.png" alt="TAGUM CITY AGRICULTURAL OFFICE" class="office-logo">
            <h1>TAGUM CITY AGRICULTURAL OFFICE</h1>
        </div>
        
        <div class="mission-vision">
            <div class="mission">
                <h2>MISSION</h2>
                <p>To promote sustainable agricultural development and food security in Tagum City by 
                    empowering local farmers, advancing eco-friendly farming practices, and fostering 
                    community participation through innovative programs and services that enhance the 
                    quality of life for all.
                </p>
                <img src="images/farmers.png" alt="Farmers working in a field">
            </div>
            <div class="vision">
                <img src="images/group_photo.png" alt="Group photo of agricultural office staff">
                <h2>VISION</h2>
                <p>A thriving agricultural community in Tagum City, where sustainable farming and 
                    inclusive growth drive food security, economic resilience, and environmental 
                    stewardship for future generations.
                </p>
            </div>
        </div>

        <h2>EVENTS</h2>
        <div class="events">
            <img src="images/trio.png" alt="Bulb Vegetables" class="event-image">
            <img src="images/humay.png" alt="Cucurbits" class="event-image">
            <img src="images/expo.png" alt="Vegetable Expo" class="event-image">
        </div>
    </div>

    <footer>
        <div class="container">
            <div class="footer-content">
                <img src="images/tagumlogo.png" alt="Tagum City Agricultural Office Logo" class="footer-logo">
                <div class="footer-section">
                <h3 class="social">SOCIALS</h3> 
                    <p><img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook" class="social-icon"> Tagum City Agriculture Office - CAGRO</p>
                    <p><img src="https://cdn-icons-png.flaticon.com/512/1006/1006771.png" alt="Website" class="social-icon"> www.tagumcity.gov.ph</p>
                </div>
                <div class="footer-section">
                    <h3>VISIT US</h3>
                    <p>Energy Park, Apokon, Tagum City</p>
                </div>
                <div class="footer-section">
                    <h3>CONTACT INFORMATION</h3>
                    <p>Telephone Number: (084) 216 2716</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profileIcon = document.getElementById('profileIcon');
            const profileModal = document.getElementById('profileModal');

            profileIcon.addEventListener('click', function() {
                profileModal.classList.toggle('show');
            });

            window.addEventListener('click', function(event) {
                if (!event.target.matches('.fa-user-circle') && !profileModal.contains(event.target)) {
                    profileModal.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>