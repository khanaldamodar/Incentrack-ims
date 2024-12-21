<?php
require "../../config.php";
require "../../includes/login_validator.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $quantity = $_POST['quantity'];
    $category = $_POST['category'];
    $product_price = $_POST['product_price'];
    $description = $_POST['description'];
    $created_by = $_SESSION['username'];
    $supplier_name = $_POST['supplier_name'];

    // Check if product already exists
    $check_query = "SELECT * FROM inventory_products WHERE p_name = '$product_name'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // Product exists, update quantity
        $update_sql = "UPDATE inventory_products SET quantity = quantity + $quantity WHERE p_name = '$product_name'";
        $result = mysqli_query($conn, $update_sql);
        
        if ($result) {
            echo "<script>alert('Product quantity updated successfully'); window.location.href = 'total-stock.php';</script>";
        } else {
            echo "<script>alert('Error updating quantity: " . mysqli_error($conn) . "'); window.location.href = 'total-stock.php';</script>";
        }
        exit();
    }

    $sql = "INSERT INTO inventory_products (p_name, category, quantity, price, description, created_by, supplier_name) VALUES ('$product_name', '$category', '$quantity', '$product_price', '$description', '$created_by', '$supplier_name')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo "<script>alert('Product added successfully'); window.location.href = 'total-stock.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href = 'total-stock.php';</script>";
    }

    
}
