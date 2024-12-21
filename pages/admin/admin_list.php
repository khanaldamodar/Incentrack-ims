<?php

include '../../config.php';
require "../../config.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}
$sql = "SELECT * FROM admins";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
    <style>
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .admin-table th, .admin-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .admin-table th {
            background-color: #2a2d38;
            color: white;
        }
        
        .admin-table tr:hover {
            background-color: #f5f5f5;
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
    </style>
</head>
<body>
<nav>
        <ul class="navbar">
            <li>
                <h1>Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
            </li>
            <ul  class="nav-links">
                <li><a class="create-admin" href="create_admin.php">Create Admin</a></li>
                <li><a class="logout-btn" href="logout.php">Logout</a></li>
            </ul>
            
        </ul>
    </nav>

    <div class="container">
        <h1 style="color: black; text-align:center;">Admin List</h1>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['number'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align: center;'>No admins found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a class="back" style="color:blue; text-align:center; display:block; margin-top:30px;" href="dashboard.php">🏠 Go Back to Dashboard</a>
    </div>
</body>
</html>



