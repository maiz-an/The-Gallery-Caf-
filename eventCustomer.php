<?php
// Start the session
session_start();

// Check if the user is logged in as customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // If not logged in as customer, redirect to login page
    header("Location: login.html");
    exit();
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
    <title>Events and Promotions - The Gallery Café</title>
    <link rel="stylesheet" href="style/events_stylesheet.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/cart-btn.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
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
                        <li><a href="customer_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="customer_view_orders.php" class="nav-btn">My-Orders</a></li>
                        <li><a href="view_products_customer.php" class="nav-btn">Menu</a></li>
                        <li><a href="view_reservations.php" class="nav-btn">Reservations</a></li>
                        <li><a href="eventCustomer.php" class="nav-btn" style="color: #ffcc00;">Events & Promotions</a></li>
                        <li><a href="logout.php" class="nav-btn">Log-Out</a></li>
                    </ul>
                </nav>
            </div>
    </header>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
    <main>
        <h2>Events & Promotions</h2> <br>
        <section id="promotions">
            <h2>Upcoming Events</h2>
            <div class="promotion-container">

                <div class="promotion-item">
                    <img src="images/Chef's Special Night.png" alt="chef special">
                    <h3>Chef's Special Night</h3>
                    <p>Exclusive dining experience with a special menu on September 5th.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/Beer Festival.png" alt="beer fest">
                    <h3>Beer Festival</h3>
                    <p>Join our beer festival with local breweries on September 10th.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/Watch classic movies under the stars every Saturday.png" alt="outdoor movie">
                    <h3>Outdoor Movie Night</h3>
                    <p>Watch classic movies under the stars every Saturday.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/cookkingclass.jpeg" alt="cooking class">
                    <h3>Cooking Class</h3>
                    <p>Learn to cook gourmet dishes with our chef on August 15th.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/Wine.jpg" alt="wine">
                    <h3>Wine Tasting Night</h3>
                    <p>Join us for an exquisite wine tasting experience on July 30th.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/LiveMusic.jpg" alt="live music">
                    <h3>Live Music with Local Artists</h3>
                    <p>Enjoy live music performances every Friday night.</p>
                </div>

            </div>
        </section>

        <section id="promotions">
            <h2>Special Promotions</h2>
            <div class="promotion-container">
                <div class="promotion-item">
                    <img src="images/Family Meal Deal.png" alt="family meal">
                    <h3>Family Meal Deal</h3>
                    <p>Special family meal deal for four every Sunday.</p>
                </div>

                <div class="promotion-item">
                    <img src="images/Takeaway Offer.png" alt="takeaway offer">
                    <h3>Takeaway Offer</h3>
                    <p>Get a free drink with every takeaway order over $20.</p>
                </div>



                <div class="promotion-item">
                    <img src="images/HappyHour.jpg" alt="Happy Hour">
                    <h3>Happy Hour</h3>
                    <p>Get 50% off on all beverages from 5 PM to 7 PM daily.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/Brunch.jpg" alt="Weekend Brunch">
                    <h3>Weekend Brunch</h3>
                    <p>Enjoy a special brunch menu every weekend from 10 AM to 2 PM.</p>
                </div>
                <div class="promotion-item">
                    <img src="images/Birthday Discount.gif" alt="birthday discount">
                    <h3>Birthday Discount</h3>
                    <p>Celebrate your birthday with a 20% discount on your total bill.</p>
                </div>

                <div class="promotion-item">
                    <img src="images/Dessert Special.png" alt="dessert special">
                    <h3>Dessert Special</h3>
                    <p>Buy one dessert, get one free every Monday.</p>
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
        Your browser does not support the audio element.
    </audio>
</div>

</html>