<?php
session_start();
if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header("Location: login.php");
    exit();
}
include '../../config.php';
$sql = "SELECT * FROM inventory_products";
$result = mysqli_query($conn, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products</title>
    <link rel="stylesheet" href="../../assets/css/admin-dashboard.css" />
    <style>
        .nav-links {
            display: flex;
            flex-direction: row;
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
            /* list-style: none; */
            padding: 0;
            background-color: #2a2d38;
            padding: 7px;
            border-radius: 4px;
            transition: 0.3s ease-in;

        }

        .create-admin:hover {
            background-color: #dadada;
            color: black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }

        table,
        th,
        td {
            /* border: 1px solid #000; */
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <ul class="navbar">
        <li>
            <h1>Inven<span style="color: rgb(247, 170, 70)">Track</span></h1>
        </li>
        <ul class="nav-links">
            <li><a class="create-admin" href="home.php">Dashboard</a></li>
            <li><a class="create-admin" href="create-request.php">Demand Products</a></li>
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

    <h1 style="text-align: center; margin-top: 20px; margin-bottom: 20px;">Available Products</h1>
    <table style="margin: 0 auto; width: 80%; padding: 20px;">
        <thead>
            <tr>
                <th>Product Id</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo $product['p_id']; ?></td>
                    <td><?php echo $product['p_name']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><?php echo $product['supplier_name']; ?></td>
                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>

</body>

</html>