<?php

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sidebar</title>
    <link rel="stylesheet" href="../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  </head>
  <body>
  <nav>
      <ul class="navbar">
        <li> <h1 style="color: black;">Inven<span style="color: rgb(247, 170, 70)">Track</span></h1></li>
        <li><button class="logout-btn"><a href="logout.php">Logout</a></button></li>
      </ul>
    </nav>
    <div class="container">
    <div class="sidebar">
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="product.php"><i class="fas fa-add"></i>Add Product</a></li>
        <li><a href="supplier.php"><i class="fas fa-truck"></i>Add Supplier</a></li>
        <li><a href="stocks.php"><i class="fas fa-cubes"></i> Manage Orders</a></li>
        <li><a href="reports.php"><i class="fas fa-chart-bar"></i> Reports</a></li>
        <li><a href="profile.php"><i class="fas fa-cog"></i> Profile</a></li>
    </ul>
    </div>
    

  </body>
</html>
