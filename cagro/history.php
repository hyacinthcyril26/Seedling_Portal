<?php 
session_start(); // Start session


include "config.php"; // Database configuration

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

$stmt = null;

// Ensure the user ID is set in the session
if (isset($_SESSION["user_id"])) {
    $user_id = $_SESSION["user_id"];

    $sql = "SELECT * FROM requests WHERE user_id = ?";

    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        die("ERROR: Could not prepare the statement. " . $con->error);
    }
    
    $stmt->bind_param("i", $user_id);

    if (!$stmt->execute()) {
        die("ERROR: Could not execute the statement. " . $stmt->error);
    }
    

    $result = $stmt->get_result();
} else {

    $result = null;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        /* Navigation Bar */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
            padding: 20px 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #006400;
            flex: 1;
        }
        /* Profile Modal Styles */
.profile-modal {
  display: none; 
  position: absolute;
  top: 60px;
  right: 20px;
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  width: 250px;
  padding: 15px;
  z-index: 10;
}
.profile-modal-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.profile-picture {
  display: flex;
  align-items: center;
  background-color: #2B921A;
  padding: 15px;
  width: 100%;
  border-radius: 8px;
  color: white;
  margin-bottom: 15px;
}
.profile-picture i {
  font-size: 20px;
  margin-right: 10px;
}
.profile-picture span {
  font-size: 16px;
}
.profile-options {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.option {
  display: flex;
  align-items: center;
  padding: 10px 15px;
  margin-bottom: 10px;
  width: 100%;
  border-radius: 6px;
  cursor: pointer;
}

.option i {
  font-size: 18px;
  margin-right: 10px;
}

.option:hover {
  background-color: #f0f0f0;
}

.option span {
  font-size: 16px;
}

.logout span {
  color: red;
}

.logout i {
  color: red;
}

/* Show modal on icon click */
.show {
  display: block !important;
}

.nav-links {
  list-style: none;
  display: flex;
  align-items: center;
  margin: 0;
  padding: 0;
}

.profile-icon {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.profile-icon i {
  font-size: 32px;
}

/* Show modal on icon click */
.show {
  display: block !important;
}
        .nav-links {
            list-style-type: none;
            display: flex;
            margin: 0;
        }

        .nav-links li {
            margin-right: 15px;
        }

        .nav-links li a {
            text-decoration: none;
            color: #006400;
            font-size: 16px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .nav-links li a.active {
            background-color: #228B22;
            color: white;
        }

        /* Search and Filter */
        .search-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px;
        }

        .search-container input {
            width: 100%;
            max-width: 300px;
            padding: 10px 40px 10px 20px;
            border-radius: 50px;
            border: 1px solid #ccc;
            font-size: 16px;
            position: relative;
        }

        .search-container i {
            position: absolute;
            left: 25px;
            top: 35px;
            color: #888;
        }

        .filter-btn {
            background-color: #38a169;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            margin-left: 10px;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .filter-btn i {
            margin-right: 5px;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Table Styling */
        .table-container {
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th, td {
            padding: 12px 20px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f1f1f1;
            font-weight: 600;
            border-top: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Border for the table */
        table, th, td {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">Seedling Portal</div>
    <ul class="nav-links">
        <li><a href="cagro.php" >Home</a></li>
        <li><a href="basket.php">Basket</a></li>
        <li><a href="history.php" class="active">History</a></li>
        <li><a href="about_us.php">About Us</a></li>
        <!-- Profile Icon -->
        <li class="profile-icon">
                <i class="fas fa-user-circle" id="profileIcon"></i>
            </li>
    </ul>
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
</div>

    <!-- Search and Filter -->
    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" placeholder="Search...">
        <button class="filter-btn">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <h1 class="title">Request History</h1>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Quantity</th>
                    <th>Seeds Requested</th>
                    <th>Status</th>
                    <th>Date Received</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if (isset($result) && $result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars(date('m/d/Y', strtotime($row['created_at']))) . "</td>
                                <td>" . htmlspecialchars($row['qty']) . "</td>
                                <td>" . htmlspecialchars($row['items_requested']) . "</td>
                                <td>" . htmlspecialchars($row['status']) . "</td>
                                <td>" . htmlspecialchars($row['date_received']) . "</td> 
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requests found.</td></tr>";
                }

                if ($stmt) {
                    $stmt->close();
                }
                $con->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>