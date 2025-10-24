// This file contains JavaScript code for client-side functionality, such as handling user interactions and AJAX requests.

document.addEventListener('DOMContentLoaded', function() {
    // Function to update the cart display
    function updateCartDisplay() {
        // Fetch cart items from the server or local storage
        fetch('cart.php')
            .then(response => response.json())
            .then(data => {
                const cartItemsContainer = document.getElementById('cart-items');
                cartItemsContainer.innerHTML = '';

                data.items.forEach(item => {
                    const itemElement = document.createElement('div');
                    itemElement.classList.add('cart-item');
                    itemElement.innerHTML = `
                        <h4>${item.name}</h4>
                        <p>Price: $${item.price}</p>
                        <button onclick="removeFromCart(${item.id})">Remove</button>
                    `;
                    cartItemsContainer.appendChild(itemElement);
                });

                const totalElement = document.getElementById('cart-total');
                totalElement.innerText = `Total: $${data.total}`;
            });
    }

    // Function to remove an item from the cart
    window.removeFromCart = function(itemId) {
        fetch(`cart.php?action=remove&id=${itemId}`, { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                updateCartDisplay();
            });
    };

    // Initial call to display cart items
    updateCartDisplay();
});