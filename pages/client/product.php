
<?php
require "../../config.php";
require "../../includes/login_validator.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get form data
        $product_id = $_POST['productId'];
        $name = $_POST['productName']; 
        $category = $_POST['category'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        // Validate inputs
        $errors = array();

        // Check if product ID is numeric
        if (!is_numeric($product_id)) {
            $errors[] = "Product ID must be a number";
        }

        // Check if name contains only letters, numbers and spaces
        if (!preg_match("/^[a-zA-Z0-9 ]+$/", $name)) {
            $errors[] = "Product name can only contain letters, numbers and spaces";
        }

        // Check if quantity is positive
        if ($quantity <= 0) {
            $errors[] = "Quantity must be greater than 0";
        }

        // Check if price is positive
        if ($price <= 0) {
            $errors[] = "Price must be greater than 0";
        }

        // If no validation errors, insert into database
        if (empty($errors)) {
            $sql = "INSERT INTO products (product_id, name, category_id, quantity_in_hand, price) 
                    VALUES (?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isids", $product_id, $name, $category, $quantity, $price);

            if ($stmt->execute()) {
                echo "<p class='success'>Product added successfully!</p>";
            } else {
                echo "<p class='error'>Error adding product: " . $conn->error . "</p>";
            }

            $stmt->close();
        } else {
            // Display validation errors
            echo "<div class='error'>";
            foreach($errors as $error) {
                echo "<p>$error</p>";
            }
            echo "</div>";
        }

    } catch (Exception $e) {
        echo "<p class='error'>An error occurred: " . $e->getMessage() . "</p>";
    }
}
?>

<style>
.success {
    color: green;
    text-align: center;
    padding: 10px;
    margin: 10px 0;
    background: #d4edda;
    border-radius: 5px;
}

.error {
    color: red;
    text-align: center;
    padding: 10px;
    margin: 10px 0;
    background: #f8d7da;
    border-radius: 5px;
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
      <h1 style="text-align: center; margin-top: 20px;">Add Product</h1>
        <div class="form-container">
            <form id="productForm" class="product-form" method="POST">
                <div class="form-group">
                    <label for="productId">Product ID:</label>
                    <input style="color: black;" type="text" id="productId" name="productId" required>
                </div>
                <div class="form-group">
                    <label for="productName">Product Name:</label>
                    <input style="color: black;" type="text" id="productName" name="productName" required>
                </div>
                <div class="form-group">
                    <label for="category">Category:</label>
                    <input style="color: black;" type="text" id="category" name="category" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input style="color: black;" type="number" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="price">Price:</label>
                    <input style="color: black;" type="number" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" class="submit-btn"> <i class="fas fa-plus"></i> Add Product</button>
            </form>
            <hr>
        </div>
        <h1 style="text-align: center; margin-top: 20px;">Product List</h1>
        <!-- <table class="product-table">

          <thead>
              <tr>
                  <th>Product ID</th>
                  <th>Product Name</th>
                  <th>Category</th>
                  <th>Quantity</th>
                  <th>Price</th>
                  <th>Actions</th>
              </tr>
          </thead>
          <tbody>
              <tr>
                  <td>001</td>
                  <td>Product A</td>
                  <td>Electronics</td>
                  <td>100</td>
                  <td>$199.99</td>
                  <td class="action-icons">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-alt"></i>
                  </td>
              </tr>
              <tr>
                  <td>002</td>
                  <td>Product B</td>
                  <td>Clothing</td>
                  <td>50</td>
                  <td>$49.99</td>
                  <td class="action-icons">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-alt"></i>
                  </td>
              </tr>
              <tr>
                  <td>003</td>
                  <td>Product C</td>
                  <td>Home & Garden</td>
                  <td>75</td>
                  <td>$79.99</td>
                  <td class="action-icons">
                      <i class="fas fa-edit"></i>
                      <i class="fas fa-trash-alt"></i>
                  </td>
              </tr>
          </tbody>
      </table> -->
        
    </div>



</div>
  
<script>
//   document.getElementById('productForm').addEventListener('submit', function(e) {
//       e.preventDefault();
      
//       const productId = document.getElementById('productId').value;
//       const productName = document.getElementById('productName').value;
//       const category = document.getElementById('category').value;
//       const quantity = document.getElementById('quantity').value;
//       const price = document.getElementById('price').value;

//       const tableBody = document.querySelector('.product-table tbody');
//       const newRow = tableBody.insertRow();
//       newRow.innerHTML = `
//           <td>${productId}</td>
//           <td>${productName}</td>
//           <td>${category}</td>
//           <td>${quantity}</td>
//           <td>$${parseFloat(price).toFixed(2)}</td>
//           <td class="action-icons">
//               <i class="fas fa-edit"></i>
//               <i class="fas fa-trash-alt"></i>
//           </td>
//       `;

//       this.reset();
//   });
 
</script>
  </body>
</html>
