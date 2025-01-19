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

// Fetch the product details
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "SELECT * FROM products WHERE id='$product_id'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Product not found.'); window.location.href = 'staff_view_products.php';</script>";
        exit();
    }
}

// Update product details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    // Handle image upload
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
        $image = addslashes(file_get_contents($_FILES['image']['tmp_name']));
        $sql = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock', image='$image' WHERE id='$product_id'";
    } else {
        $sql = "UPDATE products SET name='$name', description='$description', price='$price', stock='$stock' WHERE id='$product_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product updated successfully!'); window.location.href = 'staff_view_products.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
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
    <title>Edit Product</title>
    <link rel="stylesheet" href="style/admin_manage_users.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">



    <header>
        <h1>Admin Dashboard</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <nav>
                        <ul>
                            <li><a href="staff_dashboard.php" class="nav-btn">Home</a></li>
                            <li><a href="staff_view_user.php" class="nav-btn">Users</a></li>
                            <li><a href="staff_view_products.php" class="nav-btn" style="color: #ffcc00;">Menu</a></li>
                            <li><a href="staff_manage_orders.php" class="nav-btn">Orders</a></li>
                            <li><a href="staff_manage_reservations.php" class="nav-btn">Reservation</a></li>
                            <li><a href="view_employees.php" class="nav-btn">Employees</a></li>
                            <li><a href="staff_view_payments.php" class="nav-btn">Payments</a></li>
                            <li><a href="staff_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                            <li><a href="logout.php" class="nav-btn">Logout</a></li>
                        </ul>
                    </nav>
                </nav>
            </div>
        </div>
    </header>
</head>

<body>
    <section id="auth">
        <div id="login-form-container">

            <h2>Edit Product</h2>
            <form method="post" enctype="multipart/form-data">

                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $product['name']; ?>" required>

                <label for="description">Description</label>
                <input type="text" id="description" name="description" value="<?php echo $product['description']; ?>" required>

                <label for="price">Price</label>
                <input type="number" id="price" name="price" value="<?php echo $product['price']; ?>" required>

                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>

                <label for="image">Image</label>
                <input type="file" id="image" name="image">

                <button type="submit">Update Product</button>
                <a href="view_products.php" class="cart-link">Cancel</a>

            </form>
        </div>
    </section>
    <footer>
        <p>&copy; 2024 The Gallery Café. All rights reserved.</p>
    </footer>
</body>

</html>
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->
<!-- sooooooooooooooooooooooooooo much sleeeeeeeeeeeeeeepy   -->