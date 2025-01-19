<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.html");
    exit();
}

// Database connection
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

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_user'])) {
        // Add user
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone_number = $_POST['phone_number'];
        $role = $_POST['role'];

        $sql = "INSERT INTO users (name, username, email, password_hash, phone_number, role) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $name, $username, $email, $password, $phone_number, $role);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('User added successfully!'); window.location.href = 'admin_modify_user.php';</script>";
    } elseif (isset($_POST['edit_user'])) {
        // Edit user
        $user_id = $_POST['user_id'];
        $name = $_POST['name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $role = $_POST['role'];

        $sql = "UPDATE users SET name=?, username=?, email=?, phone_number=?, role=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $username, $email, $phone_number, $role, $user_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('User updated successfully!'); window.location.href = 'admin_modify_user.php';</script>";
    } elseif (isset($_POST['delete_user'])) {
        // Delete user
        $user_id = $_POST['user_id'];
        $sql = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('User deleted successfully!'); window.location.href = 'admin_modify_user.php';</script>";
    } elseif (isset($_POST['change_password'])) {
        // Change user password
        $user_id = $_POST['user_id'];
        $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password, $user_id);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Password changed successfully!'); window.location.href = 'admin_modify_user.php';</script>";
    }
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="style/admin_manage_users.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <script src="js/upbtn.js" defer></script>
    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var email = document.getElementById('email').value;
            var password = document.getElementById('password').value;
            var phone_number = document.getElementById('phone_number').value;

            if (username == "") {
                alert("Username must be filled out");
                return false;
            }
            if (email == "") {
                alert("Email must be filled out");
                return false;
            }
            if (!validateEmail(email)) {
                alert("Invalid email format");
                return false;
            }
            if (password == "") {
                alert("Password must be filled out");
                return false;
            }
            if (password.length < 6) {
                alert("Password must be at least 6 characters long");
                return false;
            }
            if (!/[A-Z]/.test(password)) {
                alert("Password must contain at least one uppercase letter");
                return false;
            }
            if (!/[a-z]/.test(password)) {
                alert("Password must contain at least one lowercase letter");
                return false;
            }
            if (!/[0-9]/.test(password)) {
                alert("Password must contain at least one number");
                return false;
            }
            if (!/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                alert("Password must contain at least one special character");
                return false;
            }
            if (phone_number.length < 9) {
                alert("Enter valid phone number");
                return false;
            }

            return true;
        }

        function validateEmail(email) {
            var re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return re.test(email);
        }
    </script>
</head>

<body>
    <header>
        <h1>The Gallery Café - Admin</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="admin_modify_user.php" class="nav-btn" style="color: #ffcc00;">Users</a></li>
                        <li><a href="view_products.php" class="nav-btn">Menu</a></li>
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
    <main>
        <a href="admin_modify_user.php" class="cart-link">Modify</a>
        <h2>Add User</h2>
        <form action="admin_manage_users.php" method="post" onsubmit="return validateForm()">
            <input type="hidden" name="add_user" value="1">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required><br>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required><br>
            <label for="role">Role:</label>
            <select id="role" name="role" required>
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
            </select><br>
            <button type="submit">Add User</button>
        </form>
    </main>
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