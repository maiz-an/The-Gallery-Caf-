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

// Fetch customers for the dropdown
$sql = "SELECT id, name FROM users WHERE role='customer'";
$customers = mysqli_query($conn, $sql);

$reservation_success = false;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $num_people = $_POST['num_people'];
    $parking_slot = $_POST['parking_slot'];
    $reservation_date = $_POST['reservation_date'];

    $sql = "INSERT INTO reservations (customer_id, num_people, parking_slot, reservation_date, status) 
            VALUES ('$customer_id', '$num_people', '$parking_slot', '$reservation_date', 'pending')";

    if (mysqli_query($conn, $sql)) {
        $reservation_success = true;
    } else {
        $error_message = "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Reservation - Staff</title>
    <link rel="stylesheet" href="style/admin_manage_users.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script>
        function showAlert(message, redirectUrl = null) {
            alert(message);
            if (redirectUrl) {
                window.location.href = redirectUrl;
            }
        }
    </script>
</head>

<body>
    <header>
        <h1>The Gallery Café - Staff</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="staff_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="staff_view_user.php" class="nav-btn">Users</a></li>
                        <li><a href="staff_view_products.php" class="nav-btn">Menu</a></li>
                        <li><a href="staff_manage_orders.php" class="nav-btn">Orders</a></li>
                        <li><a href="staff_manage_reservations.php" class="nav-btn" style="color: #ffcc00;">Reservation</a></li>
                        <li><a href="view_employees.php" class="nav-btn">Employees</a></li>
                        <li><a href="staff_view_payments.php" class="nav-btn">Payments</a></li>
                        <li><a href="staff_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                        <li><a href="logout.php" class="nav-btn">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </header>
    <main>
        <div class="form-container">
            <a href="staff_manage_reservations.php" class="cart-link">View</a>
            <h2>Make a Reservation</h2>
            <form method="post">
                <label for="customer_id">Customer</label>
                <select id="customer_id" name="customer_id" required>
                    <option value="">Select a customer</option>
                    <?php
                    if (mysqli_num_rows($customers) > 0) {
                        while ($customer = mysqli_fetch_assoc($customers)) {
                            echo "<option value='" . $customer['id'] . "'>" . $customer['name'] . "</option>";
                        }
                    }
                    ?>
                </select>

                <label for="num_people">Number of People</label>
                <input type="number" id="num_people" name="num_people" required>

                <label for="parking_slot">Parking Slot</label>
                <input type="number" id="parking_slot" name="parking_slot" required>

                <label for="reservation_date">Reservation Date</label>
                <input type="datetime-local" id="reservation_date" name="reservation_date" required>

                <button type="submit">Make Reservation</button>
            </form>
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

    <?php if ($reservation_success) : ?>
        <script>
            showAlert('Reservation created successfully!', 'staff_manage_reservations.php');
        </script>
    <?php elseif (isset($error_message)) : ?>
        <script>
            showAlert('<?php echo addslashes($error_message); ?>', 'staff_make_reservation.php');
        </script>
    <?php endif; ?>

</body>

</html>