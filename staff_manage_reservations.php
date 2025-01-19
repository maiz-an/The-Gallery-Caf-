<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: login.html");
    exit();
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

// Update reservation status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $reservation_id = $_POST['reservation_id'];
    $status = $_POST['status'];

    $sql = "UPDATE reservations SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $reservation_id);

    if ($stmt->execute()) {
        // Fetch customer email
        $customer_sql = "SELECT c.email FROM reservations r JOIN users c ON r.customer_id = c.id WHERE r.id = ?";
        $customer_stmt = $conn->prepare($customer_sql);
        $customer_stmt->bind_param("i", $reservation_id);
        $customer_stmt->execute();
        $customer_result = $customer_stmt->get_result();
        $customer = $customer_result->fetch_assoc();
        $customer_email = $customer['email'];

        // Send email notification
        $subject = "Reservation Status Update";
        $message = "Your reservation status has been updated to: " . $status;
        $headers = "From: no-reply@thegallerycafe.com";

        mail($customer_email, $subject, $message, $headers);

        echo "<script>alert('Reservation status updated successfully!'); window.location.href = 'staff_manage_reservations.php';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }



    $stmt->close();
}



//reservations and sort by date
$sql = "SELECT r.id, r.customer_id, r.num_people, r.parking_slot, r.reservation_date, r.status, c.username
        FROM reservations r
        JOIN users c ON r.customer_id = c.id
        ORDER BY r.reservation_date ASC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Manage Reservations</title>
    <link rel="stylesheet" href="style/admin_reservation_styles.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <script src="js/upbtn.js" defer></script>
    <style>
        #searchInput {
            width: 80%;
            padding: 0.5em;
            margin: 0 auto 1em auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
    <script>
        function searchReservations() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.querySelector("table tbody");
            var rows = table.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
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
        <div class="table-container">
            <a href="staff_make_reservation.php" class="cart-link">Add</a>
            <h2>Manage Reservations</h2>
            <input type="text" id="searchInput" onkeyup="searchReservations()" placeholder="Search for Reservations..">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Number of People</th>
                        <th>Parking Slot</th>
                        <th>Reservation Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0) : ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['username']; ?></td>
                                <td><?php echo $row['num_people']; ?></td>
                                <td><?php echo $row['parking_slot']; ?></td>
                                <td><?php echo $row['reservation_date']; ?></td>
                                <td><?php echo $row['status']; ?></td>
                                <td>
                                    <form action="staff_manage_reservations.php" method="post">
                                        <input type="hidden" name="reservation_id" value="<?php echo $row['id']; ?>">
                                        <select name="status">
                                            <option value="pending" <?php if ($row['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                            <option value="confirmed" <?php if ($row['status'] == 'confirmed') echo 'selected'; ?>>Confirmed</option>
                                            <option value="declined" <?php if ($row['status'] == 'declined') echo 'selected'; ?>>Declined</option>
                                        </select>
                                        <button type="submit" name="update_status">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="7">No reservations found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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
<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <a href="#top" class="scroll-btn">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>

</html>