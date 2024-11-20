<?php
require "../../config.php";
require "../../includes/login_validator.php";
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            // Add new supplier
            $name = $_POST['supplierName'];
            $email = $_POST['email']; 
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            $sql = "INSERT INTO suppliers (supplier_name, email, phone, address) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $phone, $address);

            if ($stmt->execute()) {
                echo "<div class='message success'>Supplier added successfully!</div>";
            } else {
                echo "<div class='message error'>Error adding supplier</div>";
            }

        } else if ($_POST['action'] == 'edit') {
            // Update existing supplier
            $id = $_POST['supplier_id'];
            $name = $_POST['supplierName'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            $sql = "UPDATE suppliers SET supplier_name=?, email=?, phone=?, address=? WHERE supplier_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

            if ($stmt->execute()) {
                echo "<div class='message success'>Supplier updated successfully!</div>";
            } else {
                echo "<div class='message error'>Error updating supplier</div>";
            }

        } else if ($_POST['action'] == 'delete') {
            // Delete supplier
            $id = $_POST['supplier_id'];
            
            $sql = "DELETE FROM suppliers WHERE supplier_id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo "<div class='message success'>Supplier deleted successfully!</div>";
            } else {
                echo "<div class='message error'>Error deleting supplier</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Management</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <style>
        .supplier-container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .supplier-form {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            color: black;
        }

        .supplier-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            
        }

        .supplier-table th, .supplier-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            color: black;
        }

        .supplier-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #4CAF50;
            color: white;
        }

        .delete-btn {
            background-color: #f44336;
            color: white;
        }

        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php require "../../includes/sidebar.php"; ?>

    <div class="main-content">
        <div class="supplier-container">
            <h2 style="color: black;">Add New Supplier</h2>
            
            <form class="supplier-form" method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="supplierName">Supplier Name:</label>
                    <input type="text" id="supplierName" name="supplierName" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <textarea id="address" name="address" required></textarea>
                </div>
                <button type="submit" class="action-btn edit-btn">Add Supplier</button>
            </form>

            <h2 style="color: black;">Supplier List</h2>
            <table class="supplier-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
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
                        echo "<td>
                                <button onclick='deleteSupplier(" . $row['supplier_id'] . ")' class='action-btn delete-btn'>Delete</button>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        function deleteSupplier(id) {
            if(confirm('Are you sure you want to delete this supplier?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="supplier_id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>
