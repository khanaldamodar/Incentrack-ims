<?php
require '../../config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Add error reporting
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // // Get the stored hashed password from database
    $sql = "SELECT * FROM clients WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
   if(mysqli_num_rows($result) > 0){
   while($row = mysqli_fetch_assoc($result)){
    if($row['email'] == $email && $row['passkey'] == $password) {
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        // Set session variables
        $_SESSION['email'] = $row['email'];
        $_SESSION['first_name'] = $row['first_name'];
        $_SESSION['last_name'] = $row['last_name'];
        
        // Redirect and exit
        header("Location: home.php");
        exit();
    } else {
        echo "<p style='color:red;'>Invalid email or password!</p>";
    }
   }
   } else {
    echo "<p style='color:red;'>User not found!</p>";
}

}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>InvenTrack - Login</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/logo1.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
      />
      <link rel="stylesheet" href="../../assets/css/styles.css" />
      <link rel="stylesheet" href="../../assets/css/login.css" />
  </head>
  <body>
    <nav>
        <a href="../index.php">
            <h1 style="color: black;">Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
          </a>
      <ul>
        <li><a style="text-decoration: none; color: black;" href="../index.php">Home</a></li>
        <li><a style="text-decoration: none; color: black;" href="services.php">Services</a></li>
        <li><a style="text-decoration: none; color: black;" href="about.php">About Us</a></li>
      </ul>
      
    </nav>

    <div class="login-container">
      <div class="login-form">
        <h2>Login to Inven<span style="color: rgb(247, 170, 70)">Track</span></h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
          <input type="text" name="email" placeholder="Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Don't have an account? <a href="register.php" style="color: rgb(247, 170, 70);">Register</a></p>
      </div>
    </div>
  </body>
</html>
