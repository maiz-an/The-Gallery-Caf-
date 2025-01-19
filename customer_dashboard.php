<?php
// Start the session
session_start();

// Check if the user is logged in as customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // If not logged in as customer, redirect to login page
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

// Count the number of items in the cart
$cart_item_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_item_count += $quantity;
    }
}

// Fetch recent orders for the customer
$recent_orders = [];
if (isset($_SESSION['user_id'])) { // Use user_id instead of customer_id
    $customer_id = $_SESSION['user_id'];
    $sql_orders = "SELECT * FROM orders WHERE customer_id = $customer_id ORDER BY order_date DESC LIMIT 5";
    $result_orders = $conn->query($sql_orders);
    if ($result_orders) {
        if ($result_orders->num_rows > 0) {
            while ($row = $result_orders->fetch_assoc()) {
                $recent_orders[] = $row;
            }
        } else {
        }
    } else {
        echo "Error fetching recent orders: " . $conn->error;
    }
} else {
    echo "Customer ID not set in session.";
}

// Fetch recent reservations for the customer
$recent_reservations = [];
if (isset($_SESSION['user_id'])) { // Use user_id instead of customer_id
    $customer_id = $_SESSION['user_id'];
    $sql_reservations = "SELECT * FROM reservations WHERE customer_id = $customer_id ORDER BY reservation_date DESC LIMIT 5";
    $result_reservations = $conn->query($sql_reservations);
    if ($result_reservations) {
        if ($result_reservations->num_rows > 0) {
            while ($row = $result_reservations->fetch_assoc()) {
                $recent_reservations[] = $row;
            }
        } else {
        }
    } else {
        echo "Error fetching recent reservations: " . $conn->error;
    }
} else {
    echo "Customer ID not set in session.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard</title>
    <link rel="stylesheet" href="style/customer_style.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>

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
                        <li><a href="customer_dashboard.php" class="nav-btn" style="color: #ffcc00;">Home</a></li>
                        <li><a href="customer_view_orders.php" class="nav-btn">My-Orders</a></li>
                        <li><a href="view_products_customer.php" class="nav-btn">Menu</a></li>
                        <li><a href="view_reservations.php" class="nav-btn">Reservations</a></li>
                        <li><a href="eventCustomer.php" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>
    <!--  Welcome message for the customer 
    <h2>Welcome to your Dashboard, <?php echo $_SESSION['name']; ?></h2>
    <p>Hello, <?php echo $_SESSION['name']; ?>. This is your dashboard.</p>

     Links for viewing products and logging out
    <a href="view_products_customer.php">View Products</a>
    <a href="logout.php">Logout</a>-->
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>

    <main>
        <section class="event-item">
            <section id="event">
                <div class="event-container">
                    <h2 class="special-font">Welcome to The Gallery Café, <?php echo $_SESSION['name']; ?></h2>
                    <p>We are delighted to have you here! Explore your dashboard to manage your reservations and pre-orders, and stay updated with our latest news and promotions, enjoy your day with us.</p>
            </section>
            <section id="quick-links">
                <h2>Quick Links</h2>
                <div class="links">
                    <a href="create_reservation.php" class="btn">Make a Reservation</a>
                    <a href="view_products_customer.php" class="btn">Pre-order a Meal</a>
                    <a href="customer_about_us.html" class="btn">Learn More About Us</a>
                </div>
                </div>
            </section>
            <section id="latest-updates">
                <h2>Latest Updates & Promotions</h2>
                <div class="updates">
                    <div class="update-item">
                        <img src="images/menu.jpg" alt="New Menu Items">
                        <p><strong>New Menu Items:</strong> Check out our latest additions to the menu, including new vegan options!</p>
                    </div>
                    <div class="update-item">
                        <img src="images/jaza.jpg" alt="Jazz Night">
                        <p><strong>Upcoming Event:</strong> Join us for a live jazz night this Friday. Reserve your table now!</p>
                    </div>
                    <div class="update-item">
                        <img src="images/special_offer.jpg" alt="Special Offer">
                        <p><strong>Special Offer:</strong> Get 20% off on your next reservation when you book online.</p>
                    </div>
                </div>
            </section>
        </section>

        <section id="recent-activities">
            <h2>Recent Activities</h2>
            <div class="activities">
                <div class="activity-item">
                    <h3>Recent Orders</h3>
                    <?php if (count($recent_orders) > 0) : ?>
                        <ul>
                            <?php foreach ($recent_orders as $order) : ?>
                                <li>Order ID: <?php echo $order['id']; ?> - Date: <?php echo $order['order_date']; ?> - Total: <?php echo $order['total']; ?> - Status: <?php echo $order['status']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No recent orders found.</p>
                    <?php endif; ?>
                </div>
                <div class="activity-item">
                    <h3>Recent Reservations</h3>
                    <?php if (count($recent_reservations) > 0) : ?>
                        <ul>
                            <?php foreach ($recent_reservations as $reservation) : ?>
                                <li>Reservation ID: <?php echo $reservation['id']; ?> - Date: <?php echo $reservation['reservation_date']; ?> - People: <?php echo $reservation['num_people']; ?> - Status: <?php echo $reservation['status']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>No recent reservations found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>

    </main>
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