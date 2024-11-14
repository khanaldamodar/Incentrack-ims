<?php
require "../../config.php";

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  </head>
  <body>
   <?php
   require '../../includes/sidebar.php';
   ?>
    <div class="main-content">
        <h1>Welcome to the Dashboard</h1>

        <div class="widget">
            <h2>Sales Overview</h2>
            <p>Total Sales: $1,500</p>
            <p>Today's Sales: $250</p>
        </div>
        <div class="widget">
            <h2>Inventory Status</h2>
            <p>Low Stock Items: 5</p>
            <p>Out of Stock Items: 2</p>
        </div>
        <div class="widget">
            <h2>Recent Orders</h2>
            <ul>
                <li>Order #12345 - Placed on 2023-07-15</li>
                <li>Order #12346 - Placed on 2023-07-16</li>
                <li>Order #12347 - Placed on 2023-07-17</li>
            </ul>
        </div>

        <!-- <div class="widget">
            <h2>Customer Feedback</h2>
            <p>Overall Satisfaction: 4.5/5</p>
            <p>Recent Feedback: "Great service and products!" - John D.</p>
        </div> -->
        <div class="widget">
            <h2>Recent Purchases</h2>
            <ul>
                <li>Purchase #12348 - Placed on 2023-07-18</li>
                <li>Purchase #12349 - Placed on 2023-07-19</li>
                <li>Purchase #12350 - Placed on 2023-07-20</li>
            </ul>

        </div>


        
      </div>

    </div>


  </body>
</html>
