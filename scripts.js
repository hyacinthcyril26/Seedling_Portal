// ----- Modal Functionality for Sign Up -----
// Get the farmer sign-up button and modal
var openSignUpModalF = document.getElementById("openSignUpModalF");
var signupModalF = document.getElementById("signupModalF");

// Show the sign-up modal for Farmer when the button is clicked
openSignUpModalF.onclick = function() {
    signupModalF.style.display = "block"; // Show the sign-up for Farmer modal
}

// Get the close button of the sign-up modal for Farmer
var closeSignUpModalF = document.getElementById("closeSignUpModalF");

// Close the sign-up modal for Farmer when the close button is clicked
closeSignUpModalF.onclick = function() {
    signupModalF.style.display = "none"; // Hide the sign-up for Farmer modal
}

// Close the sign-up modal for Farmer if the user clicks outside of it
window.onclick = function(event) {
    if (event.target == signupModalF) {
        signupModalF.style.display = "none"; // Hide the modal if clicked outside
    }
}

// ----- Sign Up Modal Functionality -----

var signupModal = document.getElementById("signupModal");
var signupAsModal = document.getElementById("signupAsModal");
var openSignUpModal = document.getElementById("openSignUpModal");
var closeSignUpModal = document.getElementById("closeSignUpModal");
var closeSignupAsModal = document.getElementById("closeSignupAsModal");

// When the "Sign Up now" link is clicked, show the sign-up as modal
openSignUpModal.onclick = function() {
    signupAsModal.style.display = "block";  // Show the sign-up as modal
}

// Close the sign-up modal when the close button is clicked
closeSignUpModal.onclick = function() {
    signupModal.style.display = "none";  // Hide the sign-up modal
    signupAsModal.style.display = "block"; // Show the sign-up as modal again
}

// Close the sign-up as modal when the close button is clicked
closeSignupAsModal.onclick = function() {
    signupAsModal.style.display = "none"; // Hide the sign-up as modal
}

// When the admin button is clicked, show the sign-up modal
var adminSignUpBtn = document.getElementById("adminSignUpBtn");
adminSignUpBtn.onclick = function() {
    console.log("Admin button clicked"); // Log action
    signupAsModal.style.display = "none"; // Hide the sign-up as modal
    signupModal.style.display = "block"; // Show the sign-up modal
}

// Function to get URL parameters
function getUrlParam(param) {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
}

// Show success modal if registration was successful
document.addEventListener('DOMContentLoaded', function () {
    // Check if the success message div is visible
    const successMessageDiv = document.getElementById('successMessage');
    if (successMessageDiv && successMessageDiv.style.display === 'block') {
        setTimeout(() => {
            successMessageDiv.style.display = 'none';
        }, 5000);
    }
});
