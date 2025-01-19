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

// Fetch user details for editing
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

if ($user_id) {
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "<script>alert('No user ID provided'); window.location.href = 'admin_manage_users.php';</script>";
    exit();
}

// Handle form submission for editing user
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $phone_number = $_POST['phone_number'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET name=?, username=?, email=?, nic=?, phone_number=?, role=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $name, $username, $email, $nic, $phone_number, $role, $user_id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('User updated successfully!'); window.location.href = 'admin_modify_user.php';</script>";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - Admin</title>
    <link rel="stylesheet" href="style/admin_manage_users.css">
</head>

<body>
    <header>
        <h1>Edit User</h1>
    </header>
    <main>
        <?php if ($user) : ?>
            <form action="admin_edit_user.php?user_id=<?php echo $user_id; ?>" method="post">
                <input type="hidden" name="edit_user" value="1">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required><br>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br>

                <label for="phone_number">Phone Number:</label>
                <input type="number" id="phone_number" name="phone_number" min="1" minlength="9" <?php echo htmlspecialchars($user['phone_number']); ?>" required><br>
                <label for="role">Role:</label>
                <select id="role" name="role" required>
                    <option value="customer" <?php if ($user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="staff" <?php if ($user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                </select><br>
                <button type="submit">Update User</button>
            </form>
        <?php else : ?>
            <p>User not found.</p>
        <?php endif; ?>
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

</html>