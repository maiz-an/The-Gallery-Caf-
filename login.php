<?php
// Start the session
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $usernameOrEmail = $_POST['usernameOrEmail'];
    $password = $_POST['password'];


    // Prepare SQL query based on input type (email or username)
    if (filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email = ?";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
    }

    // Prepare statement
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters
    mysqli_stmt_bind_param($stmt, 's', $usernameOrEmail);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Get result
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] === 'customer') {
                header("Location: customer_dashboard.php");
            } else {
                header("Location:staff_dashboard.php");
            }
            exit(); // Always exit after redirect
        } else {
            // Invalid password
            echo "<script>alert('Invalid password.'); window.location.href = 'login.html';</script>";
        }
    } else {
        // No user found
        echo "<script>alert('No user found with that username or email.'); window.location.href = 'login.html';</script>";
    }

    // Close statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
