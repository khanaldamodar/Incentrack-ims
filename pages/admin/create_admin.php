<?php
require "../../config.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$result="";

if($_SERVER['REQUEST_METHOD']== "POST"){
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $SQL = "INSERT INTO admins (name, email, number, passkey) VALUES ('$fullname', '$email', '$phone', '$password')";
    $result = mysqli_query($conn, $SQL);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
  
    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');
*{
  margin: 0;
  padding: 0;
  outline: none;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}
body{
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 30px;
  min-height: 100vh;
  /* padding: 10px; */
  font-family: 'Poppins', sans-serif;
  /* background: linear-gradient(115deg, #56d8e4 10%, #9f01ea 90%); */
  color: black;
}
.container{
  max-width: 800px;
  background: #fff;
  width: 800px;
  padding: 25px 40px 10px 40px;
  box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
}
.container .text{
  text-align: center;
  font-size: 41px;
  font-weight: 600;
  font-family: 'Poppins', sans-serif;
  background-color: rgb(247, 170, 70);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}
.container form{
  padding: 30px 0 0 0;
}
.container form .form-row{
  display: flex;
  margin: 32px 0;
}
form .form-row .input-data{
  width: 100%;
  height: 40px;
  margin: 0 20px;
  position: relative;
}
form .form-row .textarea{
  height: 70px;
}
.input-data input,
.textarea textarea{
  display: block;
  width: 100%;
  height: 100%;
  border: none;
  font-size: 17px;
  border-bottom: 2px solid rgba(0,0,0, 0.12);
  color: #000;
}
.input-data input:focus ~ label, .textarea textarea:focus ~ label,
.input-data input:valid ~ label, .textarea textarea:valid ~ label{
  transform: translateY(-20px);
  font-size: 14px;
  color: #3498db;
}
.textarea textarea{
  resize: none;
  padding-top: 10px;
}
.input-data label{
  position: absolute;
  pointer-events: none;
  bottom: 10px;
  font-size: 16px;
  transition: all 0.3s ease;
}
.textarea label{
  width: 100%;
  bottom: 40px;
  background: #fff;
}
.input-data .underline{
  position: absolute;
  bottom: 0;
  height: 2px;
  width: 100%;
}
.input-data .underline:before{
  position: absolute;
  content: "";
  height: 2px;
  width: 100%;
  background: #3498db;
  transform: scaleX(0);
  transform-origin: center;
  transition: transform 0.3s ease;
}
.input-data input:focus ~ .underline:before,
.input-data input:valid ~ .underline:before,
.textarea textarea:focus ~ .underline:before,
.textarea textarea:valid ~ .underline:before{
  transform: scale(1);
}
.submit-btn .input-data{
  overflow: hidden;
  height: 45px!important;
  width: 25%!important;
}
.submit-btn .input-data .inner{
  height: 100%;
  width: 300%;
  position: absolute;
  left: -100%;
  background-color: rgb(247, 170, 70);
  transition: all 0.4s;
}
.submit-btn .input-data:hover .inner{
  left: 0;
}
.submit-btn .input-data input{
  background: none;
  border: none;
  color: #fff;
  font-size: 17px;
  font-weight: 500;
  text-transform: uppercase;
  letter-spacing: 1px;
  cursor: pointer;
  position: relative;
  z-index: 2;
}
label{
    color:black;
}
.back{
    display:inline-block;
    margin-top:40px;
    width:80%;
    text-decoration: none;

}
@media (max-width: 700px) {
  .container .text{
    font-size: 30px;
  }
  .container form{
    padding: 0px 0px 0 0;
    margin-left: 90px;
  }
  .container form .form-row{
    display: block;
  }
  form .form-row .input-data{
    margin: 35px 0!important;
  }
  .submit-btn .input-data{
    width: 40%!important;
  }
}
.nav-links{
            display: flex;
            flex-direction: row;
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
            justify-content: center;
            
            

        }
        .create-admin{
            text-decoration: none;
            color: white;
            font-size: 1rem;
            /* list-style: none; */
            padding: 0;
            background-color: #2a2d38;
            padding: 7px;
            border-radius: 4px;
            transition: 0.3s ease-in;
            width: auto;

        }
        .create-admin:hover{
            background-color: #dadada;
            color: black;
        }
    </style>
</head>
<body>
<nav>
        <ul class="navbar">
            <li>
                <h1>Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
            </li>
            <ul  class="nav-links">
                <li><a class="create-admin" href="admin_list.php">Admin List</a></li>
                <li><a class="logout-btn" href="logout.php">Logout</a></li>
            </ul>
        </ul>
    </nav>
<div class="container">
      <div class="text">
         Create an Admin
      </div>
      <form action="create_admin.php" method='post'>
         <div class="form-row">
            <div class="input-data">
               <input type="text" name="fullname" required>
               <div class="underline"></div>
               <label for="">Full Name</label>
            </div>
            <div class="input-data">
               <input type="text" name="email" required>
               <div class="underline"></div>
               <label for="">Email Address</label>
            </div>
         </div>
         <div class="form-row">
            <div class="input-data">
               <input type="text" name="phone" required>
               <div class="underline"></div>
               <label for="">Phone Number</label>
            </div>
            <div class="input-data">
               <input type="password" name="password" required>
               <div class="underline"></div>
               <label for="">Password</label>
            </div>
         </div>
         <div class="form-row">
         <div class="input-data textarea">
            
            <div class="form-row submit-btn">
               <div class="input-data">
                  <div class="inner"></div>
                  <input type="submit" value="Create">
                 
               </div>
            </div>
      </form>
      <?php
                 
                 if($result == TRUE){

                    echo '<p style="color:green;">Admin Created Successfully!</p>';
                  }elseif(!$result){
                    echo "";
                  }

                  ?>
    </div>


          <a class="back" style="color:blue; text-a" href="dashboard.php">🏠 Go Back to Dashboard</a>
      
    </body>
</html>