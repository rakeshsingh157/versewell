
<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Versewell</title>
    <link rel="stylesheet" href="css/style2.css" type="text/css">
    <style>
        .checkout-container { max-width: 600px; margin: 40px auto; background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 8px #ccc; }
        .checkout-section { margin-bottom: 30px; }
        .checkout-section h2 { margin-bottom: 15px; }
        .cart-summary { border: 1px solid #eee; padding: 15px; border-radius: 6px; }
        .cart-item { display: flex; align-items: center; margin-bottom: 10px; }
        .cart-item img { width: 50px; height: 70px; object-fit: cover; margin-right: 15px; }
        .cart-item-title { flex: 1; }
        .cart-item-price { font-weight: bold; }
        .form-group { margin-bottom: 15px;  text-transform: lowercase; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .checkout-btn { background: #4CAF50; color: #fff; border: none; padding: 12px 25px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        .checkout-btn:hover { background: #388e3c; }
    </style>
</head>
<body>
    <div class="checkout-container">
        <button onclick="window.location.href='cart.html'" style="margin-bottom:20px;background:#eee;border:none;padding:8px 18px;border-radius:4px;cursor:pointer;font-size:15px;">
            &larr; Back to Cart
        </button>
        <h1>Checkout</h1>
        
        <div class="checkout-section">
            <h2>Order Summary</h2>
            <div class="cart-summary" id="cart-summary">
                <!-- Cart items will be loaded here by JS -->
            </div>
        </div>

        <form id="checkout-form">
            <div class="checkout-section">
                <h2>Shipping Details</h2>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name'] . ' ' . htmlspecialchars($_SESSION['last_name'])) : ''; ?>">
                </div>
                <div class="form-group" style=" text-transform: lowercase;">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required 
                           value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required 
                           value="<?php echo isset($_SESSION['address']) ? htmlspecialchars($_SESSION['address']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required 
                           value="<?php echo isset($_SESSION['city']) ? htmlspecialchars($_SESSION['city']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" id="state" name="state" required 
                           value="<?php echo isset($_SESSION['state']) ? htmlspecialchars($_SESSION['state']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required 
                           value="<?php echo isset($_SESSION['country']) ? htmlspecialchars($_SESSION['country']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="zip">ZIP Code</label>
                    <input type="text" id="zip" name="zip" required 
                           value="<?php echo isset($_SESSION['postal_code']) ? htmlspecialchars($_SESSION['postal_code']) : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" required 
                           value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>">
                </div>
            </div>

            <div class="checkout-section">
                <h2>Payment Method</h2>
                <div class="form-group">
                    <select id="payment-method" name="payment-method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cod">Cash on Delivery</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="upi">UPI</option>
                        <option value="netbanking">Net Banking</option>
                    </select>
                </div>
                <div id="payment-details"></div>
            </div>

            <button type="submit" class="checkout-btn">Place Order</button>
        </form>
    </div>

    <script>
        // Load cart items from localStorage
        function loadCart() {
            const cart = JSON.parse(localStorage.getItem('cart')) || [];
            const cartSummary = document.getElementById('cart-summary');
            if (cart.length === 0) {
                cartSummary.innerHTML = "<p>Your cart is empty.</p>";
                return;
            }
            let total = 0;
            cartSummary.innerHTML = cart.map(item => {
                total += parseFloat(item.price);
                return `
                    <div class="cart-item">
                        <img src="${item.img}" alt="${item.title}">
                        <div class="cart-item-title">${item.title}</div>
                        <div class="cart-item-price">₹${item.price}</div>
                    </div>
                `;
            }).join('') + `<hr><div style="text-align:right;font-weight:bold;">Total: ₹${total}</div>`;
        }
        loadCart();

        // Show payment details fields based on method
        document.getElementById('payment-method').addEventListener('change', function() {
            const details = document.getElementById('payment-details');
            if (this.value === 'card') {
                details.innerHTML = `
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" name="card-number" required>
                    </div>
                    <div class="form-group">
                        <label for="card-expiry">Expiry Date</label>
                        <input type="text" id="card-expiry" name="card-expiry" placeholder="MM/YY" required>
                    </div>
                    <div class="form-group">
                        <label for="card-cvv">CVV</label>
                        <input type="password" id="card-cvv" name="card-cvv" required>
                    </div>
                `;
            } else if (this.value === 'upi') {
                details.innerHTML = `
                    <div class="form-group">
                        <label for="upi-id">UPI ID</label>
                        <input type="text" id="upi-id" name="upi-id" required>
                    </div>
                `;
            } else {
                details.innerHTML = '';
            }
        });

        // Handle form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Order placed successfully!');
            localStorage.removeItem('cart');
            window.location.href = "html.html";
        });
    </script>
</body>
</html>