// Handle "Add to Cart" button click
$(document).ready(function () {
    // Add to cart from product detail page
    $(".add-to-cart").click(function () {
        const productId = $(this).data("id");
        const productName = $(this).data("name");
        const productPrice = $(this).data("price");
        const productQuantity = $("#quantity").val() || 1; // Get the quantity, default to 1 if not set

        const cartItem = {
            id: productId,
            name: productName,
            price: productPrice,
            quantity: productQuantity
        };

        // Save item to session cart (or local storage as an alternative)
        addToCart(cartItem);
    });

    // Handle cart actions like quantity update
    $(".update-cart").click(function () {
        const productId = $(this).data("id");
        const newQuantity = $("#quantity_" + productId).val();

        // Update cart item with new quantity
        updateCartItem(productId, newQuantity);
    });

    // Handle cart item removal
    $(".remove-item").click(function () {
        const productId = $(this).data("id");

        // Remove item from cart
        removeCartItem(productId);
    });

    // Add item to cart function
    function addToCart(item) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // Check if item already exists in cart
        const existingItemIndex = cart.findIndex(product => product.id === item.id);
        if (existingItemIndex >= 0) {
            // Update the quantity of the existing item
            cart[existingItemIndex].quantity += item.quantity;
        } else {
            // Add new item to cart
            cart.push(item);
        }

        // Save updated cart to localStorage
        localStorage.setItem("cart", JSON.stringify(cart));

        // Update cart count on UI
        updateCartCount();
    }

    // Update cart item quantity
    function updateCartItem(productId, newQuantity) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // Find the item and update quantity
        const itemIndex = cart.findIndex(product => product.id === productId);
        if (itemIndex >= 0) {
            cart[itemIndex].quantity = newQuantity;
            localStorage.setItem("cart", JSON.stringify(cart));
        }

        // Update cart count on UI
        updateCartCount();
    }

    // Remove item from cart
    function removeCartItem(productId) {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];

        // Remove the item from the cart
        cart = cart.filter(product => product.id !== productId);
        localStorage.setItem("cart", JSON.stringify(cart));

        // Update cart count on UI
        updateCartCount();
    }

    // Update cart item count on the header or cart page
    function updateCartCount() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        const cartCount = cart.reduce((total, product) => total + product.quantity, 0);

        // Update cart count display in the navigation or cart page
        $("#cart-count").text(cartCount);
    }

    // Display cart items in cart page
    function displayCartItems() {
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        const cartTable = $("#cart-items");

        cartTable.empty(); // Clear existing rows

        if (cart.length > 0) {
            cart.forEach(item => {
                const cartRow = `<tr>
                    <td>${item.name}</td>
                    <td>$${item.price}</td>
                    <td><input type="number" id="quantity_${item.id}" value="${item.quantity}" class="form-control" min="1"></td>
                    <td>$${(item.price * item.quantity).toFixed(2)}</td>
                    <td><button class="btn btn-danger remove-item" data-id="${item.id}">Remove</button></td>
                </tr>`;
                cartTable.append(cartRow);
            });
        } else {
            cartTable.append("<tr><td colspan='5'>Your cart is empty.</td></tr>");
        }
    }

    // Initialize cart items display when page loads (Cart page)
    if ($("#cart-items").length > 0) {
        displayCartItems();
    }

    // Initialize cart count display (for all pages)
    updateCartCount();
});
