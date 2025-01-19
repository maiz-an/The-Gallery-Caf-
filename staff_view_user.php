<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
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



// Get the sorting parameter from the URL
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$allowed_columns = ['id', 'name', 'username', 'email', 'phone_number', 'role'];

if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'id'; // default sorting column
}

// Fetch all users with dynamic sorting
$sql = "SELECT * FROM users ORDER BY $sort_column ASC";
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
    <style>
        .cart-l {
            display: block;
            margin: 10px;
            margin-left: 50px;
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            width: 38px;
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
        <h1>The Gallery Café - Staff</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="staff_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="staff_view_user.php" class="nav-btn" style="color: #ffcc00;">Users</a></li>
                        <li><a href="staff_view_products.php" class="nav-btn">Menu</a></li>
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
    <main>


        <h2>User Details </h2>

        <input type="text" id="searchInput" onkeyup="searchUser()" placeholder="Search for users..">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Role</th>
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