<?php
session_start();
require '../../config.php';

// Check if user is logged in
if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header("Location: login.php");
    exit();
}
$error = "";

// Get user details from database
$email = $_SESSION['email'];
$sql = "SELECT * FROM clients WHERE email = '$email'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Handle form submission for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone_number'];
    
    if(!$first_name || !$last_name || !$email || !$phone){
        $error = "Please fill all fields";
    }
    elseif(strlen($phone) != 10){
        $error = "Phone number must be 10 digits";
    }
    elseif(!preg_match('/^(98|97)\d{8}$/', $phone)){
        $error = "Phone number must start with 98 or 97";
    }
    else {
        $update_sql = "UPDATE clients SET 
                       first_name = '$first_name',
                       last_name = '$last_name', 
                       email = '$email',
                       phone_number = '$phone'
                       WHERE email = '".$_SESSION['email']."'";
        
        if(mysqli_query($conn, $update_sql)) {
            $_SESSION['first_name'] = $first_name;
            $_SESSION['last_name'] = $last_name;
            $_SESSION['email'] = $email;
            $error = "Profile updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $error = "Error updating profile!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .profile-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            background-color: #2a2d38;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .profile-avatar span {
            color: white;
            font-size: 4rem;
        }

        .profile-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2a2d38;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }

        .update-btn {
            grid-column: span 2;
            background-color: #2a2d38;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s ease;
        }

        .update-btn:hover {
            background-color: #404454;
        }

        .nav-links {
            display: flex;
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
            align-items: center;
            justify-content: center;
        }

        .create-admin {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            padding: 7px;
            background-color: #2a2d38;
            border-radius: 4px;
            transition: 0.3s ease-in;
        }

        .create-admin:hover {
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
            <ul class="nav-links">
                <li><a class="create-admin" href="home.php">Dashboard</a></li>
                <li><a class="create-admin" href="available-products.php">Available Products</a></li>
                <li><a class="create-admin" href="create-request.php">Demand Product</a></li>
                <li style="position: relative;">
                    <div class="profile-circle" style="width: 40px; height: 40px; background-color: #2a2d38; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <span style="color: white; font-size: 1.2rem;">
                            <?php echo strtoupper(substr($_SESSION['first_name'], 0, 1)); ?>
                        </span>
                    </div>
                    <div class="profile-dropdown" style="display: none; position: absolute; top: 100%; right: 0; background-color: white; box-shadow: 0 2px 5px rgba(0,0,0,0.2); border-radius: 4px; width: 150px; margin-top: 10px;">
                        <a href="profile.php" style="display: block; padding: 10px 15px; text-decoration: none; color: #2a2d38; transition: 0.3s ease;">
                            <i class="fas fa-user"></i> Profile
                        </a>
                        <a href="logout.php" style="display: block; padding: 10px 15px; text-decoration: none; color: #2a2d38; transition: 0.3s ease;">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                    <script>
                        document.querySelector('.profile-circle').addEventListener('mouseover', function() {
                            document.querySelector('.profile-dropdown').style.display = 'block';
                        });
                        
                        document.querySelector('.profile-dropdown').addEventListener('mouseleave', function() {
                            this.style.display = 'none';
                        });
                    </script>
                </li>
            </ul>
        </ul>
    </nav>

    <?php if($error): ?>
        <div class="error-message" style="color: <?php echo strpos($error, 'successfully') !== false ? 'green' : 'red'; ?>; text-align: center; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <span><?php echo strtoupper(substr($_SESSION['first_name'], 0, 1)); ?></span>
            </div>
            <h2>Profile Settings</h2>
        </div>

        <form method="POST" class="profile-form">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                
            </div>

            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo $user['phone_number']; ?>" required>
            </div>

            <button type="submit" class="update-btn">Update Profile</button>
        </form>
    </div>
</body>
</html>

