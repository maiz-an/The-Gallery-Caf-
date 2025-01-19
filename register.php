<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = trim($_POST['name']);
    $user = trim($_POST['username']);
    $email = trim($_POST['email']);
    $pass = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $phone_number = trim($_POST['phone_number']);
    $role = "customer";

    // Optional: Remove this line if "nic" is not used in your form
    $nic = isset($_POST['nic']) ? trim($_POST['nic']) : null;

    // Check if username already exists
    $checkUsernameSql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkUsernameSql->bind_param("s", $user);
    $checkUsernameSql->execute();
    $usernameResult = $checkUsernameSql->get_result();

    if ($usernameResult->num_rows > 0) {
        // If username exists, set error message in session and redirect to registration page
        $_SESSION['error'] = "This username is already taken.";
        header("Location: registration.php");
        exit();
    }

    // Check if email already exists
    $checkEmailSql = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $checkEmailSql->bind_param("s", $email);
    $checkEmailSql->execute();
    $emailResult = $checkEmailSql->get_result();

    if ($emailResult->num_rows > 0) {
        // If email exists, set error message in session and redirect to registration page
        $_SESSION['error'] = "This email is already registered.";
        header("Location: registration.php");
        exit();
    }

    // If username and email do not exist, insert new user into the database using a prepared statement
    $sql = $conn->prepare("INSERT INTO users (name, username, email, password_hash, nic, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql->bind_param("sssssss", $name, $user, $email, $pass, $nic, $phone_number, $role);

    if ($sql->execute()) {
        // If insertion is successful, set success message in session and redirect to login page
        $_SESSION['success'] = "Registration successful!";
        header("Location: login.html");
        exit();
    } else {
        // If insertion fails, set error message in session and redirect to registration page
        $_SESSION['error'] = "Error: " . $sql->error;
        header("Location: registration.php");
        exit();
    }

    // Close prepared statements
    $checkUsernameSql->close();
    $checkEmailSql->close();
    $sql->close();
}

// Close the database connection
$conn->close();
