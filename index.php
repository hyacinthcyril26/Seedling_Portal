<?php
session_start();
// Check for success message
$showSuccessModal = isset($_SESSION['success']) && $_SESSION['success'] === "Registration successful!";
if ($showSuccessModal) {
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seedling Portal Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container">
        <div class="left">
            <img src="logo.png" alt="Tagum City Agriculture Office Logo">
            <h2>TAGUM CITY AGRICULTURE OFFICE</h2>
        </div>

        <div class="right">
            <h2 class="seedling-portal-title">SEEDLING PORTAL</h2>
            <div class="login-box">
                
              
                <form id="loginForm" action="login.php" method="POST">
                    <label for="contactNumber">Contact Number</label>
                    <input type="number" name="contactNumber" placeholder="Contact Number"  required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;" min="0">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required><br>
                      <!-- Error Message Section -->
                      <?php
                if (isset($_SESSION['error1'])) {
                    echo "<div style='color: red; margin-bottom: 20px; font-size: 12px;'>{$_SESSION['error1']}</div>";
                    unset($_SESSION['error1']); 
                }
                ?>
                    <span class="forgot-password" id="forgotPasswordBtn">Forgot password?</span><br>
                    <button type="submit">LOGIN</button>
                </form>
                
                <div class="signup">
                    <p>Don't Have an Account? <span id="openSignUpModal" style="cursor: pointer; color: #2400FF; text-decoration: underline;">Sign Up now.</span></p>
                </div>
            </div>            
        </div>
    </div>

    <!-- Success Modal -->
    <?php if ($showSuccessModal): ?>
        <div id="successModal" class="modal" style="display: block;">
            <div class="modal-content">
                <h4>Registration Successful!</h4>
            </div>
        </div>
    <?php endif; ?>
     
    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeForgotPasswordModal">&times;</span>
            <h2>Forgot Password</h2>
            <form action="forgot_password.php" method="POST" class="forgot-password-box">
                <label for="email">Email Address</label>
                <input type="email" name="email" placeholder="Email Address" required>
                <button type="submit">Reset Password</button>
            </form>
        </div>
    </div>
    
    <!-- sign up as Modal -->
    <div id="signupAsModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeSignupAsModal">&times;</span>
            <h2 style="color: #2e8121; margin-bottom: 20px; text-align: center;">Sign Up As</h2>
            <div style="display: flex; justify-content: center;">
                <button id="adminSignUpBtn" style="background-color: green; color: white; padding: 10px 20px; margin: 10px; border: none; border-radius: 5px; cursor: pointer;">Admin</button>
                <button id="openSignUpModalF" style="background-color: green; color: white; padding: 10px 20px; margin: 10px; border: none; border-radius: 5px; cursor: pointer;">Farmer</button>
            </div>
        </div>
    </div>
    
<!-- Sign Up Modal Admin -->
<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeSignUpModal">&times;</span>
        <h2 style="color: #2e8121; margin-bottom: 20px;">Sign Up As Admin</h2> 
        <form action="signup.php" method="POST" class="signup-box">
            <div class="name-fields">
                <div class="field">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" placeholder="First Name" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;">
                </div>
                <div class="field">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" placeholder="Last Name" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;">
                </div>
            </div>

            <label for="idNumber">ID Number</label>
            <input type="number" name="idNumber" placeholder="ID Number" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;" min="0">

            <label for="contactNumber">Contact Number</label>
            <input type="number" name="contactNumber" placeholder="Contact Number" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;" min="0">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;">

            <label for="cpassword">Confirm Password</label>
            <input type="password" name="cpassword" placeholder="Confirm Password" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;">
                <!-- Error Message Section -->
                <?php
if (isset($_SESSION['admin_error'])) {
    echo "<div style='color: red; margin-bottom: 20px; font-size: 12px;'>{$_SESSION['admin_error']}</div>";
    unset($_SESSION['admin_error']); 
}
?>
            <button type="submit">SIGN-UP</button>
            <div class="sign-up">
                <p>Already Have an Account? <a href="index.php" style="color: #2400FF; text-decoration: underline;">Login here</a></p>
            </div>
        </form>
    </div>
</div>
<!-- Sign Up Modal Farmer -->
<div id="signupModalF" class="modal">
    <div class="modal-content">
        <span class="close" id="closeSignUpModalF">&times;</span>
        <h2 style="color: #2e8121; margin-bottom: 20px;">Sign Up As Farmer</h2> 
        <form action="signup.php" method="POST" class="signup-box">
            <div class="name-fields">
                <div class="field">
                    <label for="firstName">First Name</label>
                    <input type="text" name="firstName" placeholder="First Name" required>
                </div>
                <div class="field">
                    <label for="lastName">Last Name</label>
                    <input type="text" name="lastName" placeholder="Last Name" required>
                </div>
            </div>

            <label for="contactNumber">Contact Number</label>
            <input type="number" name="contactNumber" placeholder="Contact Number" required style="width: 100%; padding: 10px; margin-bottom: 20px; border-radius: 10px; border: 1px solid #ccc; background-color: #f2f2f2;" min="0">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>

            <label for="cpassword">Confirm Password</label>
            <input type="password" name="cpassword" placeholder="Confirm Password" required>
             <!-- Error Message Section -->
             <?php
if (isset($_SESSION['farmer_error'])) {
    echo "<div style='color: red; margin-bottom: 20px; font-size: 12px;'>{$_SESSION['farmer_error']}</div>";
    unset($_SESSION['farmer_error']); 
}
?>
            <button type="submit">SIGN-UP</button>
            <div class="sign-up">
                <p>Already Have an Account? <a href="index.php" style="color: #2400FF; text-decoration: underline;">Login here
                </a></p> 
            </div>

             </form
             > </div>

</div>
 <script src="scripts.js"></script>
<script> 
document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const signupType = urlParams.get('signup');

    // Check if the 'signup' query parameter exists and open the corresponding modal
    if (signupType === 'admin') {
        document.getElementById('signupModal').style.display = 'block'; // Open Admin Sign-up Modal
    } else if (signupType === 'farmer') {
        document.getElementById('signupModalF').style.display = 'block'; // Open Farmer Sign-up Modal
    }
});
</script>
 <script>
        // Check if success modal is displayed
        const successModal = document.getElementById('successModal');
        const closeSuccessModal = document.getElementById('closeSuccessModal');
        const closeSuccessBtn = document.getElementById('closeSuccessBtn');

        if (successModal) {
            // Log that modal is visible
            console.log("Success modal is visible");

            // Auto redirect after 5 seconds
            setTimeout(function() {
                console.log("Redirecting to login page");
                window.location.href = 'index.php';
            }, 1000);

            // Close modal on button click
            closeSuccessModal.onclick = function() {
                successModal.style.display = 'none';
            };
            closeSuccessBtn.onclick = function() {
                successModal.style.display = 'none';
            };
        }
    </script>
    

 </body> </html>