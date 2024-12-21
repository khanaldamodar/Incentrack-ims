<?php
require "../../includes/login_validator.php";
require "../../config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php require '../../includes/sidebar.php'; ?>
    
    <div class="main-content">
        
            <div class="reports-header">
                <h1><i style="color: #f7aa46;" class="fas fa-chart-line"></i> Inventory Reports & Analytics</h1>
            </div>

            <div class="report-cards">
                <?php
                // Get total products
                $products_query = "SELECT COUNT(*) as total FROM inventory_products";
                $products_result = mysqli_query($conn, $products_query);
                $total_products = mysqli_fetch_assoc($products_result)['total'];

                // Get total value of inventory
                $value_query = "SELECT SUM(quantity * price) as total FROM inventory_products";
                $value_result = mysqli_query($conn, $value_query);
                $total_value = mysqli_fetch_assoc($value_result)['total'];

                // Get pending requests
                $pending_query = "SELECT COUNT(*) as total FROM client_requests WHERE status='pending'";
                $pending_result = mysqli_query($conn, $pending_query);
                $pending_requests = mysqli_fetch_assoc($pending_result)['total'];

                // Get low stock items (less than 10 units)
                $low_stock_query = "SELECT COUNT(*) as total FROM inventory_products WHERE quantity < 10";
                $low_stock_result = mysqli_query($conn, $low_stock_query);
                $low_stock = mysqli_fetch_assoc($low_stock_result)['total'];
                ?>

                <div class="stat-card">
                    <i class="fas fa-boxes"></i>
                    <h3>Total Products</h3>
                    <p><?php echo $total_products; ?></p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-money-bill-wave"></i>
                    <h3>Total Inventory Value</h3>
                    <p>Rs. <?php echo number_format($total_value, 2); ?></p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-clock"></i>
                    <h3>Pending Requests</h3>
                    <p><?php echo $pending_requests; ?></p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h3>Low Stock Items</h3>
                    <p><?php echo $low_stock; ?></p>
                </div>
            </div>

            <div class="charts-container">
                <div class="chart-card">
                    <h3>Stock Distribution by Category</h3>
                    <canvas id="categoryChart"></canvas>
                </div>

                <div class="chart-card">
                    <h3>Request Status Overview</h3>
                    <canvas id="requestChart"></canvas>
                </div>
            </div>

            <div class="table-container">
                <h2>Low Stock Items Details</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $low_stock_details = "SELECT * FROM inventory_products WHERE quantity < 10 ORDER BY quantity ASC";
                        $result = mysqli_query($conn, $low_stock_details);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['p_name'] . "</td>";
                            echo "<td>" . $row['category'] . "</td>";
                            echo "<td>" . $row['quantity'] . "</td>";
                            echo "<td>Rs. " . number_format($row['price'], 2) . "</td>";
                            echo "<td><span class='status-badge " . ($row['quantity'] <= 5 ? 'critical' : 'warning') . "'>" . 
                                 ($row['quantity'] <= 5 ? 'Critical' : 'Warning') . "</span></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        

        <script>
            // Category Distribution Chart
            <?php
            $category_query = "SELECT category, COUNT(*) as count FROM inventory_products GROUP BY category";
            $category_result = mysqli_query($conn, $category_query);
            $categories = [];
            $category_counts = [];
            while ($row = mysqli_fetch_assoc($category_result)) {
                $categories[] = $row['category'];
                $category_counts[] = $row['count'];
            }
            ?>

            new Chart(document.getElementById('categoryChart'), {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($categories); ?>,
                    datasets: [{
                        data: <?php echo json_encode($category_counts); ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']
                    }]
                }
            });

            // Request Status Chart
            <?php
            $request_query = "SELECT status, COUNT(*) as count FROM client_requests GROUP BY status";
            $request_result = mysqli_query($conn, $request_query);
            $statuses = [];
            $status_counts = [];
            while ($row = mysqli_fetch_assoc($request_result)) {
                $statuses[] = ucfirst($row['status']);
                $status_counts[] = $row['count'];
            }
            ?>

            new Chart(document.getElementById('requestChart'), {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($statuses); ?>,
                    datasets: [{
                        label: 'Number of Requests',
                        data: <?php echo json_encode($status_counts); ?>,
                        backgroundColor: ['#FF9F40', '#4BC0C0', '#FF6384']
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <style>
            .reports-container {
                padding: 20px;
                margin-left: 220px;
            }

            .reports-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .reports-header h1 {
                color: #333;
                font-size: 2em;
            }

            .report-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .stat-card {
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: transform 0.3s ease;
            }

            .stat-card:hover {
                transform: translateY(-5px);
            }

            .stat-card i {
                font-size: 2em;
                color: #f7aa46;
                margin-bottom: 10px;
            }

            .stat-card h3 {
                color: #666;
                margin-bottom: 10px;
            }

            .stat-card p {
                color: #333;
                font-size: 1.5em;
                font-weight: bold;
            }

            .charts-container {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 20px;
                margin-bottom: 30px;
            }

            .chart-card {
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            }

            .chart-card h3 {
                color: #333;
                text-align: center;
                margin-bottom: 20px;
            }

            .table-container {
                background: white;
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            }

            .table-container h2 {
                color: #333;
                margin-bottom: 20px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                padding: 12px;
                text-align: left;
                border-bottom: 1px solid #ddd;
                color: #333;
            }

            th {
                background-color: #f7aa46;
                color: white;
            }

            tr:hover {
                background-color: #f5f5f5;
            }

            .status-badge {
                padding: 5px 10px;
                border-radius: 20px;
                font-size: 0.9em;
                font-weight: bold;
            }

            .critical {
                background-color: #ff4444;
                color: white;
            }

            .warning {
                background-color: #ffbb33;
                color: white;
            }
        </style>

    </div> 
</body>
</html>
