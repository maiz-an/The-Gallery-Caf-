<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
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

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - The Gallery Café</title>
    <link rel="stylesheet" href="style/admin_dash.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>

    <header>
        <h1>The Gallery Café - Staff</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="staff_dashboard.php" class="nav-btn" style="color: #ffcc00;">Home</a></li>
                        <li><a href="staff_view_user.php" class="nav-btn">Users</a></li>
                        <li><a href="staff_view_products.php" class="nav-btn">Menu</a></li>
                        <li><a href="staff_manage_orders.php" class="nav-btn">Orders</a></li>
                        <li><a href="staff_manage_reservations.php" class="nav-btn">Reservation</a></li>
                        <li><a href="view_employees.php" class="nav-btn">Employees</a></li>
                        <li><a href="staff_view_payments.php" class="nav-btn">Payments</a></li>
                        <li><a href="staff_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                        <li><a href="logout.php" class="nav-btn">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </header>


    <div class="content1">

        <div class="dashboard">
            <div class="card">
                <a href="staff_view_user.php">Users</a>
                <p><br>View User Details</p>
            </div>

            <div class="card">
                <a href="staff_view_products.php">Menu</a>
                <p><br>Add/View/Search/Edit/Delete in the Menu</p>
            </div>

            <div class="card">
                <a href="staff_manage_orders.php">Orders</a>
                <p><br>View/Change status of the Ordrers</p>
            </div>
            <div class="card">
                <a href="staff_manage_reservations.php">Reservations</a>
                <p><br>Add/View/Change status of the Reservation</p>
            </div>
            <div class="card">
                <a href="view_employees.php">Employees</a>
                <p><br>View Employee Details</p>
            </div>
            <div class="card">
                <a href="staff_view_contacts.php">Feedbacks</a>
                <p><br>Read the feedback from the user</p>
            </div>

        </div>
    </div>
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



</html>