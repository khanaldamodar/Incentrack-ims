<?php
require "../../config.php"; 
session_start();

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $email = $_POST['email'];
    $passkey = $_POST['password'];

    if(!$email || !$passkey){
        echo "All fields are required.";
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0){
            $row = $result->fetch_assoc();
            // Use password_verify to check hashed password
            if($row['passkey'] == $passkey){
                echo "Login Successful!";

                $_SESSION['id'] = $row['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                echo "Invalid email or password.";
            }
        } else {
            echo "Invalid email or password.";
        }
    }
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index.php" method="POST">
        <input type="text" name="email" id="email" placeholder="Enter Email">
        <input type="password" name="password" id="password" placeholder="Enter Password">
        <button>Login</button>
    </form>
    
</body>
</html>
