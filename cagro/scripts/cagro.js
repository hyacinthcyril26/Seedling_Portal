document.addEventListener('DOMContentLoaded', function () {
    const profileIcon = document.querySelector('.top-right-icon');
    const profileModal = document.getElementById('profile-modal');
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');

    // Toggle the profile modal visibility when the profile icon is clicked
    profileIcon.addEventListener('click', function () {
        profileModal.classList.toggle('show');
    });

    // Close the modal when clicking outside of it
    window.addEventListener('click', function (event) {
        if (!profileModal.contains(event.target) && !profileIcon.contains(event.target)) {
            profileModal.classList.remove('show');
        }
    });

    // Search functionality
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const query = searchInput.value.trim();
        if (query) {
            fetch(`search.php?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    filterCategories(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            showAllCategories();
        }
    });

    function filterCategories(categories) {
        const allItems = document.querySelectorAll('.grid-item');
        allItems.forEach(item => {
            item.style.display = 'none';
        });

        categories.forEach(category => {
            const matchingItems = document.querySelectorAll(`.grid-item[data-category="${category.category}"][data-group="${category.group}"]`);
            matchingItems.forEach(item => {
                item.style.display = 'flex';
            });
        });
    }

    function showAllCategories() {
        const allItems = document.querySelectorAll('.grid-item');
        allItems.forEach(item => {
            item.style.display = 'flex';
        });
    }

    // Set background images for grid items 
    const gridItems = document.querySelectorAll('.grid-item');
    gridItems.forEach(item => {
        const category = item.getAttribute('data-category');
        const group = item.getAttribute('data-group');
        item.style.backgroundImage = `url('images/${group.toLowerCase()}/${category.toLowerCase()}.jpg')`;
    });
});

function openModal(type, category) {
    const modal = document.getElementById("myModal");
    const title = document.getElementById("modal-title");
    const itemsContainer = document.getElementById("items-container");
    itemsContainer.innerHTML = ''; // Clear previous items

    title.innerText = `${category.charAt(0).toUpperCase() + category.slice(1)} ${type}`;

    // Fetch items from the database using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `fetch_items.php?type=${type}&category=${category}`, true);
    // In your openModal function
xhr.onload = function () {
    if (xhr.status === 200) {
        const items = JSON.parse(xhr.responseText);
        items.forEach(item => {
            const itemContainer = document.createElement("div");
            itemContainer.classList.add("item-container");

            // Create the image element
            const itemImage = document.createElement("img");
            itemImage.src = item.image_data; // This now contains the Base64 image data from the database
            itemImage.alt = item.seeds; // Set alt text to the seed name
            itemContainer.appendChild(itemImage); // Append the image to the container
            

                // Item name
                const itemName = document.createElement("div");
                itemName.classList.add("item-name");
                itemName.innerText = item.seeds;
                itemContainer.appendChild(itemName);

                // Item description
                const itemDescription = document.createElement("div");
                itemDescription.classList.add("item-description");
                itemDescription.innerText = item.description || "No description available";
                itemContainer.appendChild(itemDescription);

                // Available quantity
                const availableQty = document.createElement("div");
                availableQty.classList.add("available-qty");
                availableQty.innerText = `Available: ${item.qty}`;
                itemContainer.appendChild(availableQty);

                // Quantity control container
                const qtyContainer = document.createElement("div");
                qtyContainer.classList.add("qty-container");

                const decrementBtn = document.createElement("button");
                decrementBtn.innerText = "-";

                const qtyInput = document.createElement("input");
                qtyInput.type = "text";
                qtyInput.value = 1;
                qtyInput.readOnly = true;

                const incrementBtn = document.createElement("button");
                incrementBtn.innerText = "+";

                qtyContainer.appendChild(decrementBtn);
                qtyContainer.appendChild(qtyInput);
                qtyContainer.appendChild(incrementBtn);

                decrementBtn.onclick = function () {
                    if (qtyInput.value > 1) {
                        qtyInput.value--;
                    }
                };

                incrementBtn.onclick = function () {
                    if (qtyInput.value < 3) {
                        qtyInput.value++;
                    }
                };

                itemContainer.appendChild(qtyContainer);

                const addToBasketBtn = document.createElement("button");
                addToBasketBtn.classList.add("add-to-basket");
                addToBasketBtn.innerText = "Add to Basket";

                let addedToBasket = false;

                addToBasketBtn.onclick = function () {
                    const quantity = qtyInput.value;
                    const successMessage = document.createElement("div");
                    successMessage.classList.add("success-message");

                    if (!addedToBasket) {
                        const data = new FormData();
                        data.append("item_name", item.seeds);
                        data.append("quantity", quantity);

                        const addToBasketXhr = new XMLHttpRequest();
                        addToBasketXhr.open("POST", "basket.php", true);
                        addToBasketXhr.onload = function () {
                            if (addToBasketXhr.status === 200) {
                                successMessage.innerText = `${item.seeds} added to basket! Quantity: ${quantity}`;
                            } else {
                                successMessage.innerText = "Error adding to basket.";
                            }
                        };
                        addToBasketXhr.send(data);

                        addedToBasket = true;
                    } else {
                 successMessage.innerText = `${item.seeds} added to basket! Quantity: ${quantity}`;

                    }

                    itemContainer.appendChild(successMessage);
                    successMessage.style.display = 'block';

                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 2000);
                };

                itemContainer.appendChild(addToBasketBtn);
                itemsContainer.appendChild(itemContainer);
            });
        } else {
            console.error("Failed to fetch items: " + xhr.status);
        }
    };
    xhr.send();

    modal.style.display = "block";
}

function closeModal() {
    const modal = document.getElementById("myModal");
    modal.style.display = "none";
}

window.onclick = function (event) {
    const modal = document.getElementById("myModal");
    if (event.target === modal) {
        closeModal();
    }
};

// Close the modal when pressing the ESC key
window.addEventListener('keydown', function (event) {
    if (event.key === "Escape") {
        closeModal();
    }
});

// Adjust item layout on window resize
window.addEventListener('resize', function () {
    const itemsContainer = document.getElementById("items-container");
    if (itemsContainer) {
        const items = itemsContainer.querySelectorAll('.item-container');
        const containerWidth = itemsContainer.offsetWidth;
        const itemWidth = 250; // Base width of an item
        const gap = 20; // Gap between items
        const itemsPerRow = Math.floor((containerWidth + gap) / (itemWidth + gap));

        items.forEach(item => {
            item.style.flex = `0 1 calc(${100 / itemsPerRow}% - ${gap}px)`;
            item.style.maxWidth = `calc(${100 / itemsPerRow}% - ${gap}px)`;
        });
    }
});
