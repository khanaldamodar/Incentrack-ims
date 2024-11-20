<?php
// Include the database connection file
require '../config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../assets/css/register.css">
    <link rel="icon" type="image/x-icon" href="logo1.png">

</head>

<body>
    <nav>
        <h1>Inven<span style="color: rgb(247, 170, 70);">Track</span></h1>
        <ul>
            <li><a href="../index.php">Home</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="about.php">About Us</a></li>
        </ul>
    </nav>

    <div class="register-container">
        <!-- <a href="../index.php">
            <h1 style="color: black;">Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
          </a> -->
        <form class="register-form" method="POST" action="register.php" id="register-form">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="company-name">Company Name:</label>
                <input type="text" id="company-name" name="company-name" required>
            </div>
            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" required>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" required></textarea>
            </div>
            <div class="form-group">
                <label for="postal">Postal Code:</label>
                <input type="number" id="postal" name="postal" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="number" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>

            <button type="submit" class="register-btn">Register</button>
        </form>

        <?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve input values
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $state = $_POST['state'];
    $postal = $_POST['postal'];
    $email = $_POST['email'];
    $company_name = $_POST['company-name'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    // Validate phone number length
    if (strlen($phone) != 10 || !ctype_digit($phone)) {
        echo "<p style='color:red;'>Phone number must be exactly 10 digits!</p>";
    } else if (strlen($password) < 8) {
        echo "<p style='color:red;'>Password must be at least 8 characters long!</p>";
    } else if ($password !== $confirm_password) {
        echo "<p style='color:red;'>Passwords do not match!</p>";
    } else {
        // Check if username already exists
        $sql1 = "SELECT username FROM users WHERE username = '$username'";
        $result = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($result) > 0) {
            echo "<p style='color:red;'>Username is already used!</p>";
        } else {
            // Hash the password for security
            $password_hash = password_hash($password, PASSWORD_BCRYPT);

            // Insert data into the database
            $sql = "INSERT INTO users (username, first_name, last_name, address, state, postal_code, phone_number, company_name, email, password_hash) 
                    VALUES ('$username', '$firstname', '$lastname', '$address', '$state', '$postal', '$phone', '$company_name', '$email', '$password_hash')";

            if (mysqli_query($conn, $sql)) {
                echo "<p style='color:green;'>Register successfully!</p>";
                header("Location: login.php");
                exit();
            } else {
                echo "<p style='color:red;'>Failed to register: " . mysqli_error($conn) . "</p>";
            }
        }
    }
}
?>

        
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <div class="footer">
        <h1>Inven<span style="color: rgb(247, 170, 70);">Track</span></h1>
        <p>InvenTrack is a simple, real-time Inventory Management System for tracking and managing stock efficiently. It offers secure login and easy navigation for businesses of all sizes.</p>
        <div class="social-icons">
            <a href="#"> <i class="fa-brands fa-facebook"></i> &nbsp;Facebook</a>
            <a href="#"> <i class="fa-brands fa-instagram"></i> &nbsp;Instagram</a>
            <a href="#"> <i class="fa-brands fa-twitter"></i> &nbsp;Twitter</a>
        </div>
        <p>&copy; 2024 InvenTrack. All rights reserved.</p>
        <p>Made with ❤️ by <span style="color: rgb(247, 170, 70);">InvenTrack</span></p>
    </div>

    <!-- <script>
        const form = document.getElementById('register-form');
        function formHandle(e){
            // e.preventDefault()
        }
        form.addEventListener('submit', formHandle)
    </script> -->
</body>

</html>