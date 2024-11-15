<?php
require "../../config.php";
$first_sql = "ALTER TABLE categories
ADD COLUMN user_id INT,
ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(user_id);
";
$second_sql = "SELECT c.name AS category_name
FROM categories c
JOIN users u ON c.user_id = u.user_id
WHERE c.user_id = <current_user_id>;
";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management System</title>
    <!-- <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/> -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            color: #333;
            margin-bottom: 10px;
        }
        .stat-card p {
            font-size: 24px;
            color: #007bff;
        }
        .products-section {
            margin-top: 20px;
        }
        .add-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .products-table {
            width: 100%;
            border-collapse: collapse;
        }
        .products-table th, .products-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: white;
            width: 50%;
            margin: 100px auto;
            padding: 20px;
            border-radius: 8px;
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
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php
        // require '../../includes/sidebar.php';
    ?>
    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Categories</h3>
            <p id="totalCategories">0</p>
        </div>
        <div class="stat-card">
            <h3>Total Products</h3>
            <p id="totalProducts">0</p>
        </div>
        <div class="stat-card">
            <h3>Total Amount</h3>
            <p id="totalAmount">$0</p>
        </div>
    </div>

    <div class="products-section">
        <button class="add-btn" onclick="openModal()">Add Product</button>
        <table class="products-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="productsList">
                <!-- Products will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <h2>Add New Product</h2>
            <form id="addProductForm" onsubmit="addProduct(event)">
                <div class="form-group">
                    <label for="productName">Product Name</label>
                    <input type="text" id="productName" required>
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <select id="category" required>
                        <option value="">Select Category</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Clothing">Clothing</option>
                        <option value="Food">Food</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="stock">Stock</label>
                    <input type="number" id="stock" required>
                </div>
                <button type="submit" class="add-btn">Add Product</button>
                <button type="button" class="add-btn" style="background: #dc3545;" onclick="closeModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        let products = [];
        let categories = new Set();
        
        function updateStats() {
            document.getElementById('totalCategories').textContent = categories.size;
            document.getElementById('totalProducts').textContent = products.length;
            const totalAmount = products.reduce((sum, product) => sum + (product.price * product.stock), 0);
            document.getElementById('totalAmount').textContent = `Rs.${totalAmount.toFixed(2)}`;
        }

        function displayProducts() {
            const productsList = document.getElementById('productsList');
            productsList.innerHTML = '';
            
            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.category}</td>
                    <td>Rs.${product.price}</td>
                    <td>${product.stock}</td>
                    <td>
                        <button onclick="deleteProduct(${product.id})" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 3px;">Delete</button>
                    </td>
                `;
                productsList.appendChild(row);
            });
            
        }

        function openModal() {
            document.getElementById('addProductModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('addProductModal').style.display = 'none';
            document.getElementById('addProductForm').reset();
        }

        function addProduct(event) {
            event.preventDefault();
            
            const newProduct = {
                id: products.length + 1,
                name: document.getElementById('productName').value,
                category: document.getElementById('category').value,
                price: parseFloat(document.getElementById('price').value),
                stock: parseInt(document.getElementById('stock').value)
            };

            products.push(newProduct);
            categories.add(newProduct.category);
            
            updateStats();
            displayProducts();
            closeModal();
        }

        function deleteProduct(id) {
            products = products.filter(product => product.id !== id);
            categories = new Set(products.map(product => product.category));
            
            updateStats();
            displayProducts();
        }

        // Initialize the display
        updateStats();
        displayProducts();
    </script>
</body>
</html>

