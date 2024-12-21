<?php
include '../../config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['first_name']) || !isset($_SESSION['last_name'])) {
    header("Location: login.php");
    exit();
}

// Only process POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $quantity = floatval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $total_price = $price * $quantity;
    $created_by = $_SESSION['first_name'] . " " . $_SESSION['last_name'];


    $sql = "INSERT INTO client_requests (product, quantity, price, total_price, created_by) VALUES ('$product_name', $quantity, $price, $total_price, '$created_by')";


    if (!$product_name || !$quantity || !$price || !$total_price) {
        echo "<script>alert('Please fill all fields');</script>";
    } else {
        $result = mysqli_query($conn, $sql);
        if ($result) {
            echo "<script>alert('Request submitted successfully');</script>";
        } else {
            echo "<script>alert('Request failed');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

        /* Styling for the main content */
        .main-content {
            margin-top: 20px;
            padding: 20px;
            /* background-color: #f0f0f0; */
            border-radius: 8px;
        }

        .widget h2 {
            text-align: center;
            font-size: 2rem;
            color: #2a2d38;
        }
    </style>
    <style>
        body {
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #000;
            margin: 30px 0;
            font-size: 2.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in;
        }

        form {
            max-width: 700px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 1);
            animation: slideUp 0.8s ease-out;
        }

        select,
        input {
            width: 100%;
            padding: 12px 15px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        input:focus {
            border-color: #9b59b6;
            box-shadow: 0 0 8px rgba(155, 89, 182, 0.4);
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: rgb(247, 170, 70);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        button:hover {
            background: green;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Success/Error Messages */
        .message {
            text-align: center;
            padding: 10px;
            margin: 10px auto;
            max-width: 700px;
            border-radius: 8px;
            animation: fadeIn 0.5s ease-in;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

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

        label {
            color: #000;
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            display: block;

        }

        .username-note {
            font-size: 0.8rem;
            color: red;
            font-style: italic;

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
            <li><a class="create-admin" href="available-products.php">Available Products</a></li>
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

    <h1>Demand Product</h1>




    <form action="create-request.php" method="post">
        <select name="product_name" id="product_name">
            <option value="">Select Product</option>
            <?php
            $sql = "SELECT * FROM inventory_products";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['p_name'] . "' data-price='" . $row['price'] . "'>" . $row['p_name'] . "</option>";
            }
            ?>
        </select>
        <input type="number" name="price" id="price" placeholder="Product Price" readonly>
        <input type="number" name="quantity" id="quantity" placeholder=" Enter Quantity" min="1" required>
        <button name="submit">Submit</button>
    </form>


    <script>
        document.getElementById('product_name').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            document.getElementById('price').value = price || '';

        });
    </script>
</body>

</html>