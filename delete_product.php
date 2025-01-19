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

// Delete the product
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id='$product_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Product deleted successfully!'); window.location.href = 'view_products.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    echo "<script>alert('Invalid product ID.'); window.location.href = 'view_products.php';</script>";
}

// Close the connection
mysqli_close($conn);
?>
