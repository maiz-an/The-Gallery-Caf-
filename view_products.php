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

// Query to select products
$sql = "SELECT id, name, description, price, stock, image FROM products ORDER BY id ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/view_pro_admin.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/upbtn.js" defer></script>
    <style>
        .cart-view {
            display: block;
            margin: 10px;
            margin-left: 118px;
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            text-align: center;
            width: 105px;
        }


        .cart-view:hover {
            background-color: #0a9660;
        }

        .delete-btn {
            background-color: #333;
            padding: 10px 10px;
            border-radius: 8px;
            color: white;
        }

        .delete-btn:hover,
        .edit-btn:hover {
            opacity: 0.8;
        }
    </style>
    <script>
        function searchProducts() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.getElementById('productsTable');
            var tr = table.getElementsByTagName('tr');

            for (var i = 1; i < tr.length; i++) {
                tr[i].style.display = 'none';
                var td = tr[i].getElementsByTagName('td');
                for (var j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].innerHTML.toLowerCase().indexOf(filter) > -1) {
                            tr[i].style.display = '';
                            break;
                        }
                    }
                }
            }
        }
    </script>
    <header>
        <h1>The Gallery Café - Admin</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="admin_modify_user.php" class="nav-btn">Users</a></li>
                        <li><a href="view_products.php" class="nav-btn" style="color: #ffcc00;">Menu</a></li>
                        <li><a href="admin_manage_orders.php" class="nav-btn">Orders</a></li>
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
</head>


<body>
    <div></div>
    <section>
        <div class="view-products-container">
            <a href="add_menu.php" class="cart-view">Add Products</a>
            <h2>Available Products</h2>

            <input type="text" id="searchInput" onkeyup="searchProducts()" placeholder="Search for products..">
            <table id="productsTable">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                <?php
                // Check if there are any products in the result
                if (mysqli_num_rows($result) > 0) {
                    // Fetch and display each product
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['stock'] . "</td>";
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '" alt="Product Image"/></td>';
                        echo '<td>';
                        echo '<a href="edit_product.php?id=' . $row['id'] . '"class="delete-btn">Edit</a> <br><br><br>  ';
                        echo '<a href="delete_product.php?id=' . $row['id'] . '"class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this product?\')">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No products found</td></tr>";
                }
                // Close the database connection
                mysqli_close($conn);
                ?>
            </table>

        </div>
    </section>
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


</div>
<!-- Scroll to Top Button -->
<div class="scroll-to-top" id="scrollToTop">
    <a href="#top" class="scroll-btn">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>

</html>