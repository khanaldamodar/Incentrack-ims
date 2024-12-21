<?php

require "../../config.php";
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}


$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Panel</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        
        .profile-card {
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            /* flex-wrap: wrap; */
            background-color: #2a2d38;
            border-radius: 8px;
            padding: 16px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: 1s ease-in;
        }
        .profile-card:hover{
            background-color: #2a2d38;
        }

        .image-placeholder {
            width: 70px;
            height: 70px;
            /* background-color: white; */
            /* background-image: url('../../assets/img/profile.png'); */

            border-radius: 4px;
            margin-right: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .details h2 {
            margin: 0;
            font-size: 1.5rem;
            /* color: while; */
        }


        .details .designation {
            font-size: 1rem;
            color: #b0b3bd;
            margin: 4px 0;
        }

        .details .contact {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            margin: 4px 0;
            color: #fff;
        }

        .details .contact span {
            margin-right: 8px;
            font-size: 1rem;
            
        }

        .user-table {
            width: 100%;
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            /* flex-direction: column; */
            justify-content: center;
            align-items: center;
            gap: 30px;
        }
        .actions button{
            background-color: #2a2d38;
            border: none;
            cursor: pointer;
           
            
        }
        .actions{
            display:flex;
            flex-direction: row-reverse;
            gap:20px;
            position: relative;
            bottom:-60px;
            

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
                <li><a class="create-admin" href="admin_list.php">Admin List</a></li>
                <li><a class="create-admin" href="create_admin.php">Create Admin</a></li>
                <li><a class="logout-btn" href="logout.php">Logout</a></li>
            </ul>
            
        </ul>
    </nav>


    <div class="container">
        <h1 style="color: black; text-align:center;"> Application Users List</h1>
        <div class="user-table">
                <?php

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

                        echo '
                            <div class="profile-card">
                            <img src="../../assets/img/profile.png" alt="" class="image-placeholder">' .
                            '<div class="details">' .
                            '<h2 style="color: white;">' . $row['first_name']. ' '. $row['last_name']  . '</h2>
                             <p class="designation">' . $row['company_name'] . '</p>
                            <p class="contact">' .
                            '<span>📧</span>' . $row['email']
                            . '</p>
                             <p class="contact">
                            <span>📞</span>' .'+977 '. $row['phone_number']
                            . '</p>
                             </div>
                             
                             <form action="action.php" method="post" class="actions">
                             <input type="hidden" name="username" value="'.$row['username'].'">
                             <input type="hidden" name="firstName" value="'.$row['first_name'].'">
                             <input type="hidden" name="lastName" value="'.$row['last_name'].'">
                             <input type="hidden" name="address" value="'.$row['address'].'">
                             <input type="hidden" name="state" value="'.$row['state'].'">
                             <input type="hidden" name="postal" value="'.$row['postal_code'].'">
                             <input type="hidden" name="phone" value="'.$row['phone_number'].'">
                             <input type="hidden" name="company" value="'.$row['company_name'].'">
                             <input type="hidden" name="email" value="'.$row['email'].'">




                             <button name="edit"><i style="color: blue; font-size:20px;" class="fa-solid fa-edit"></i> </button>
                             <button name="delete"><i style="color: red; font-size:20px;" class="fa-solid fa-trash"></i> </button>
                             
                             </form>
                             
                             
                             </div>';

                    }
                }

                ?>
            
        </div>
        
        
        
    </div>
</body>







</html>