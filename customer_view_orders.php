<?php
// Start the session
session_start();

// Check if the user is logged in as a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: login.html");
    exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_website";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get customer ID from session
$customer_id = $_SESSION['user_id'];

// Query to select orders for the logged-in customer
$sql = "SELECT id, product_ids, quantities, total, order_date, status FROM orders WHERE customer_id='$customer_id'";
$result = mysqli_query($conn, $sql);

// Function to get product names by IDs
function getProductNames($conn, $product_ids)
{
    $ids = explode(',', $product_ids);
    $names = [];
    foreach ($ids as $id) {
        $product_sql = "SELECT name FROM products WHERE id='$id'";
        $product_result = mysqli_query($conn, $product_sql);
        if ($product_row = mysqli_fetch_assoc($product_result)) {
            $names[] = $product_row['name'];
        }
    }
    return implode(', ', $names);
}
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
    <title>Your Orders</title>
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>

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
                        <li><a href="customer_view_orders.php" class="nav-btn" style="color: #ffcc00;">My-Orders</a></li>
                        <li><a href="view_products_customer.php" class="nav-btn">Menu</a></li>
                        <li><a href="view_reservations.php" class="nav-btn">Reservations</a></li>
                        <li><a href="eventCustomer.php" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <h2>Your Orders</h2>
    <table>
        <tr>
            <th>Order ID</th>
            <th>Products</th>
            <th>Quantities</th>
            <th>Total</th>
            <th>Order Date</th>
            <th>Status</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $product_names = getProductNames($conn, $row['product_ids']);
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $product_names . "</td>";
                echo "<td>" . $row['quantities'] . "</td>";
                echo "<td>$" . $row['total'] . "</td>";
                echo "<td>" . $row['order_date'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No orders found</td></tr>";
        }
        // Close the database connection
        mysqli_close($conn);
        ?>
    </table>
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
        <a href="contact_form.html" class="contact-btn">
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