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
                        <a href="dashboard.php">
                            <i class="fa-solid fa-house"></i>Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="request.php" class="active">
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
                <header>
                    <h1>Clients Requests</h1>
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" id="search-input" placeholder="Search by name">
                    </div>
                </header>
                <div class="table-wrapper">
                    <table id="request-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact Number</th>
                                <th>Items Requested</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="request-data">
                            <!-- Data will be loaded here dynamically using JS -->
                        </tbody>
                    </table>
                </div>
 <!-- Modal -->
<div id="statusModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Update Status</h2>
        <form id="statusForm">
            <input type="hidden" id="requestId">
            
            <div class="status-option">
                <label>
                    <input type="radio" name="status" value="Approved" required> Approved
                </label>
            </div>
            <div class="status-option">
                <label>
                    <input type="radio" name="status" value="Rejected" required> Rejected
                </label>
            </div>
            <div class="status-option">
            <label>
            <input type="radio" name="status" value="Claimed" onclick="toggleDateInput()"> Claimed
        </label>
        </div>
        
        <div id="dateInputContainer" style="display: none;">
            <label for="claimedDate">Claimed Date:</label>
            <input type="date" id="claimedDate">
        </div>
            <button type="button" class="submit-button" onclick="submitStatus()">Submit</button>
        </form>
    </div>
</div>

            </main>
        </div>
        
<script>
   

   function fetchRequests() {
    fetch('fetch_requests.php')
        .then(response => response.json())
        .then(data => {
            const requestData = document.getElementById('request-data');
            requestData.innerHTML = ''; // Clear previous data

            data.forEach(request => {
                const statusClass = request.status === 'Approved' ? 'status-approved' : request.status === 'Rejected' ? 'status-rejected' : '';
                requestData.innerHTML += `
                    <tr>
                        <td>${request.full_name}</td>
                        <td>${request.contact_number}</td>
                        <td>${request.items_requested}</td>
                        <td>${request.qty}</td>
                        <td id="status-${request.id}" class="${statusClass}">${request.status}</td>
                        <td>
                            <i class="fas fa-pencil-alt action-icon" data-id="${request.id}" onclick="openStatusModal(${request.id})"></i>
                        </td>
                    </tr>`;
            });
        })
        .catch(error => console.error('Error fetching requests:', error)); // Error handling
}



            function toggleDateInput() {
    const status = document.querySelector('input[name="status"]:checked');
    const dateInputContainer = document.getElementById('dateInputContainer');
    
    if (status && status.value === 'Claimed') {
        dateInputContainer.style.display = 'block'; // Show date input for Claimed status
    } else {
        dateInputContainer.style.display = 'none'; // Hide date input for other statuses
    }
}

// Open the modal and populate it with the current request ID
function openStatusModal(requestId) {
    document.getElementById('statusModal').style.display = 'block';
    document.getElementById('requestId').value = requestId;

    // Reset the radio buttons and hide date input initially
    const statusRadios = document.querySelectorAll('input[name="status"]');
    statusRadios.forEach(radio => {
        radio.checked = false; // Uncheck all radios
    });
    document.getElementById('dateInputContainer').style.display = 'none'; // Hide date input
}

// Close the modal
function closeModal() {
    document.getElementById('statusModal').style.display = 'none';
}

function submitStatus() {
    const requestId = document.getElementById('requestId').value;
    const status = document.querySelector('input[name="status"]:checked');

    if (!status) {
        alert('Please select a status.');
        return;
    }

    const statusValue = status.value;
    let claimedDate = null;

    // If Claimed is selected, get the date
    if (statusValue === 'Claimed') {
        claimedDate = document.getElementById('claimedDate').value;
        if (!claimedDate) {
            alert('Please select a claimed date.');
            return;
        }
    }

    console.log(`Request ID: ${requestId}, Status: ${statusValue}, Claimed Date: ${claimedDate}`); // Debugging line

    // Update the status in the table
    const statusCell = document.getElementById(`status-${requestId}`);
    statusCell.innerText = statusValue;

    // Clear previous status classes
    statusCell.classList.remove('status-approved', 'status-rejected');

    // Add the new status class
    if (statusValue === 'Approved') {
        statusCell.classList.add('status-approved');
    } else if (statusValue === 'Rejected') {
        statusCell.classList.add('status-rejected');
    }

    // Close the modal after submission
    closeModal();

    // Send the update request to the server
    fetch('update_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${requestId}&status=${statusValue}&claimedDate=${claimedDate}`
    })
    .then(response => response.text())
    .then(data => {
        console.log(data); // Debugging line to check server response
        alert(data); // Alert the response from the server
    })
    .catch(error => console.error('Error:', error));
}



        // Load data when the page is ready
        window.onload = fetchRequests;
 // Search functionality
 document.getElementById('search-input').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#request-data tr'); // Look at tbody rows only

            rows.forEach(row => {
                const name = row.cells[0].textContent.toLowerCase();
                if (name.includes(searchValue)) {
                    row.style.display = ''; // Show the row
                } else {
                    row.style.display = 'none'; // Hide the row
                }
            });
        });
    </script>
     
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