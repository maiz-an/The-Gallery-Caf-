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

// Query to select payment details along with order_id and reservation_id
$sql = "
    SELECT p.*, o.id AS order_id, r.id AS reservation_id
    FROM payments p
    LEFT JOIN orders o ON p.customer_id = o.customer_id AND o.status = 'completed'  -- Assuming completed orders for payment
    LEFT JOIN reservations r ON p.customer_id = r.customer_id AND r.status = 'confirmed'  -- Assuming confirmed reservations for payment
";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payments - Admin</title>
    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <script src="js/upbtn.js" defer></script>
    <style>
        table {
            width: 90%;
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
        function searchPayment() {
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
                    <nav>
                        <ul>
                            <li><a href="staff_dashboard.php" class="nav-btn">Home</a></li>
                            <li><a href="staff_view_user.php" class="nav-btn">Users</a></li>
                            <li><a href="staff_view_products.php" class="nav-btn">Menu</a></li>
                            <li><a href="staff_manage_orders.php" class="nav-btn">Orders</a></li>
                            <li><a href="staff_manage_reservations.php" class="nav-btn">Reservation</a></li>
                            <li><a href="view_employees.php" class="nav-btn">Employees</a></li>
                            <li><a href="staff_view_payments.php" class="nav-btn" style="color: #ffcc00;">Payments</a></li>
                            <li><a href="staff_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                            <li><a href="logout.php" class="nav-btn">Logout</a></li>
                        </ul>
                    </nav>
                </nav>
            </div>
        </div>
    </header>

    <h2>View Payments</h2>
    <input type="text" id="searchInput" onkeyup="searchPayment()" placeholder="Search for Payment..">
    <table>
        <thead>
            <tr>

                <th>Payment ID</th>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Total Amount</th>
                <th>Amount Paid</th>
                <th>Payment Option</th>
                <th>Payment Status</th>
                <th>Payment Date</th>
            </tr>
        </thead>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['customer_id'] . "</td>";
                echo "<td>" . $row['customer_name'] . "</td>";
                echo "<td>" . $row['total_amount'] . "</td>";
                echo "<td>" . $row['amount_paid'] . "</td>";
                echo "<td>" . $row['payment_option'] . "</td>";
                echo "<td>" . $row['payment_status'] . "</td>";
                echo "<td>" . $row['payment_date'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No payments found</td></tr>";
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
</body>
<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <a href="#top" class="scroll-btn">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>

</html>