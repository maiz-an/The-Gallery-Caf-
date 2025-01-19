<?php
// Start the session
session_start();

// Check if the user is logged in as staff
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));

    // Insert product into database
    $sql = "INSERT INTO products (name, description, price, stock, image) VALUES ('$name', '$description', '$price', '$stock', '$image')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product added successfully!'); window.location.href = 'staff_view_products.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff - Add Product</title>
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/add_menu.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .cart-view {
            display: block;
            margin: 10px;
            margin-left: 5px;
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
    </style>
    <header>
        <h1>The Gallery Café - Staff</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="staff_dashboard.php" class="nav-btn"> Menu</a></li>
                        <li><a href="staff_view_user.php" class="nav-btn">Users</a></li>
                        <li><a href="staff_view_products.php" class="nav-btn" style="color: #ffcc00;"> Menu</a></li>
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
</head>

<body>
    <div class="add-product-container">
        <a href="staff_view_products.php" class="cart-view">View Products</a>
        <h2>Add New Product</h2>
        <form action="staff_add_menu.php" method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" min="1" max="500" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="1" max="150">

            <label for="image">Product Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required>

            <button type="submit">Add Product</button>
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

</html>