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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $sql = "DELETE FROM employees WHERE id='$delete_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Employee deleted successfully!'); window.location.href = 'admin_manage_employee.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error deleting record: " . mysqli_error($conn) . "'); window.location.href = 'admin_manage_employee.php';</script>";
        exit();
    }
}

// Query to select employee details
$sql = "SELECT * FROM employees";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
    <link rel="stylesheet" href="style/view_pro.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <script src="js/upbtn.js" defer></script>

    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .action-buttons a,
        .action-buttons form button {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }

        .edit-btn {
            background-color: #333;
        }

        .delete-btn {
            background-color: #333;
        }

        .delete-btn:hover,
        .edit-btn:hover {
            opacity: 0.8;
        }

        .cart-view {
            display: block;
            margin: 10px;
            margin-left: 148px;
            padding: 10px 20px;
            background-color: #444;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            text-align: center;
            width: 105px;
        }

        .cart-view:hover {
            background-color: #0a9660;
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
        function searchEmployee() {
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
    <header>
        <h1>The Gallery Café - Admin</h1>
        <div class="header-container">
            <div class="title-nav">
                <nav>
                    <ul>
                        <li><a href="admin_dashboard.php" class="nav-btn">Home</a></li>
                        <li><a href="admin_modify_user.php" class="nav-btn">Users</a></li>
                        <li><a href="view_products.php" class="nav-btn">Menu</a></li>
                        <li><a href="admin_manage_orders.php" class="nav-btn">Orders</a></li>
                        <li><a href="admin_manage_reservations.php" class="nav-btn">Reservation</a></li>
                        <li><a href="admin_manage_employee.php" class="nav-btn" style="color: #ffcc00;">Employees</a></li>
                        <li><a href="admin_view_payments.php" class="nav-btn">Payments</a></li>
                        <li><a href="admin_view_contacts.php" class="nav-btn">Feedbacks</a></li>
                        <li><a href="logout.php" class="nav-btn">Logout</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </header>
</head>

<body>
    <h2>View Employees</h2>
    <input type="text" id="searchInput" onkeyup="searchEmployee()" placeholder="Search for Employee..">
    <section>
        <div>
            <a href="add_employee.php" class="cart-view">Add Employee</a>
            <table>
                <thead>
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Post</th>
                        <th>Email</th>
                        <th>NIC/Passport</th>
                        <th>Address</th>
                        <th>Photo</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['age'] . "</td>";
                        echo "<td>" . $row['post'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['nic_passport'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo '<td><img src="data:image/jpeg;base64,' . base64_encode($row['photo']) . '" alt="Employee Photo"/></td>';
                        echo "<td class='action-buttons'>
                            <a href='edit_employee.php?id=" . $row['id'] . "' class='edit-btn'>Edit</a>
                            <form method='post' action='admin_manage_employee.php' style='display:inline-block;'>
                                <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                                <button type='submit' class='delete-btn'>Delete</button>
                            </form>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No employees found</td></tr>";
                }
                // Close the database connection
                mysqli_close($conn);
                ?>
            </table>
        </div>
    </section>

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