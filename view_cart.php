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

// Check if the cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty.'); window.location.href = 'view_products_customer.php';</script>";
    exit();
}

// Handle POST requests for removing items
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove'])) {
        // Remove product from cart
        $product_id = $_POST['product_id'];
        if (isset($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
            if (empty($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
            echo "<script>alert('Product removed from cart!'); window.location.href = 'view_cart.php';</script>";
        }
    }
}

// Get the product IDs from the cart
$cart = $_SESSION['cart'];
$product_ids = implode(',', array_keys($cart));

// Query to get product details for items in the cart
$sql = "SELECT id, name, price FROM products WHERE id IN ($product_ids)";
$result = mysqli_query($conn, $sql);

// Count the number of items in the cart
$cart_item_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_item_count += $quantity;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>


    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-size: 1.5em;
            margin-top: 20px;
            text-align: right;
            width: 80%;
            margin-right: auto;
            margin-left: auto;
        }

        .back-link,
        .confirm-button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
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
            background-color: red;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            text-align: center;

            width: 80px;
        }

        .back-link:hover,
        .confirm-button:hover {
            background-color: #e53935;
        }

        .remove-button {
            background-color: #333;
            color: white;
            border: 3px;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 80px;
        }

        .remove-button:hover {
            background-color: #e53935;
        }

        .action-column {
            width: 100px;
        }
    </style>
</head>

<body>
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
                        <li><a href="eventCustomer.html" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
    <h2>Your Cart</h2>
    <table>
        <tr>
            <th>Product Name</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
            <th class="action-column">Action</th>
        </tr>
        <?php
        $total = 0;
        // Check if there are any products in the result set
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $quantity = $cart[$row['id']];
                $subtotal = $row['price'] * $quantity;
                $total += $subtotal;
                echo '<tr>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>$' . $row['price'] . '</td>';
                echo '<td>' . $quantity . '</td>';
                echo '<td>$' . $subtotal . '</td>';
                echo '<td class="action-column">';
                echo '<form method="post" action="view_cart.php" style="margin: 0;">';
                echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
                echo '<button type="submit" name="remove" class="remove-button">Remove</button>';
                echo '</form>';
                echo '</td>';
                echo '</tr>';
            }
        }
        // Close the database connection
        mysqli_close($conn);
        ?>
    </table>
    <div class="total">
        <strong>Total: $<?php echo $total; ?></strong>
    </div>
    <div class="container">
        <form method="get" action="make_payment.php">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <button type="submit" class="confirm-button">Proceed to Payment</button>
        </form>
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

</body>
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

<!-- Floating Audio Control -->
<div id="audio-control">
    <button id="musicPlaying">&#9654;</button> <!-- Play Icon -->
    <audio id="background-music" loop>
        <source src="images/mp3.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</div>

</html>