<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notice</title>
    <style>
        *{
            font-family: poppins;
        }
        body{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;

        }
        .box{
            width:50%;
            height: 400px;
            /* border: 1px solid black; */
            text-align: center;
            padding: 20px;
        }
        a{
            text-decoration: none;

        }
        button{
            width: 150px;
            height: 40px;
            cursor: pointer;
            background-color: blue;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 5px;
        }
        button:hover{
            background-color: #010044;
        }

    </style>
</head>
<body>

<div class="box">
    <h1>User deleted Successfully!!</h1>
    <p>To get back into admin dashobard click on exit button.</p>
    <a href="dashboard.php"><button>Exit</button></a>
</div>
    
</body>
</html>
<?php
require "../../config.php";


//To redirect admin to user information Edit page
if($_SERVER['REQUEST_METHOD'] = "POST" && isset($_POST['edit'])){

    session_start();
    $_SESSION['username'] = $_POST['username'];
    header("location: user_edit.php");

}



//To Delete The user From Admin Panel
if($_SERVER['REQUEST_METHOD'] = "POST" && isset($_POST['delete'])){
    $username = $_POST['username'];
    

    $sql = "DELETE FROM users WHERE username= '$username'";
    $result = mysqli_query($conn, $sql);
    if($result = TRUE){
        // echo "User Deleted Successfully!";
        // header("location: dashboard.php");
        // echo "<p style='color:green;'>User Deleted Successfully!!</p>";
?>






<?php


    }

}
?>