<?php
require "../../config.php";
require "../../includes/login_validator.php";


if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);  // Clear the message after displaying
}



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Settings</title>
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/css/product.css" />

</head>

<body>
    <?php
    require "../../includes/sidebar.php";
    ?>
    <div class="box">
        <h1>Pending Product Requests</h1>

        <?php
        // Add message display here, before the requests-container
        if(isset($_SESSION['error'])) {
            echo '<div class="alert error" style="color: red; text-align: center; margin-bottom: 20px;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }

        if(isset($_SESSION['success'])) {
            echo '<div class="alert success" style="color: green; text-align: center; margin-bottom: 20px;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <div class="requests-container">

            <?php
            // Get all pending requests
            $sql = "SELECT * FROM client_requests WHERE status='pending' ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                    <div class="request-card">
                        <div class="request-header">
                            <h3>Request #<?php echo $row['request_id']; ?></h3>
                            <span class="date"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                        </div>

                        <div class="request-details">
                            <div class="detail-item">
                                <i class="fas fa-box"></i>
                                <span>Product: <?php echo $row['product']; ?></span>
                            </div>

                            <div class="detail-item">
                                <i class="fas fa-layer-group"></i>
                                <span>Quantity: <?php echo $row['quantity']; ?></span>
                            </div>

                            <div class="detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <span>Price per unit: Rs. <?php echo number_format($row['price'], 2); ?></span>
                            </div>

                            <div class="detail-item">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Total Price: Rs. <?php echo number_format($row['total_price'], 2); ?></span>
                            </div>

                            <div class="detail-item">
                                <i class="fas fa-user"></i>
                                <span>Requested by: <?php echo $row['created_by']; ?></span>
                            </div>
                        </div>

                        <div class="request-actions">
                            <form action="process_request.php" method="POST" style="display: inline;">
                                <input type="hidden" name="product_name" value="<?php echo $row['product']; ?>">
                                <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                
                                <button type="submit" name="action" value="Approved" class="btn approve">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button type="submit" name="action" value="cancelled" class="btn reject">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </form>
                            
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="no-requests">
                    <i class="fas fa-inbox fa-3x"></i>
                    <h2>No Pending Requests</h2>
                    <p>There are currently no pending requests to review.</p>
                </div>
            <?php
            }
            ?>

        </div>
        <style>
            .requests-container {
                width: 75%;
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                gap: 20px;
                margin-left: 250px;
                margin-top: 30px;
            }

            .requests-container h1 {
                color: #333;
                text-align: center;
                margin-bottom: 30px;
                font-size: 2.5em;
                color: #000;
            }

            .request-card {
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                padding: 20px;
                margin-bottom: 20px;
                transition: transform 0.2s;
                width: 40%;

            }

            .request-card:hover {
                transform: translateY(-5px);
                /* z-index: -10; */
            }

            .request-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 10px;
                border-bottom: 1px solid #eee;
            }

            .request-header h3 {
                color: #2a2d38;
                margin: 0;
            }

            .date {
                color: #666;
                font-size: 0.9em;
            }

            .request-details {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
                margin-bottom: 20px;
            }

            .detail-item {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .detail-item span {
                color: #000;
            }

            .detail-item i {
                color: #f7aa46;
                width: 20px;
            }

            .request-actions {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
            }

            .btn {
                padding: 8px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-weight: bold;
                display: inline-flex;
                align-items: center;
                gap: 5px;
                transition: all 0.3s;
            }

            .approve {
                background: #4CAF50;
                color: white;
            }

            .approve:hover {
                background: #45a049;
            }

            .reject {
                background: #f44336;
                color: white;
            }

            .reject:hover {
                background: #da190b;
            }

            .no-requests {
                text-align: center;
                padding: 50px;
                color: #666;
            }

            .no-requests i {
                color: #ddd;
                margin-bottom: 20px;
            }

            .no-requests h2 {
                margin: 10px 0;
                color: #333;
            }

            .no-requests p {
                margin: 0;
                color: #666;
            }
        </style>
        <hr style="border: 2px solid #ddd; margin: 20px 0px; margin-left: 250px;">
        <div class="request-history">
            <h2>Request History</h2>
            <?php
            $sql = "SELECT * FROM client_requests WHERE status IN ('approved', 'Cancelled') ORDER BY created_at DESC";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) > 0) {
            ?>
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Request ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            $statusClass = $row['status'] == 'cancelled' ? 'status-cancelled' : 'status-approved';
                            $statusIcon = $row['status'] == 'Cancelled' ? 'fa-times-circle' : 'fa-check-circle';
                            $statusText = $row['status'] == 'Cancelled' ? 'Cancelled' : 'Approved';
                            $statusTextColor = $row['status'] == 'Cancelled' ? '#f44336' : '#4CAF50';
                            $statusColor = $row['status'] == 'Cancelled' ? '#f44336' : '#4CAF50';
                        ?>
                            <tr>
                                <td style="display: flex; align-items: center; gap: 10px;">
                                    <div class="status-cell <?php echo $statusClass; ?>">
                                        <i class="fas <?php echo $statusIcon; ?>"></i>
                                        <span style="color: <?php echo $statusColor; ?>;"><?php echo $statusText; ?></span>

                                    </div>
                                    <form action="process_request.php" method="POST">
                                        <input type="hidden" name="product_name" value="<?php echo $row['product']; ?>">
                                        <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
                                        <input type="hidden" name="action" value="revert">
                                        <button class="btn cancel-btn">Revert</button>
                                    </form>
                                </td>
                                <td>#<?php echo $row['request_id']; ?></td>
                                <td><?php echo $row['product']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    <style>
                        .cancel-btn{
                            background: #f44336;
                            color: white;
                            padding: 5px 10px;
                            border: none;
                            border-radius: 5px;
                            cursor: pointer;
                            font-weight: bold;
                            display: inline-flex;
                            align-items: center;
                            gap: 5px;
                        }
                    </style>
                </table>
            <?php
            } else {
            ?>
                <div class="no-requests">
                    <i class="fas fa-history fa-4x"></i>
                    <h2>No Request History</h2>
                    <p>There are no approved or rejected requests to display.</p>
                </div>
            <?php
            }
            ?>
        </div>

        <style>
            .request-history {
                padding: 20px;
                max-width: 1200px;
                margin: 0 auto;
                margin-left: 250px;
            }

            .request-history h2 {
                color: #333;
                margin-bottom: 20px;
                text-align: center;
                font-size: 24px;
            }

            .history-table {
                width: 100%;
                border-collapse: collapse;
                background: white;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                overflow: hidden;
            }

            .history-table th,
            .history-table td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #eee;
                color: #333;
            }

            .history-table th {
                background-color: #f8f9fa;
                font-weight: 600;
                color: #333;
            }

            .history-table tr:hover {
                background-color: #f5f5f5;
            }

            .status-cell {
                display: flex;
                /* flex-direction: column; */
                align-items: center;
                gap: 8px;
                padding: 5px 10px;
                border-radius: 5px;
                width: fit-content;
            }

            .status-cell.status-approved {
                background-color: #c8e6c9;
                color: #4CAF50;
            }

            .status-cell.status-cancelled {
                background-color: #ffcdd2;
                color: #f44336;
            }

            .status-cell i {
                font-size: 16px;
            }

            .no-requests {
                text-align: center;
                padding: 50px;
                color: #666;
            }

            .no-requests i {
                color: #ddd;
                margin-bottom: 20px;
            }

            .no-requests h2 {
                margin: 10px 0;
                color: #333;
            }

            .no-requests p {
                margin: 0;
                color: #666;
            }
        </style>
    </div>







</body>

</html>