<?php
// Start the session
session_start();

// Check if the user is logged in as customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // If not logged in as customer, redirect to login page
    header("Location: login.html");
    exit();
}

// Ensure the user_id session variable is set
if (!isset($_SESSION['user_id'])) {
    die("Error: User ID is not set.");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_website";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['user_id'];
    $num_people = $_POST['num_people'];
    $parking_slot = $_POST['parking_slot'];
    $reservation_date = $_POST['reservation_date'];

    $sql = "INSERT INTO reservations (customer_id, num_people, parking_slot, reservation_date, status) VALUES (?, ?, ?, ?, 'pending')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiss", $customer_id, $num_people, $parking_slot, $reservation_date);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation created successfully!');window.location.href = 'view_reservations.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Count the number of items in the cart
$cart_item_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_item_count += $quantity;
    }
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Reservation</title>
    <link rel="stylesheet" href="style/reservation.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>

</head>

<body>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
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
                        <li><a href="view_products_customer.php" class="nav-btn"">Menu</a></li>                      
                        <li><a href=" view_reservations.php" class="nav-btn" style="color: #ffcc00;">Reservations</a></li>
                        <li><a href="eventCustomer.php" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>

    <main>
        <section id="auth">
            <div id="login-form-container">

                <div class="container">
                    <a href="view_reservations.php" class="cart-link">View</a>
                </div>
                <h2>Make a Reservation</h2>
                <form action="create_reservation.php" method="post">
                    <label for="num_people">Number of People:</label>
                    <input type="number" id="num_people" name="num_people" min="1" max=""> <br>

                    <label for=" parking_slot">Parking Slot:</label>
                    <input type="number" id="parking_slot" name="parking_slot" min="1" max=""> <br><br>

                    <label for=" reservation_date">Reservation Date:</label>
                    <input type="datetime-local" id="reservation_date" name="reservation_date" required><br><br>

                    <button type="submit">Submit Reservation</button>
                </form>
            </div>
        </section>
    </main>




</body>

<div>
    <br><br>

</div>

<section>
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
</section>
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
        browser not support the audio
    </audio>
</div>

</html>