<?php
require "../../config.php";
require "../../includes/login_validator.php";



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Total Stock</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css">
</head>

<body>
    <?php
    include('../../includes/sidebar.php');
    ?>
    <?php


    // Fetch all products with their details
    $username = $_SESSION['username'];
    $query = "SELECT * FROM inventory_products where created_by = '$username'";
    $result = mysqli_query($conn, $query);

    ?>

    <div class="stock-container">

        <h1>Available Products</h1>

        <div class="products-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="product-card">
                        <div class="product-header">
                            <h3><?php echo $row['p_name']; ?></h3>
                            <span class="stock-badge"><?php echo $row['quantity']; ?> in stock</span>
                        </div>
                        <div class="product-details">
                            <div class="detail-item">
                                <i class="fas fa-tag"></i>
                                <span>Category: <?php echo $row['category']; ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Price: Rs. <?php echo number_format($row['price'], 2); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Description: <?php echo $row['description']; ?></span>
                            </div>


                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="no-products">
                    <i class="fas fa-box-open fa-3x"></i>
                    <h2>No Products Available</h2>
                    <p>There are currently no products in stock.</p>
                </div>
            <?php
            }
            ?>
        </div>
        <hr>
        <div class="supplier-section">
            <h2>Request Products from Suppliers</h2>

            <?php
            // Fetch suppliers from database
            $supplier_query = "SELECT * FROM suppliers";
            $supplier_result = mysqli_query($conn, $supplier_query);

            if (mysqli_num_rows($supplier_result) > 0) {
            ?>
                <div class="suppliers-grid">
                    <?php
                    while ($supplier = mysqli_fetch_assoc($supplier_result)) {
                    ?>
                        <div class="supplier-card">
                            <div class="supplier-info">
                                <h3><?php echo $supplier['company_name']; ?></h3>
                                <p><i class="fas fa-user"></i> <?php echo $supplier['supplier_name']; ?></p>
                                <p><i class="fas fa-phone"></i> <?php echo $supplier['phone']; ?></p>
                                <p><i class="fas fa-envelope"></i> <?php echo $supplier['email']; ?></p>
                            </div>
                            <div class="supplier-actions">
                                <button class="request-btn" onclick="openRequestForm(<?php echo $supplier['supplier_id']; ?>)">
                                    <?php
                                    $_SESSION['supplier_id'] = $supplier['supplier_id'];
                                    $supplier_id = $_SESSION['supplier_id'];

                                    ?>
                                    <i class="fas fa-paper-plane"></i> Send Request
                                </button>
                            </div>
                        </div>
                    <?php
                    }

                    ?>
                </div>

                <!-- Request Form Modal -->
                <div id="requestModal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h3 style="text-align: center; color: #f7aa46;">Product Request Form</h3>

                        <!-- : Request Form -->

                        <form id="requestForm" action="request-product.php" method="POST">
                            <input type="hidden" id="supplier_id" value="<?php $supplier_id; ?>" name="supplier_id">
                            <div class="form-group">
                                <label>Product Name:</label>
                                <!-- <input style="border: 1px solid #f7aa46; color: #f7aa46;" type="text" name="product_name" required> -->
                                <select name="product_name" id="product_name" required onchange="updateProductDetails()">
                                    <option value="" disabled selected>Select Product</option>
                                    <?php
                                    $query = "SELECT product_name, category, product_price, description FROM supplier_products";
                                    $result = mysqli_query($conn, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='" . $row['product_name'] . "' 
                                            data-category='" . $row['category'] . "' 
                                            data-price='" . $row['product_price'] . "'
                                            data-description='" . htmlspecialchars($row['description']) . "'>" .
                                            $row['product_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                <script>
                                    function updateProductDetails() {
                                        var select = document.getElementById('product_name');
                                        var selectedOption = select.options[select.selectedIndex];
                                        document.getElementsByName('category')[0].value = selectedOption.dataset.category;
                                        document.getElementsByName('product_price')[0].value = selectedOption.dataset.price;
                                        document.getElementsByName('description')[0].value = selectedOption.dataset.description;
                                    }
                                </script>
                            </div>
                            <div class="form-group">
                                <label>Description:</label>
                                <textarea style="border: 1px solid #f7aa46; color: #f7aa46;" name="description" required readonly></textarea>
                            </div>


                            <div class="form-group">
                                <label>Category:</label>
                                <input style="border: 1px solid #f7aa46; color: #f7aa46;" name="category" type="text" readonly></input>
                                <input type="hidden" name="supplier_name" value="<?php echo $supplier['supplier_name']; ?>">
                            </div>
                            <div class="form-group">
                                <label>Price:</label>
                                <input style="border: 1px solid #f7aa46; color: #f7aa46;" name="product_price" type="text" readonly></input>
                            </div>
                            <div class="form-group">
                                <label>Quantity:</label>
                                <input style="border: 1px solid #f7aa46; color: #f7aa46;" type="number" name="quantity" required>
                            </div>
                            <button type="submit" class="submit-btn">Send Request</button>
                        </form>
                    </div>
                </div>

                <style>
                    .supplier-section {
                        margin-top: 40px;
                    }

                    .supplier-section h2 {
                        color: #333;
                        text-align: center;
                        margin-bottom: 30px;
                    }

                    .suppliers-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
                        gap: 25px;
                        padding: 20px;
                    }

                    .supplier-card {
                        background: white;
                        border-radius: 10px;
                        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                        padding: 20px;
                        transition: transform 0.3s ease;
                    }

                    .supplier-card:hover {
                        transform: translateY(-5px);
                    }

                    .supplier-info h3 {
                        color: #2a2d38;
                        margin-bottom: 15px;
                    }

                    .supplier-info p {
                        margin: 8px 0;
                        color: #666;
                    }

                    .supplier-info i {
                        width: 20px;
                        color: #f7aa46;
                    }

                    .supplier-actions {
                        margin-top: 20px;
                        text-align: center;
                    }

                    .request-btn {
                        background: #f7aa46;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        transition: background 0.3s ease;
                    }

                    .request-btn:hover {
                        background: #e59935;
                    }

                    /* Modal Styles */
                    .modal {
                        display: none;
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.5);
                        z-index: 1000;
                    }

                    .modal-content {
                        background: white;
                        margin: 10% auto;
                        padding: 20px;
                        width: 50%;
                        border-radius: 10px;
                        position: relative;
                    }

                    .close {
                        position: absolute;
                        right: 20px;
                        top: 10px;
                        font-size: 28px;
                        cursor: pointer;
                        color: #000;
                    }

                    .form-group {
                        margin: 15px 0;
                    }

                    .form-group label {
                        display: block;
                        margin-bottom: 5px;
                        color: #333;
                    }

                    .form-group input,
                    .form-group textarea,
                    .form-group select {
                        width: 100%;
                        padding: 8px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        color: #333;
                    }

                    .form-group select option {
                        color: #333;
                    }

                    .submit-btn {
                        background: #f7aa46;
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        width: 100%;
                        margin-top: 10px;
                    }

                    .submit-btn:hover {
                        background: #e59935;
                    }
                </style>

                <script>
                    // Get modal elements
                    const modal = document.getElementById('requestModal');
                    const closeBtn = document.getElementsByClassName('close')[0];
                    const supplierIdInput = document.getElementById('supplier_id');

                    // Function to open modal
                    function openRequestForm(supplierId) {
                        modal.style.display = "block";
                        supplierIdInput.value = supplierId;
                    }

                    // Close modal when clicking (x)
                    closeBtn.onclick = function() {
                        modal.style.display = "none";
                    }

                    // Close modal when clicking outside
                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.style.display = "none";
                        }
                    }
                </script>
            <?php
            } else {
            ?>
                <div class="no-suppliers">
                    <i class="fas fa-users fa-3x"></i>
                    <h2>No Suppliers Available</h2>
                    <p>There are currently no suppliers registered in the system.</p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <style>
        .stock-container {
            width: 75%;
            margin-left: 250px;
            padding: 30px;
            margin-top: 100px;
            margin-left: 250px;
        }

        .stock-container h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            padding: 20px;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .product-header h3 {
            color: #2a2d38;
            margin: 0;
        }

        .stock-badge {
            background: #f7aa46;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .product-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-item i {
            color: #f7aa46;
            width: 20px;
        }

        .detail-item span {
            color: #333;
        }

        .no-products {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px;
            color: #666;
        }

        .no-products i {
            color: #f7aa46;
            margin-bottom: 20px;
        }

        .no-products h2 {
            color: #333;
            margin-bottom: 10px;
        }
    </style>


</body>

</html>