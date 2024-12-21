<?php
session_start();
require '../../config.php';

// Check if user is logged in
if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT * FROM client_requests WHERE created_by = '".$_SESSION['first_name']." ".$_SESSION['last_name']."'";
$result = mysqli_query($conn, $sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
        table{
            width: 75%;
            margin: 0 auto;
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

        /* Styling for the main content */
        .main-content{
            margin-top: 20px;
            padding: 20px;
            /* background-color: #f0f0f0; */
            border-radius: 8px;
        }
        .widget h2{
            text-align: center;
            font-size: 2rem;
            color: #2a2d38;
            margin-bottom: 20px;
        }

        .profile-dropdown a{
            text-decoration: none;
            color: #2a2d38;
            font-size: 1rem;
        }
        .profile-dropdown a:hover{
            background-color: #dadada;
            color: white;
        }

        .delete-btn {
            background-color: #ff4444;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>
<nav>
    <!-- Navigation Bar -->
        <ul class="navbar">
            <li>
                <h1>Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
            </li>
            <ul  class="nav-links">
                <li><a class="create-admin" href="available-products.php">Available Products</a></li>
                <li><a class="create-admin" href="create-request.php">Demand Product</a></li>
                <!-- <li><a class="logout-btn" href="logout.php">Logout</a></li> -->
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
    <!-- Main Content -->
    <div class="main-content">
        <!-- <h1>Welcome to the Home Page</h1> -->
        <!-- <h3>Welcome, <?php echo $_SESSION['first_name']; ?> <?php echo $_SESSION['last_name']; ?></h3> -->

        <div class="widget">
            <h2>Your Recent Order Requests</h2>
            <table>
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row['request_id']."</td>";
                            echo "<td>".$row['product']."</td>";
                            echo "<td>".$row['quantity']."</td>";
                            echo "<td>".$row['created_at']."</td>";
                            echo "<td>".$row['status']."</td>";
                            echo "<td>";
                            
                            echo "<button class='delete-btn'>Delete</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }else{
                        echo "<tr><td colspan='6' style='text-align: center; color: red;'>No requests found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
        
    </div>
</body>
</html>