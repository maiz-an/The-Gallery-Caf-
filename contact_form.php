<?php
// Database connection details
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

// Handle form submission via AJAX
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajax'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact_submissions (name, email, message) VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    $conn->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - The Gallery Café</title>
    <link rel="stylesheet" href="style/contact_form_style.css">
    <link rel="stylesheet" href="style/loader.css">
    <script src="js/loader.js" defer></script>
    <script src="js/back_button.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelector("form").addEventListener("submit", function(e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way
                const formData = new FormData(this);
                formData.append('ajax', true);

                fetch('contact_form.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Your message has been sent successfully!');
                            window.location.href = window.location.pathname; // Reload the page to clear the form
                        } else {
                            alert('There was an error: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            });
        });

        function goBack() {
            window.history.back();
        }
    </script>
</head>

<body>
    <div id="loader">
        <img src="images/logo.png" alt="The Gallery Café Logo">
        <p>Loading, please wait...</p>
    </div>
    <main>
        <button onclick="goBack()" class="btn nav-btn back-btn">Back</button>

        <section id="contact-info">
            <h2>Contact Us</h2>

            <section class="center-section">
                <a href="tel:+94753357777" class="pbutton">
                    <span class="shine"></span>
                </a>

                <a href="mailto:mohamedmaizanmunas@outlook.com" class="ebutton contact-link"></a>

                <a href="https://www.instagram.com/mr.de11_?igsh=M2NrOWcwcjNicGp0&utm_source=qr" target="_blank" class="ibutton contact-link"></a>

                <a href="https://www.facebook.com/profile.php?id=61553304986903&mibextid=LQQJ4d" target="_blank" class="fbutton contact-link"></a>
            </section>
        </section>

        <section>
            <form method="post">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <input type="submit" value="Send Message" class="btn">
            </form>
        </section>
    </main>
</body>

</html>