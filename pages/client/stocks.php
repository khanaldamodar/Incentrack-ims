<?php
// session_start();
include  '../../config.php';
require "../../includes/login_validator.php";
$created_by = $_SESSION['username'];
$error_message = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplierName = $_POST['supplierName'];
    $productName = $_POST['productName'];
    $quantity = $_POST['quantity'];
    

    // Input validation
    if (empty($supplierName) || empty($productName) || empty($quantity)) {
        // echo "field Required";
        $error_message = '<span class="error">All fields are required</span>';
    } else {
        // Fetch product stock from the database
        $stockQuery = "SELECT total_stock, product_price FROM products WHERE product_name = '$productName'";
        $stockResult = mysqli_query($conn, $stockQuery);

        if ($stockResult && mysqli_num_rows($stockResult) > 0) {
            $product = mysqli_fetch_assoc($stockResult);
            $totalStock = $product['total_stock'];
            $ProductPrice = $product['product_price'];

            if ($quantity > $totalStock) {
                
                $error_message = '<span class="error">Low stock. Available stock is '.$totalStock. '</span>';

            } else {
                // Deduct stock and insert order
                mysqli_begin_transaction($conn);

                try {
                    // Update stock
                    $newStock = $totalStock - $quantity;
                    $updateStockQuery = "UPDATE products SET total_stock = $newStock WHERE product_name = '$productName'";
                    $updateStockResult = mysqli_query($conn, $updateStockQuery);

                    if (!$updateStockResult) {
                        throw new Exception("Error updating stock.");
                    }
                    // Insert order
                    $insertOrderQuery = "INSERT INTO orders (supplier_name, product_name, quantity, price, created_by) 
                                         VALUES ('$supplierName', '$productName', $quantity, $ProductPrice, '$created_by')";
                    $insertOrderResult = mysqli_query($conn, $insertOrderQuery);

                    if (!$insertOrderResult) {
                        throw new Exception("Error inserting order.");
                    }

                    mysqli_commit($conn);
                    $success_message = '<span class="error">Order created successfully.</span>';
                } catch (Exception $e) {
                    mysqli_rollback($conn);
                    
                    $error_message = '<span class="error">Error creating order:'.$e->getMessage(). '</span>';
                }
            }
        } else {
            $error_message = '<span class="error">Product not found.</span>';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="../../assets/css/stocks.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        #supplierName,
        #productName {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: #333;
            background-color: #fff;
            outline: none;
        }

        .supplier-form input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            color: black;
        }
        .error{
            color: red;
        }
        td{
            color: black;
        }
        
        .subcontainer{
            margin-left: 200px;
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <?php include "../../includes/sidebar.php"; ?>
    <div class="subcontainer">


        <!-- <div class="shortcuts">
            <div class="box total-order">
                <i class="fas fa-shopping-cart"></i>
                <h5>Total Orders</h5>
                <h5>10</h5>
            </div>
            <div class="box pending-order">
                <i class="fas fa-clock"></i>
                <h5>Pending Orders</h5>
                <h5>30</h5>
            </div>
            <div class="box cancel-order">
                <i class="fas fa-ban"></i>
                <h5>Cancelled Orders</h5>
                <h5>2</h5>
            </div>
        </div> -->
        
        <form class="supplier-form" method="POST">
            <h2 style="color: black;">Create Mannual Order</h2>
            <div class="form-group">
                <label for="supplierName">Supplier Name:</label>
                <!-- <input type="text" id="supplierName" name="supplierName" required> -->

                <select style="color: black;" name="supplierName" id="supplierName">
                    <option style='color:black' selected value="">Selcet Supplier</option>
                    <?php
                    $supplierSQL = "SELECT supplier_name FROM suppliers where created_by = '$created_by'";
                    $supplierResult = mysqli_query($conn, $supplierSQL);
                    
                  
                    
                    //? Fetching Data from Database to select the name of Supplier to create an order
                    if ($supplierResult->num_rows > 0) {
                        while ($row = $supplierResult->fetch_assoc()) {
                            echo "<option style='color:black' value='" . $row['supplier_name'] . "'>" . $row['supplier_name'] . "</option>";
                        }
                    } else {
                        echo "<option value=''>Suppliers not available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="productName">Product Name:</label>
                <!-- <input type="text" id="productName" name="productName" required> -->
                <select style="color: black;" name="productName" id="productName">
                    <option style='color:black' selected value="">Selcet Product</option>
                    <?php
                    $price = '';
                      $productSQL = "SELECT product_name, total_stock, product_price FROM products where created_by = '$created_by'";
                      $productResult = mysqli_query($conn, $productSQL);
                    //? Fetching Data from Database to select the product name to create an order
                    if ($productResult->num_rows > 0) {
                        while ($row = $productResult->fetch_assoc()) {
                            echo "<option style='color:black' value='" . $row['product_name'] . "'>" . $row['product_name'] . "</option>";
                        }
                        if($row['product_name'] = $productName){
                            $price = $row['product_price'];
                        }
                    } else {
                        echo "<option value=''>No products available</option>";
                    }
                    ?>
                </select>



            </div>
            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>
            <button type="submit" class="action-btn">Create Order</button>
            <?php
             if(!$error_message){
                echo "<span></span>";
             }else{
                echo $error_message;
             }
             ?>


        </form>

        <div class="tablecontainer">
            <table class="supplier-table">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Product</th>
                        <th>Rate</th>
                        <th>Quantity</th>
                        <th>Total Cost</th>
                      
                    </tr>
                </thead>
                <tbody style="color: red;"> 
                    <?php
                    // Query to get orders for the current user's products
                    $orderQuery = "SELECT * FROM orders WHERE created_by = '$created_by'";
                    $orderResult = mysqli_query($conn, $orderQuery);

                    if ($orderResult && mysqli_num_rows($orderResult) > 0) {
                        while ($order = mysqli_fetch_assoc($orderResult)) {
                            $totalCost = $order['price'] * $order['quantity'];
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($order['supplier_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($order['product_name']) . "</td>";
                            echo "<td>Rs. " . htmlspecialchars($order['price']) . "</td>";
                            echo "<td>" . htmlspecialchars($order['quantity']) . " Pcs</td>";
                            echo "<td>Rs. " . htmlspecialchars($totalCost) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td style='color:red;' colspan='6'>No orders found</td></tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</body>
</html>