<?php
// Start the session
session_start();

// Check if the user is logged in as a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // Redirect to login page if not a customer
    header("Location: login.html");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_website";

// Create a new database connection using procedural style
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle adding products to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Initialize the cart if not already set
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Update the cart with the new product or add a new product
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $quantity;
    } else {
        $_SESSION['cart'][$product_id] = $quantity;
    }

    // Alert user and redirect to product view page
    header("Location: view_products_customer.php");
    exit();
}

// Count the number of items in the cart
$cart_item_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_item_count += $quantity;
    }
}

// Query to select product details
$sql = "SELECT id, name, description, price, stock, image FROM products ORDER BY id ASC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Products</title>

    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>
    <script>
        function searchProducts() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var productCards = document.getElementsByClassName('product-card');

            for (var i = 0; i < productCards.length; i++) {
                var card = productCards[i];
                var productName = card.getElementsByTagName('h3')[0].innerText.toLowerCase();
                var productDescription = card.getElementsByTagName('p')[0].innerText.toLowerCase();

                if (productName.indexOf(filter) > -1 || productDescription.indexOf(filter) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Restore scroll position
            if (sessionStorage.getItem('scrollPosition')) {
                window.scrollTo(0, sessionStorage.getItem('scrollPosition'));
                sessionStorage.removeItem('scrollPosition');
            }

            // Function to show the notification
            function showNotification(message) {
                var notification = document.createElement('div');
                notification.id = 'notification';
                notification.innerText = message;
                document.body.appendChild(notification);
                setTimeout(function() {
                    notification.remove();
                }, 3000); // Adjust time as needed
            }

            // Check for notification in sessionStorage
            if (sessionStorage.getItem('notification')) {
                showNotification(sessionStorage.getItem('notification'));
                sessionStorage.removeItem('notification');
            }

            // Add event listener to form submit buttons
            var addToCartForms = document.querySelectorAll('form[action="view_products_customer.php"]');
            addToCartForms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    // Save scroll position
                    sessionStorage.setItem('scrollPosition', window.scrollY);

                    // Save notification message
                    sessionStorage.setItem('notification', 'Product added to cart!');
                });
            });
        });
    </script>


    <style>
        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 300px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            max-width: 100%;
            height: 400px;
            border-radius: 10px;
        }

        .product-card h3 {
            margin: 10px 0;
        }

        .product-card p {
            color: #777;
        }

        .product-card .price {
            font-size: 1.2em;
            color: #333;
        }

        .product-card form {
            margin-top: 10px;
        }

        .product-card input[type="number"] {
            width: 60px;
            padding: 5px;
            margin-right: 10px;
        }

        .product-card button {
            padding: 5px 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .product-card button:hover {
            background-color: #444;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 10vh;

        }

        .cart-link {
            display: block;
            margin: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            text-align: center;
            text-transform: uppercase;
            width: 150px;
        }


        .cart-link:hover {
            background-color: #45a049;
        }

        #searchInput {
            width: 80%;
            padding: 0.5em;
            margin: 0 auto 1em auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #333;
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 1000;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
    </style>
    <header>
        <div class="header-container">
            <a href="customer_dashboard.php" class="logo">
                <img src="images/logo.png" alt="The Gallery Café Logo">
            </a>
            <h1>The Gallery Café</h1>
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="customer_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="customer_view_orders.php" class="nav-btn">My-Orders</a></li>
                        <li><a href="view_products_customer.php" class="nav-btn" style="color: #ffcc00;">Menu</a></li>
                        <li><a href="view_reservations.php" class="nav-btn">Reservations</a></li>
                        <li><a href="eventCustomer.php" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>
</head>

<body>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
    <h2>Available Products</h2>
    <input type="text" id="searchInput" onkeyup="searchProducts()" placeholder="Search for products..">

    <div class="products-container">

        <?php
        // Check if there are any products in the result set
        if (mysqli_num_rows($result) > 0) {
            // Loop through each product and display it
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="product-card">';
                echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="Product Image"/>';
                echo '<h3>' . $row['name'] . '</h3>';
                echo '<p>' . $row['description'] . '</p>';
                echo '<p class="price">$' . $row['price'] . '</p>';
                echo '<form method="post" action="view_products_customer.php">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<input type="number" name="quantity" value="1" min="1" max="' . $row['stock'] . '" required>';
                echo '<button type="submit" name="add_to_cart">Add to Cart</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            // Display a message if no products are available
            echo '<p>No products available.</p>';
        }
        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>


    <footer>
        <div class="footer-container">

            <div class="social-icons">
                <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i></a>
                <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://linkedin.com" target="_blank"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
            <p>123 Main Street, Colombo, Sri Lanka | Phone: +94 77 123 4567 | Email: info@gallerycafe.com</p>
        </div>
    </footer>
    <!-- Floating Cart Button -->
    <div class="floating-cart">
        <a href="view_cart.php" class="cart-btn">
            <img src="images/cart-icon.png" alt="View Cart">
            <?php if ($cart_item_count > 0) : ?>
                <span class="cart-count"><?php echo $cart_item_count; ?></span>
            <?php endif; ?>
        </a>
    </div>


    <!-- Floating Contact Button -->
    <div class="floating-contact">
        <a href="contact_form.php" class="contact-btn">
            <img src="images/customer-service-icon.png" alt="Contact Us">
        </a>
    </div>

</body>

<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <a href="#top" class="scroll-btn">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>
<!-- Floating Audio Control -->
<div id="audio-control">
    <button id="musicPlaying">&#9654;</button> <!-- Play Icon -->
    <audio id="background-music" loop>
        <source src="images/mp3.mp3" type="audio/mpeg">
        browser not support the audio
    </audio>
</div>

</html>