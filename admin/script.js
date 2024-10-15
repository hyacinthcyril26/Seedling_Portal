// Get the modal
var modal = document.getElementById("add-modal");

// Get the button that opens the modal
var btn = document.getElementsByClassName("filter-btn")[0];

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal
btn.onclick = function() {
    modal.style.display = "block"; // Show the modal when the button is clicked
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none"; // Hide the modal when the 'X' is clicked
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none"; // Close modal if user clicks outside of it
    }
}

// Profile Modal Toggle
const profileIcon = document.querySelector('.top-right-icon');
const profileModal = document.getElementById('profile-modal');

// Add click event listener to the profile icon
profileIcon.addEventListener('click', () => {
    // Toggle the modal's visibility when the icon is clicked
    if (profileModal.style.display === 'block') {
        profileModal.style.display = 'none';
    } else {
        profileModal.style.display = 'block';
    }
});
// Close modal if clicked outside
window.addEventListener('click', (e) => {
    if (!profileModal.contains(e.target) && !profileIcon.contains(e.target)) {
        profileModal.style.display = 'none';
    }
});
// sidebar
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    sidebar.classList.toggle('active');
}

