<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
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

// Handle form submission to update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $sql = "UPDATE orders SET status='$status' WHERE id='$order_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Order status updated successfully!'); window.location.href = 'admin_manage_orders.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Query to select orders
$sql = "SELECT orders.id, orders.customer_id, orders.product_ids, orders.quantities, orders.total, orders.order_date, orders.status, users.name AS customer_name FROM orders JOIN users ON orders.customer_id = users.id";
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/view_pro_admin.css">
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
        function searchOrders() {
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
<header>
    <h1>The Gallery Café - Admin</h1>
    <div class="header-container">
        <div class="title-nav">
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                    <li><a href="admin_modify_user.php" class="nav-btn">Users</a></li>
                    <li><a href="view_products.php" class="nav-btn">Menu</a></li>
                    <li><a href="admin_manage_orders.php" class="nav-btn" style="color: #ffcc00;">Orders</a></li>
                    <li><a href="admin_manage_reservations.php" class="nav-btn">Reservation</a></li>
                    <li><a href="admin_manage_employee.php" class="nav-btn">Employees</a></li>
                    <li><a href="admin_view_payments.php" class="nav-btn">Payments</a></li>
                    <li><a href="admin_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                    <li><a href="logout.php" class="nav-btn">Logout</a></li>
                </ul>
            </nav>
        </div>
    </div>

</header>


<body>
    <div class="view-orders-container">
        <h2>Manage Orders</h2>
        <input type="text" id="searchInput" onkeyup="searchOrders()" placeholder="Search for orders..">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Products</th>
                    <th>Quantities</th>
                    <th>Total</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $product_names = getProductNames($conn, $row['product_ids']);
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "</td>";
                    echo "<td>" . $row['product_ids'] . " (" . $product_names . ")</td>";
                    echo "<td>" . $row['quantities'] . "</td>";
                    echo "<td>$" . $row['total'] . "</td>";
                    echo "<td>" . $row['order_date'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo '<td>';
                    echo '<form method="post" action="admin_manage_orders.php">';
                    echo '<input type="hidden" name="order_id" value="' . $row['id'] . '">';
                    echo '<select name="status">';
                    echo '<option value="pending"' . ($row['status'] == 'pending' ? ' selected' : '') . '>Pending</option>';
                    echo '<option value="preparing"' . ($row['status'] == 'preparing' ? ' selected' : '') . '>Preparing</option>';
                    echo '<option value="complete"' . ($row['status'] == 'complete' ? ' selected' : '') . '>Complete</option>';
                    echo '<option value="declined"' . ($row['status'] == 'declined' ? ' selected' : '') . '>Declined</option>';
                    echo '</select>';
                    echo '<button type="submit" name="update_status">Update</button>';
                    echo '</form>';
                    echo '</td>';
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No orders found</td></tr>";
            }
            // Close the database connection
            mysqli_close($conn);
            ?>

        </table>
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
<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <a href="#top" class="scroll-btn">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>

</html>