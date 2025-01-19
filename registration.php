<?php
session_start();

if (isset($_SESSION['error'])) {
    echo "<p style='color:red;'>" . $_SESSION['error'] . "</p>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<p style='color:green;'>" . $_SESSION['success'] . "</p>";
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register or Login - The Gallery Café</title>
    <link rel="stylesheet" href="style/auth_style.css">
    <link rel="stylesheet" href="style/floating_contact_button.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style/h1.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/audio.js" defer></script>
    <script src="js/upbtn.js" defer></script>
    <style>
        .btn-sub {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 10px;
        }

        .logo img {
            max-width: 155px;
            height: 120px;

        }

        .btn-lg {

            width: 100%;
            padding: 7px 10px;
            border-radius: 5px;
            margin-left: 1px;
            border: none;
            background-color: #333;
            color: rgb(247, 230, 230);
            text-decoration: none;
            margin: 5px;
            cursor: pointer;
        }

        .error {
            border-color: red;
        }

        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }
    </style>

    <script>
        function validateForm() {
            var valid = true; // Flag to track if form is valid
            var username = document.getElementById('username');
            var email = document.getElementById('email');
            var password = document.getElementById('password');
            var phoneNumber = document.getElementById('phone_number');

            // Clear previous errors
            clearErrors();

            // Username validation
            if (username.value == "") {
                showError(username, "Username must be filled out");
                valid = false;
            }

            // Email validation
            if (email.value == "") {
                showError(email, "Email must be filled out");
                valid = false;
            } else if (!validateEmail(email.value)) {
                showError(email, "Invalid email format");
                valid = false;
            }

            // Password validation
            if (password.value == "") {
                showError(password, "Password must be filled out");
                valid = false;
            } else if (password.value.length < 6) {
                showError(password, "Password must be at least 6 characters long");
                valid = false;
            } else if (!/[A-Z]/.test(password.value)) {
                showError(password, "Password must contain at least one uppercase letter");
                valid = false;
            } else if (!/[a-z]/.test(password.value)) {
                showError(password, "Password must contain at least one lowercase letter");
                valid = false;
            } else if (!/[0-9]/.test(password.value)) {
                showError(password, "Password must contain at least one number");
                valid = false;
            } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(password.value)) {
                showError(password, "Password must contain at least one special character");
                valid = false;
            }

            // Phone number validation
            if (phoneNumber.value == "") {
                showError(phoneNumber, "Phone number must be filled out");
                valid = false;
            } else if (phoneNumber.value.length < 9) {
                showError(phoneNumber, "Enter a valid phone number");
                valid = false;
            }

            return valid;
        }

        function validateEmail(email) {
            var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function showError(input, message) {
            var error = document.createElement('span');
            error.className = 'error-message';
            error.innerText = message;
            input.parentNode.insertBefore(error, input.nextSibling);
            input.classList.add('error');
        }

        function clearErrors() {
            var errors = document.querySelectorAll('.error-message');
            errors.forEach(function(error) {
                error.remove();
            });

            var inputs = document.querySelectorAll('.error');
            inputs.forEach(function(input) {
                input.classList.remove('error');
            });
        }
    </script>

</head>

<body>
    <header>
        <div class="header-container">
            <a href="index.html" class="logo">
                <img src="images/logo.png" alt="The Gallery Café Logo">
            </a>
            <div class="title-nav">
                <h1>The Gallery Café</h1>
                <nav>
                    <ul>
                        <li><a href="index.html" class="nav-btn">Home</a></li>
                        <li><a href="menu.html" class="nav-btn">Menu</a></li>
                        <li><a href="events.html" class="nav-btn">Events & Promotions</a></li>
                        <li><a href="about.html" class="nav-btn">About Us</a></li>
                        <li><a href="login.html" class="nav-btn" style="color: #ffcc00;">Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
    <main>
        <section id="auth">
            <div class="auth-toggle">

            </div>
            <div id="login-form-container">
                <h2>Create an Account</h2>
                <form action="register.php" method="post" onsubmit="return validateForm()">
                    <input type="hidden" name="add_user" value="1">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required> <br>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required><br>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required> <br>

                    <label for="phone_number">Phone Number:</label>
                    <input type="text" id="phone_number" name="phone_number" required><br><br>
                    <!--<label for="role">Role:</label>
                    <select id="role" name="role">
                        <option value="">Select a role</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select> -->
                    <button type="submit" class="btn-sub">Register</button>
                </form>
                <p>Already registered? <a href="login.html" class="btn-lg">Login here</a></p>
            </div>
        </section>
    </main>

    <!-- Floating Contact Button -->
    <div class="floating-contact">
        <a href="contact_form.php" class="contact-btn">
            <img src="images/customer-service-icon.png" alt="Contact Us">
        </a>
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

<!-- Floating Audio Control -->
<div id="audio-control">
    <button id="musicPlaying">&#9654;</button> <!-- Play Icon -->
    <audio id="background-music" loop>
        <source src="images/mp3.mp3" type="audio/mpeg">
        browser not support the audio
    </audio>
</div>

</html>