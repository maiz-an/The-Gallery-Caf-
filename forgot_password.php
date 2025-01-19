<?php
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
    $usernameOrEmail = $_POST['usernameOrEmail'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('Passwords do not match.'); window.location.href = 'forgot_password.php';</script>";
        exit();
    }

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $sql = "UPDATE users SET password_hash='$hashedPassword' WHERE username='$usernameOrEmail' OR email='$usernameOrEmail'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Password reset successful!'); window.location.href = 'login.html';</script>";
    } else {
        echo "<script>alert('Error updating password: " . mysqli_error($conn) . "'); window.location.href = 'forgot_password.php';</script>";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">



    <script>
        function validateForm() {
            var usernameOrEmail = document.getElementById('usernameOrEmail').value;
            var newPassword = document.getElementById('newPassword').value;
            var confirmPassword = document.getElementById('confirmPassword').value;

            if (usernameOrEmail.trim() === "") {
                alert("Username or Email must be filled out");
                return false;
            }

            if (newPassword.trim() === "") {
                alert("New Password must be filled out");
                return false;
            }

            if (newPassword.length < 6) {
                alert("Password must be at least 6 characters long");
                return false;
            }

            if (newPassword !== confirmPassword) {
                alert("Passwords do not match");
                return false;
            }

            return true;
        }
    </script>
</head>

<body>
    
    <main>
        <section id="auth">
            <div class="auth-toggle">

            </div>
            <div id="forgot-password-form-container">
                <h2>Forgot Password</h2>
                <form action="forgot_password.php" method="post" onsubmit="return validateForm()">
                    <label for="usernameOrEmail">Username or Email:</label>
                    <input type="text" id="usernameOrEmail" name="usernameOrEmail"><br>
                    <label for="newPassword">New Password:</label>
                    <input type="password" id="newPassword" name="newPassword"><br>
                    <label for="confirmPassword">Confirm New Password:</label>
                    <input type="password" id="confirmPassword" name="confirmPassword"><br> <br>
                    <button type="submit" class="btn-sub">Reset Password</button>
                </form>
                <p>Remembered your password? <a href="login.html" class="btn-lg">Login here</a></p>
            </div>
        </section>
    </main>


</html>