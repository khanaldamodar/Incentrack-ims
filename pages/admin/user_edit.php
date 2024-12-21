<?php
require "../../config.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
// echo $username;
$SQL = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $SQL);
$row = mysqli_fetch_assoc($result);

if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['update'])){
    
    $updateSQL = "UPDATE users SET first_name = '$_POST[firstName]', last_name = '$_POST[lastName]', address = '$_POST[address]', state = '$_POST[state]', postal_code = '$_POST[postal]', phone_number = '$_POST[phone]', company_name = '$_POST[company]', email = '$_POST[email]' WHERE username = '$username'";
    $updateResult = mysqli_query($conn, $updateSQL);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
    <style>

        body {
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #000;
            margin: 30px 0;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
            animation: fadeIn 1s ease-in;
        }

        form {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,1);
            animation: slideUp 0.8s ease-out;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 8px rgba(155, 89, 182, 0.4);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: rgb(247, 170, 70);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: green;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Success/Error Messages */
        .message {
            text-align: center;
            padding: 10px;
            margin: 10px auto;
            max-width: 700px;
            border-radius: 8px;
            animation: fadeIn 0.5s ease-in;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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

        }
        .create-admin:hover{
            background-color: #dadada;
            color: black;
        }
        label{
            color: #000;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            display: block;

        }
        .username-note{
            font-size: 0.8rem;
            color: red;
            font-style: italic;

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
                <li><a class="create-admin" href="dashboard.php">Dashboard</a></li>
                <li><a class="create-admin" href="admin_list.php">Admin List</a></li>
                <li><a class="create-admin" href="create_admin.php">Create Admin</a></li>
                <li><a class="logout-btn" href="logout.php">Logout</a></li>
            </ul>
            
        </ul>
    </nav>

<h1>Edit User Profile</h1>

<?php
// Add message display
if(isset($updateResult)) {
    if($updateResult) {
        echo '<div class="message success">User updated successfully</div>';
    } else {
        echo '<div class="message error">User update failed</div>';
    }
}
?>

<form action="user_edit.php" method="post">
    <label for="username">Username : <span class="username-note">Cannot be changed</span></label>
    <input type="text" name="username" value="<?php echo $row['username']; ?>" placeholder="Username" readonly>
    <label for="firstName">First Name : </label>
    <input type="text" name="firstName" value="<?php echo $row['first_name']; ?>" placeholder="First Name">
    <label for="lastName">Last Name : </label>
    <input type="text" name="lastName" value="<?php echo $row['last_name']; ?>" placeholder="Last Name">
    <label for="address">Address : </label>
    <input type="text" name="address" value="<?php echo $row['address']; ?>" placeholder="Address">
    <label for="state">State</label>
    <input type="text" name="state" value="<?php echo $row['state']; ?>" placeholder="State">
    <label for="postal">Postal Code : </label>
    <input type="text" name="postal" value="<?php echo $row['postal_code']; ?>" placeholder="Postal Code">
    <label for="phone">Phone Number : </label>
    <input type="text" name="phone" value="<?php echo $row['phone_number']; ?>" placeholder="Phone Number">
    <label for="company">Company Name : </label>
    <input type="text" name="company" value="<?php echo $row['company_name']; ?>" placeholder="Company Name">
    <label for="email">Email : </label>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" placeholder="Email">
    <button name="update">Update User</button>
</form>

</body>
</html>