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

// Get employee ID from query string
$employee_id = $_GET['id'];

// Fetch employee details
$sql = "SELECT * FROM employees WHERE id='$employee_id'";
$result = mysqli_query($conn, $sql);
$employee = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $post = $_POST['post'];
    $email = $_POST['email'];
    $nic_passport = $_POST['nic_passport'];
    $address = $_POST['address'];

    if ($_FILES['photo']['tmp_name']) {
        $photo = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
        $sql = "UPDATE employees SET name='$name', age='$age', post='$post', email='$email', nic_passport='$nic_passport', address='$address', photo='$photo' WHERE id='$employee_id'";
    } else {
        $sql = "UPDATE employees SET name='$name', age='$age', post='$post', email='$email', nic_passport='$nic_passport', address='$address' WHERE id='$employee_id'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Employee updated successfully!'); window.location.href = 'admin_manage_employee.php';</script>";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>

    <link rel="stylesheet" href="style/admin_manage_users.css">
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
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
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

        img {
            max-width: 300px;
            height: auto;
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

    <div class="form-container">
        <h2>Edit Employee</h2>
        <form method="post" action="edit_employee.php?id=<?php echo $employee_id; ?>" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $employee['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" value="<?php echo $employee['age']; ?>" required>
            </div>
            <div class="form-group">
                <label for="post">Post:</label>
                <input type="text" id="post" name="post" value="<?php echo $employee['post']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $employee['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nic_passport">NIC/Passport:</label>
                <input type="text" id="nic_passport" name="nic_passport" value="<?php echo $employee['nic_passport']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" rows="3" required><?php echo $employee['address']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*">
                <p>Current Photo:</p>
                <div class="img">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($employee['photo']); ?>" alt="Employee Photo">
                </div>
            </div>
            <div class="form-group">
                <button type="submit">Update Employee</button>
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

</body>

</html>