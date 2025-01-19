<?php
// Start the session
session_start();

// Check if the user is logged in as a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // Redirect to login page if not a customer
    header("Location: login.html");
    exit();
}

// Check if total is passed
if (!isset($_GET['total'])) {
    header("Location: view_cart.php");
    exit();
}

// Get the total amount from the URL
$total = $_GET['total'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <style>
        .payment-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .payment-container h2 {
            margin-bottom: 20px;
        }

        .payment-container label {
            display: block;
            margin-bottom: 10px;
        }

        .payment-container input[type="text"],
        .payment-container input[type="month"],
        .payment-container input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .payment-container .btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .payment-container .btn:hover {
            background-color: #444;
        }

        .input-container {
            position: relative;
        }

        .input-container label.placeholder {
            position: absolute;
            top: 10px;
            left: 10px;
            color: #aaa;
            pointer-events: none;
            transition: 0.2s;
        }

        .input-container input:focus+label.placeholder,
        .input-container input:not(:placeholder-shown)+label.placeholder {
            top: -20px;
            left: 0;
            font-size: 12px;
            color: #333;
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
    <div class="payment-container">
        <h2>Choose Payment Option</h2>
        <form method="post" action="process_payment.php">
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <label>
                <input type="radio" name="payment_option" value="full" required>
                Full Payment - $<?php echo $total; ?>
            </label>
            <label>
                <input type="radio" name="payment_option" value="advance" required>
                Advance Payment - $<?php echo $total / 2; ?>
            </label>
            <div class="payment-details">
                <h2>Payment Details</h2>
                <label for="card_name">Cardholder Name</label>
                <input type="text" id="card_name" name="card_name" placeholder="Name on your Cart" required>

                <label for="card_number">Debit/Credit Card Number</label>
                <input type="number" id="card_number" name="card_number" placeholder="Enter your card number" min="1" max="9999999999999999" required>

                <label for="expiry_date">Expiry Date (MM/YY) </label>
                <input type="month" id="expiry_date" name="expiry_date" required>

                <label for="cvv">CVV</label>
                <input type="number" id="cvv" name="cvv" placeholder="CVV" min="1" max="999" required>
            </div>
            <button type="submit" class="btn">Proceed to Payment</button>
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