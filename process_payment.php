<?php
// Start the session
session_start();

// Check if the user is logged in as a customer
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    // Redirect to login page if not a customer
    header("Location: login.html");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    // Get form data
    $customer_id = $_SESSION['user_id'];
    $customer_name = $_SESSION['name'];
    $total = $_POST['total'];
    $payment_option = $_POST['payment_option'];
    $amount_paid = $payment_option === 'full' ? $total : $total / 2;

    // Get payment details
    $card_name = $_POST['card_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Process the payment 
    $payment_success = true; // Simulate a successful payment

    // Insert payment details into the database
    if ($payment_success) {
        // Ensure customer name is retrieved correctly
        $customer_name = $_SESSION['name'];

        $sql = "INSERT INTO payments (customer_id, customer_name, total_amount, amount_paid, payment_option, payment_status, card_name, card_number, expiry_date, cvv) 
            VALUES ('$customer_id', '$customer_name', '$total', '$amount_paid', '$payment_option', 'success', '$card_name', '$card_number', '$expiry_date', '$cvv')";

        if (mysqli_query($conn, $sql)) {
            // Confirm the order
            $product_ids = implode(',', array_keys($_SESSION['cart']));
            $quantities = implode(',', $_SESSION['cart']);
            $order_sql = "INSERT INTO orders (customer_id, product_ids, quantities, total, status) 
                      VALUES ('$customer_id', '$product_ids', '$quantities', '$total', 'pending')";

            if (mysqli_query($conn, $order_sql)) {
                unset($_SESSION['cart']);
                echo "<script>alert('Payment successful!'); window.location.href = 'customer_view_orders.php';</script>";
            } else {
                echo "<script>alert('Order confirmation failed: " . mysqli_error($conn) . "'); window.location.href = 'view_cart.php';</script>";
            }
        } else {
            echo "<script>alert('Payment record insertion failed: " . mysqli_error($conn) . "'); window.location.href = 'view_cart.php';</script>";
        }
    } else {
        echo "<script>alert('Payment failed!'); window.location.href = 'view_cart.php';</script>";
    }


    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect to cart page if the form is not submitted
    header("Location: view_cart.php");
    exit();
}
