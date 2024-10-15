document.addEventListener('DOMContentLoaded', function() {
    // Profile icon functionality
    const profileIcon = document.getElementById('profile-icon');
    const profileModal = document.getElementById('profile-modal');
    const logoutOption = document.getElementById('logout-option');

    if (profileIcon && profileModal) {
        profileIcon.addEventListener('click', function(event) {
            event.stopPropagation();
            profileModal.classList.toggle('show');
        });

        // Close the modal when clicking outside of it
        window.addEventListener('click', function(event) {
            if (!profileModal.contains(event.target) && !profileIcon.contains(event.target)) {
                profileModal.classList.remove('show');
            }
        });
    }

    if (logoutOption) {
        logoutOption.addEventListener('click', function() {
            window.location.href = 'logout.php';
        });
    }

    // Update item in basket
    window.updateItemInBasket = function(itemName, action) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    location.reload(); // Reload the page to see the updated basket
                } else {
                    console.error('Error updating basket:', xhr.status, xhr.statusText);
                    alert('An error occurred while updating the basket. Please try again.');
                }
            }
        };
        const quantity = (action === 'increment') ? 1 : (action === 'decrement') ? -1 : 0;
        xhr.send(`item_name=${encodeURIComponent(itemName)}&quantity=${quantity}`);
    }

    // Check selected items
    window.checkSelectedItems = function() {
        console.log("checkSelectedItems function called");
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        if (checkboxes.length === 0) {
            console.log("No items selected");
            showModal("noItemsModal");
        } else {
            console.log("Items selected, preparing modal");
            let totalSeeds = 0;
            let selectedItemsHtml = "";
            checkboxes.forEach(checkbox => {
                const itemName = checkbox.value;
                const qtyElement = checkbox.closest('.item').querySelector('.qty-display');
                const quantity = parseInt(qtyElement.textContent);
                selectedItemsHtml += `<li>${itemName}: <span>${quantity} seeds</span></li>`;
                totalSeeds += quantity;
            });
            
            showModal("itemsSelectedModal", function() {
                console.log("Modal shown, updating content");
                updateModalContent(selectedItemsHtml, totalSeeds);
            });
        }
    }

    // Update modal content
    function updateModalContent(selectedItemsHtml, totalSeeds) {
        console.log("Updating modal content");
        const modal = document.getElementById("itemsSelectedModal");
        if (!modal) {
            console.error("itemsSelectedModal not found");
            return;
        }

        const selectedItemsList = modal.querySelector("#selectedItemsList");
        const itemsTotalSeeds = modal.querySelector("#itemsTotalSeeds");

        if (selectedItemsList) {
            console.log("Updating selectedItemsList");
            selectedItemsList.innerHTML = selectedItemsHtml;
        } else {
            console.error("selectedItemsList element not found");
        }

        if (itemsTotalSeeds) {
            console.log("Updating itemsTotalSeeds");
            itemsTotalSeeds.textContent = `Total seeds: ${totalSeeds}`;
        } else {
            console.error("itemsTotalSeeds element not found");
        }
    }

    // Show modal
    window.showModal = function(modalId, callback) {
        console.log(`Showing modal: ${modalId}`);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.style.display = "block";
            setTimeout(() => {
                modal.classList.add('show');
                if (typeof callback === 'function') {
                    callback();
                }
            }, 50);
        } else {
            console.error(`Modal with id ${modalId} not found`);
        }
    }

    // Close modal
    window.closeModal = function(modalId) {
        console.log(`Closing modal: ${modalId}`);
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        } else {
            console.error(`Modal with id ${modalId} not found`);
        }
    }

    // Close modal if user clicks outside of it
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            closeModal(event.target.id);
        }
    }

    window.confirmRequest = function() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        const selectedItems = Array.from(checkboxes).map(checkbox => {
            const itemName = checkbox.value;
            const qtyElement = checkbox.closest('.item').querySelector('.qty-display');
            const quantity = parseInt(qtyElement.textContent);
            return `${itemName} (${quantity})`;
        });
    
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                    closeModal('itemsSelectedModal');
                    showModal('confirmationModal');
                    // Clear the basket after successful submission
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    console.error('Error confirming request:', xhr.status, xhr.statusText);
                    alert('An error occurred while confirming your request. Please try again.');
                }
            }
        };
        xhr.send(`selectedItems=${JSON.stringify(selectedItems)}`);
    };
});

console.log("basket.js loaded");
