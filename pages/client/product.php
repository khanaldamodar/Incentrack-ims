<?php
require "../../config.php"; 
require "../../includes/login_validator.php";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'add') {
        // Add a new product
        $name = $_POST['name'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $created_by = $_SESSION['username'];

        $sql = "INSERT INTO products (product_name, category, total_stock, product_price, created_by) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdis", $name, $category, $stock, $price, $created_by);

        if ($stmt->execute()) {
            echo "<script>alert('Product added successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>alert('Error adding product.');</script>";
        }
    } elseif ($_POST['action'] == 'update') {
        // Update price and stock of a product
        $id = $_POST['id'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];

        $sql = "UPDATE products SET product_price = ?, total_stock = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dii", $price, $stock, $id);

        if ($stmt->execute()) {
            echo "<script>alert('Product updated successfully!');</script>";
            header("Location: " . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>alert('Error updating product.');</script>";
        }
    }
}

// Fetch all products
    $user = $_SESSION['username'];
$products = [];
$sql = "SELECT product_id, product_name, category, product_price, total_stock FROM products where created_by = '$user'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<style>
        .main-content {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        table {
            
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            color: black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .button {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .button:hover {
            background-color: #0056b3;
        }
        input,h2{
            color: black;
        }
    </style>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/product.css"/>
    
  </head>
  <body>
   <?php
    require "../../includes/sidebar.php";
   ?>
    <div class="main-content">
    <h1>Products</h1>
    <button class="button" onclick="document.getElementById('addProductForm').style.display='block'">Add Product</button>

     <!-- Add Product Form -->
     <div id="addProductForm" style="display:none; margin-top: 20px;">
        <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="name">Product Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="category">Category:</label>
                <input type="text" id="category" name="category" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>
            </div>
            <button type="submit" class="button">Add Product</button>
        </form>
    </div>

     <!-- Product List -->
     <h2 style="color: black;">Product List</h2>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['product_name']) ?></td>
                <td><?= htmlspecialchars($product['category']) ?></td>
                <td>Rs.<?= htmlspecialchars(number_format($product['product_price'], 2)) ?></td>
                <td><?= htmlspecialchars($product['total_stock']) ?></td>
                <td>
                    <button class="button" onclick="updateProduct(<?= $product['product_id'] ?>, <?= $product['product_price'] ?>, <?= $product['total_stock'] ?>)">Update</button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

        <!-- Update Product Form -->
<div id="updateProductForm" style="display:none; margin-top: 20px;">
    <h2 style="color: black;">Update Product</h2>
    <form method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="updateProductId">
        <div class="form-group">
            <label for="updatePrice">Price:</label>
            <input type="number" id="updatePrice" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="updateStock">Stock:</label>
            <input type="number" id="updateStock" name="stock" required>
        </div>
        <button type="submit" class="button">Update Product</button>
    </form>
</div>
    </div>



<script>
    function updateProduct(id, price, stock) {
        document.getElementById('updateProductForm').style.display = 'block';
        document.getElementById('updateProductId').value = id;
        document.getElementById('updatePrice').value = price;
        document.getElementById('updateStock').value = stock;
    }
</script>
  

  </body>
</html>
