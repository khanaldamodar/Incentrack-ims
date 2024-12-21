<?php
require "../../config.php";
require "../../includes/login_validator.php";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="../../assets/css/supplier.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    
</head>
<body>
    <?php require "../../includes/sidebar.php"; ?>

    <div class="main-content">
        <div class="supplier-container">
            <h2 style="color: black;">Supplier List</h2>
            <table class="supplier-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $sql = "SELECT * FROM suppliers";
                    $result = $conn->query($sql);

                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['supplier_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['phone']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                        
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
   
</body>
</html>
