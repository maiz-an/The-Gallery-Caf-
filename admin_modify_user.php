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

        $sql = "INSERT INTO users (name, username, email, password_hash, nic, phone_number, role) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $name, $username, $email, $password, $nic, $phone_number, $role);
        $stmt->execute();
        $stmt->close();
        echo "User added successfully!";
    } elseif (isset($_POST['delete_user'])) {
        // Delete user
        $user_id = $_POST['user_id'];
        $sql = "DELETE FROM users WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
        echo "User deleted successfully!";
    } elseif (isset($_POST['change_password'])) {
        // Change user password
        $user_id = $_POST['user_id'];
        $password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password_hash=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        if ($stmt->bind_param("si", $password, $user_id) && $stmt->execute()) {
            $stmt->close();
            echo "<script>alert('Password changed successfully!'); window.location.href = 'admin_modify_user.php';</script>";
        } else {
            echo "<script>alert('Error changing password'); window.location.href = 'admin_modify_user.php';</script>";
        }
    }
}

// Fetch all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Fetch user details for editing
$edit_user = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_user_form'])) {
    $user_id = $_POST['user_id'];
    $sql = "SELECT * FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $edit_user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

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
    <style>
        .cart-l {
            display: block;

            margin: 5px;
            margin-left: 10px;
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: 30px;
            height: 15px;
        }


        .cart-l {
            background-color: #333;
        }

        #searchInput {
            width: 80%;
            padding: 0.5em;
            margin: 0 auto 1em auto;
            display: block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
    </style>
    <script>
        function searchUser() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var table = document.querySelector("table tbody");
            var rows = table.getElementsByTagName('tr');

            for (var i = 0; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName('td');
                var match = false;
                for (var j = 0; j < cells.length; j++) {
                    if (cells[j].innerText.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
                rows[i].style.display = match ? "" : "none";
            }
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
        <a href="admin_manage_users.php" class="cart-link">Add</a>

        <h2>Modify User</h2>
        <input type="text" id="searchInput" onkeyup="searchUser()" placeholder="Search for users..">
        <?php if ($edit_user) : ?>
            <h3>Edit User</h3>
            <form action="admin_manage_users.php" method="post">
                <input type="hidden" name="user_id" value="<?php echo $edit_user['id']; ?>">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo $edit_user['name']; ?>" required>
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $edit_user['username']; ?>" required>
                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $edit_user['email']; ?>" required>

                <label for="phone_number">Phone Number:</label>
                <input type="text" name="phone_number" value="<?php echo $edit_user['phone_number']; ?>" required>
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="customer" <?php if ($edit_user['role'] == 'customer') echo 'selected'; ?>>Customer</option>
                    <option value="staff" <?php if ($edit_user['role'] == 'staff') echo 'selected'; ?>>Staff</option>
                    <option value="admin" <?php if ($edit_user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                </select>
                <button type="submit" name="edit_user">Update User</button>
            </form>
        <?php endif; ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>

                    <th>Phone Number</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) : ?>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['username']; ?></td>
                            <td><?php echo $row['email']; ?></td>

                            <td><?php echo $row['phone_number']; ?></td>
                            <td><?php echo $row['role']; ?></td>
                            <td>

                                <a href="admin_edit_user.php?user_id=<?php echo $row['id']; ?>" class="cart-l" style="display:inline-block;">Edit</a>


                                <form action="admin_manage_users.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="delete_user" value="1">
                                    <button type="submit">Delete</button>
                                </form> <br>
                                <form action="admin_manage_users.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="change_password" value="1">
                                    <input type="password" name="new_password" placeholder="New Password" required>
                                    <button type="submit">Change Password</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
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