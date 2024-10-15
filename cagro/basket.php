<?php 
session_start(); // Start session

include "config.php"; // Database configuration

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../login.php");
    exit();
}

// Fetch user information once at the start
$resultf = mysqli_query($con, "SELECT * FROM farmers WHERE id='" . $_SESSION['user_id'] . "'");
$rowf = mysqli_fetch_array($resultf);

// Initialize the basket items
$basketItems = isset($_SESSION['basket']) ? $_SESSION['basket'] : [];

// Handle post request for adding/updating items...
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if the request is for basket update or form submission
    if (isset($_POST['item_name']) && isset($_POST['quantity'])) {
        $item_name = $_POST['item_name'];
        $quantity = intval($_POST['quantity']); // Ensure quantity is an integer

        // Initialize the basket in session if it doesn't exist
        if (!isset($_SESSION['basket'])) {
            $_SESSION['basket'] = [];
        }

        // Update the basket
        if ($quantity > 0) {
            // Increment or add item
            if (isset($_SESSION['basket'][$item_name])) {
                $_SESSION['basket'][$item_name] = min(3, $_SESSION['basket'][$item_name] + $quantity); // Increase quantity, max 3
            } else {
                $_SESSION['basket'][$item_name] = min(3, $quantity); // Add new item, max 3
            }
        } elseif ($quantity < 0) {
            // Decrement item
            if (isset($_SESSION['basket'][$item_name])) {
                $_SESSION['basket'][$item_name] = max(1, $_SESSION['basket'][$item_name] + $quantity); // Decrease quantity, min 1
            }
        } elseif ($quantity === 0) {
            // Delete item
            unset($_SESSION['basket'][$item_name]); // Remove item
        }

        // Debug: Show the updated session data
        error_log("Basket updated: " . print_r($_SESSION['basket'], true));

        // Return the updated basket in JSON format
        echo json_encode($_SESSION['basket']);
        exit; // End the script to avoid any further output
    }
}

// Handle form submission for requests
if (isset($_POST['selectedItems'])) {
    $selectedItems = json_decode($_POST['selectedItems'], true); // Decode the JSON array
    
    if (!empty($selectedItems)) {
        // Prepare the SQL statement for inserting items
        $stmt = mysqli_prepare($con, "INSERT INTO requests (user_id, items_requested, qty) VALUES (?, ?, ?)");

        // Array to keep track of items that were inserted
        $insertedItems = [];

        // Loop through each selected item and insert it into the database
        foreach ($selectedItems as $item) {
            preg_match('/^(.*) \((\d+)\)$/', $item, $matches);
            $item_name = $matches[1];
            $quantity = intval($matches[2]);

            // Bind parameters and execute the insert statement
            mysqli_stmt_bind_param($stmt, 'isi', $_SESSION['user_id'], $item_name, $quantity);
            mysqli_stmt_execute($stmt);
            
            // Store the item to be removed from the basket
            $insertedItems[$item_name] = $quantity; 
        }

        // Close the statement after execution
        mysqli_stmt_close($stmt);

        // Clear only the inserted items from the basket session variable
        foreach ($insertedItems as $item_name => $quantity) {
            // Check if the item exists in the basket
            if (isset($_SESSION['basket'][$item_name])) {
                // Reduce the quantity in the basket
                $_SESSION['basket'][$item_name] -= $quantity;

                // If the quantity is zero or less, remove the item from the basket
                if ($_SESSION['basket'][$item_name] <= 0) {
                    unset($_SESSION['basket'][$item_name]);
                }
            }
        }

        // Respond with a success message
        echo json_encode(['success' => true]);
        exit; // End the script after handling the request
    } else {
        // Respond with an error message if no items were selected
        echo json_encode(['error' => 'No items selected']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seedling Portal - Basket</title>
    <link rel="stylesheet" href="styles/basket_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="navbar">
        <div class="logo">Seedling Portal</div>
        <ul class="nav-links">
            <li><a href="cagro.php">Home</a></li>
            <li><a href="#" class="active">Basket</a></li>
            <li><a href="history.php">History</a></li>
            <li><a href="about_us.html">About</a></li>
            <!-- Profile Icon -->
            <li class="profile-icon">
                <i class="fas fa-circle-user top-right-icon" id="profile-icon" style="font-size: 32px; cursor: pointer;"></i>
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

    <div class="item-list">
        <?php if (empty($basketItems)): ?>
            <p>Your basket is empty.</p>
        <?php else: ?>
            <?php 
            $basketItems = array_reverse($basketItems);
            foreach ($basketItems as $item_name => $quantity): ?>
                <div class="item">
                    <input type="checkbox" class="item-checkbox" id="item-<?php echo addslashes($item_name); ?>" value="<?php echo addslashes($item_name); ?>">
                    <div class="image-box"></div>
                    <div class="item-details">
                        <h2><?php echo htmlspecialchars($item_name); ?></h2>
                        <div class="quantity">
                            <span>Qty:</span>
                            <span class="qty-display"><?php echo $quantity; ?></span>
                        </div>
                        <div class="buttons">
                            <button class="btn-qty" onclick="updateItemInBasket('<?php echo addslashes($item_name); ?>', 'increment')">+</button>
                            <button class="btn-qty" onclick="updateItemInBasket('<?php echo addslashes($item_name); ?>', 'decrement')">-</button>
                            <button class="btn-qty" onclick="updateItemInBasket('<?php echo addslashes($item_name); ?>', 'delete')">Delete</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="total-container">
        <span class="total-text">Total: <span class="total-seeds"><?php echo array_sum($basketItems); ?> seeds</span></span>
        <button class="btn-send-request" onclick="checkSelectedItems()">Send Request</button>
    </div>

    <!-- No items selected modal -->
    <div id="noItemsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-circle"></i> No Items Selected</h2>
                <button class="close-modal" onclick="closeModal('noItemsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Please select at least one item to send a request.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal" onclick="closeModal('noItemsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Items Selected modal -->
    <div id="itemsSelectedModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-shopping-basket"></i> Confirm Your Request</h2>
                <button class="close-modal" onclick="closeModal('itemsSelectedModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>You have selected the following items:</p>
                <ul id="selectedItemsList">
                    <!-- Selected items will be displayed here -->
                </ul>
                <div id="itemsTotalSeeds" class="total-seeds-text"></div>
            </div>
            <div class="modal-footer">
                <button class="btn-modal" onclick="closeModal('itemsSelectedModal')">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button class="btn-modal" onclick="confirmRequest()">
                    <i class="fas fa-check"></i> Confirm Request
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-check-circle"></i> Request Sent</h2>
                <button class="close-modal" onclick="closeModal('confirmationModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p>Your request has been sent successfully!</p>
            </div>
            <div class="modal-footer">
                <button class="btn-modal" onclick="closeModal('confirmationModal')">Close</button>
            </div>
        </div>
    </div>

    <script src="scripts/basket.js"></script>
</body>
</html>
