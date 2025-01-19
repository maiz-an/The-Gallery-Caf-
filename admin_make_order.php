<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "product_website";

// Create a new database connection using procedural style
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check the database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to select product details
$product_sql = "SELECT id, name, description, price, stock, image FROM products";
$product_result = mysqli_query($conn, $product_sql);

// Query to select registered users
$user_sql = "SELECT id, username FROM users WHERE role='customer'";
$user_result = mysqli_query($conn, $user_sql);

// Handle creating an order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_order'])) {
    $customer_selection = $_POST['customer_selection'];
    $customer_name = $_POST['customer_name'] ?? '';
    $customer_id = $_POST['customer_id'] ?? null;
    $product_ids = $_POST['product_id'];
    $quantities = $_POST['quantity'];
    $total = 0;
    $order_items = [];

    foreach ($product_ids as $index => $product_id) {
        $quantity = (int)$quantities[$index];
        if ($quantity > 0) {
            $product_price_sql = "SELECT price FROM products WHERE id='$product_id'";
            $product_price_result = mysqli_query($conn, $product_price_sql);
            $product_price_row = mysqli_fetch_assoc($product_price_result);
            $total += $product_price_row['price'] * $quantity;
            $order_items[] = [
                'id' => $product_id,
                'quantity' => $quantity,
                'price' => $product_price_row['price']
            ];
        }
    }

    $product_ids_str = implode(',', array_column($order_items, 'id'));
    $quantities_str = implode(',', array_column($order_items, 'quantity'));

    if ($customer_selection === 'registered') {
        $sql = "INSERT INTO orders (customer_id, customer_name, product_ids, quantities, total, status) VALUES ('$customer_id', NULL, '$product_ids_str', '$quantities_str', '$total', 'pending')";
    } else {
        $sql = "INSERT INTO orders (customer_id, customer_name, product_ids, quantities, total, status) VALUES (0, '$customer_name', '$product_ids_str', '$quantities_str', '$total', 'pending')";
    }

    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['cart']);
        echo "<script>alert('Order created successfully!'); window.location.href = 'admin_make_order.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Order</title>

    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">

    <style>
        .form-container {
            width: 80%;
            margin: 20px auto;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group input[type="number"] {
            width: calc(100% - 20px);
            display: inline-block;
            margin-right: 10px;
        }

        .form-group button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .products-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 300px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        .product-card h3 {
            margin: 10px 0;
        }

        .product-card p {
            color: #777;
        }

        .product-card .price {
            font-size: 1.2em;
            color: #333;
        }

        .product-card input[type="checkbox"] {
            margin-top: 10px;
        }

        .product-card input[type="number"] {
            margin-top: 10px;
            display: none;
        }

        .product-card input[type="checkbox"]:checked+input[type="number"] {
            display: block;
        }
    </style>
    <header>
        <div class="header-container">
            <a href="admin_dashboard.php" class="logo">
                <img src="images/logo.png" alt="The Gallery Café Logo">
            </a>
            <h1>The Gallery Café - Admin</h1>
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="admin_modify_user.php" class="nav-btn">Users</a></li>
                        <li><a href="view_products.php" class="nav-btn">Menu</a></li>
                        <li><a href="admin_manage_orders.php" class="nav-btn">Orders</a></li>
                        <li><a href="admin_manage_reservations.php" class="nav-btn">Reservation</a></li>
                        <li><a href="admin_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                        <li><a href="logout.php" class="nav-btn">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
</head>

<body>
    <div class="form-container">
        <h2>Create Order</h2>
        <form method="post" action="admin_make_order.php">
            <div class="form-group">
                <label for="customer_selection">Select Customer:</label>
                <select id="customer_selection" name="customer_selection" onchange="toggleCustomerInput(this)">
                    <option value="registered">Registered User</option>
                    <option value="unregistered">Unregistered User</option>
                </select>
            </div>
            <div class="form-group" id="registered_user_div">
                <label for="customer_id">Registered User:</label>
                <select id="customer_id" name="customer_id">
                    <option value="">Select a customer</option>
                    <?php
                    if (mysqli_num_rows($user_result) > 0) {
                        while ($user = mysqli_fetch_assoc($user_result)) {
                            echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group" id="unregistered_user_div" style="display: none;">
                <label for="customer_name">Unregistered User Name:</label>
                <input type="text" id="customer_name" name="customer_name">
            </div>

            <div class="products-container">
                <?php
                if (mysqli_num_rows($product_result) > 0) {
                    while ($product = mysqli_fetch_assoc($product_result)) {
                        echo '<div class="product-card">';
                        echo '<img src="data:image/jpeg;base64,' . base64_encode($product['image']) . '" alt="Product Image"/>';
                        echo '<h3>' . $product['name'] . '</h3>';
                        echo '<p>' . $product['description'] . '</p>';
                        echo '<p class="price">$' . $product['price'] . '</p>';
                        echo '<input type="checkbox" name="product_id[]" value="' . $product['id'] . '">';
                        echo '<input type="number" name="quantity[]" min="1" placeholder="Quantity">';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No products available.</p>';
                }
                ?>
            </div>

            <div class="form-group">
                <button type="submit" name="create_order">Create Order</button>
            </div>
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
    <!-- Floating Cart Button -->
    <div class="floating-cart">
        <a href="admin_view_cart.php" class="cart-btn">
            <img src="images/cart-icon.png" alt="View Cart">
        </a>
    </div>
    <!-- Floating Contact Button -->
    <div class="floating-contact">
        <a href="contact_form.html" class="contact-btn">
            <img src="images/customer-service-icon.png" alt="Contact Us">
        </a>
    </div>

    <script>
        function toggleCustomerInput(select) {
            const registeredDiv = document.getElementById('registered_user_div');
            const unregisteredDiv = document.getElementById('unregistered_user_div');
            if (select.value === 'unregistered') {
                registeredDiv.style.display = 'none';
                unregisteredDiv.style.display = 'block';
            } else {
                registeredDiv.style.display = 'block';
                unregisteredDiv.style.display = 'none';
            }
        }
    </script>
</body>

</html>