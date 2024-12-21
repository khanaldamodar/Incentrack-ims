<?php
require "../../config.php";
require "../../includes/login_validator.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_name = $_POST['product_name'];
    $action = $_POST['action'];
    $product_id = $_POST['request_id'];
    

    $select_query = "SELECT quantity FROM inventory_products WHERE p_name = '$product_name'";
    $result = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result);
    $quantity = $row['quantity'];

    // Get the quantity of the client request
    $select_query = "SELECT quantity FROM client_requests WHERE product = '$product_name'";
    $result = mysqli_query($conn, $select_query);
    $row = mysqli_fetch_assoc($result);
    $client_quantity = $row['quantity'];

    if ($action == 'Approved') {
        // Get the quantity of the Inventory product

        if ($client_quantity > $quantity) {
            $_SESSION['error'] = "Quantity is not available!";
            header("location: product.php");
            exit();
        } else {
            $sql = "UPDATE client_requests SET status = 'approved' WHERE request_id = '$product_id'";
            if (mysqli_query($conn, $sql)) {
                $sql = "UPDATE inventory_products SET quantity = quantity - $client_quantity WHERE p_name = '$product_name'";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['success'] = "Request approved successfully!";
                } else {
                    $_SESSION['error'] = "Error: " . mysqli_error($conn);
                }
            } else {
                $_SESSION['error'] = "Error: " . mysqli_error($conn);
            }
            header("location: product.php");
            exit();
        }
    } elseif ($action == 'cancelled') {
        if ($client_quantity < $quantity) {
            // $_SESSION['error'] = "Quantity is not available!";
            echo '<div class="overlay" id="confirmationOverlay">
                <div class="popup">
                    <div class="popup-content">
                        <i class="fas fa-exclamation-circle"></i>
                        <h2>Sufficient Stock Available</h2>
                        <p>There is enough stock in inventory to fulfill this request. Are you sure you want to cancel it?</p>
                        <div class="popup-buttons">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="product_name" value="' . $product_name . '">
                                <input type="hidden" name="action" value="cancelled">
                               
                            </form>
                            <button onclick="window.location.href=\'product.php\'" class="cancel-btn">No, Keep Request</button>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 1000;
                }
                
                .popup {
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
                    max-width: 500px;
                    width: 90%;
                    text-align: center;
                }

                .popup-content i {
                    font-size: 48px;
                    color: #ffc107;
                    margin-bottom: 20px;
                }

                .popup-content h2 {
                    color: #333;
                    margin-bottom: 15px;
                    font-size: 24px;
                }

                .popup-content p {
                    color: #666;
                    margin-bottom: 25px;
                    line-height: 1.5;
                }

                .popup-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 15px;
                }

                .confirm-btn, .cancel-btn {
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    transition: all 0.3s ease;
                }

                .confirm-btn {
                    background: #dc3545;
                    color: white;
                }

                .cancel-btn {
                    background: #6c757d;
                    color: white;
                }

                .confirm-btn:hover {
                    background: #c82333;
                }

                .cancel-btn:hover {
                    background: #5a6268;
                }
            </style>';
            exit();
        }
        $sql = "UPDATE client_requests SET status = 'cancelled' WHERE product = '$product_name'";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['success'] = "Request cancelled successfully!";
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($conn);
        }
        header("location: product.php");
        exit();
    } elseif ($action == 'revert') {
        // Get the specific request using request_id
        $sql = "SELECT * FROM client_requests WHERE request_id = '$product_id' AND status IN ('approved', 'cancelled') LIMIT 1";
        $result = mysqli_query($conn, $sql);
        
        if ($row = mysqli_fetch_assoc($result)) {
            $previous_status = strtolower($row['status']);
            $client_quantity = $row['quantity'];
            $product_name = $row['product']; // Get product name from the request
            
            // If previous status was approved, add back to inventory
            if ($previous_status == 'approved') {
                // Update inventory stock
                $update_inventory = "UPDATE inventory_products SET quantity = quantity + $client_quantity WHERE p_name = '$product_name'";
                if (!mysqli_query($conn, $update_inventory)) {
                    $_SESSION['error'] = "Error updating inventory: " . mysqli_error($conn);
                    header("location: product.php");
                    exit();
                }
            }
            
            // Update request status to pending using request_id
            $update_request = "UPDATE client_requests SET status = 'pending' WHERE request_id = '$product_id'";
            if (mysqli_query($conn, $update_request)) {
                $_SESSION['success'] = "Request reverted successfully!";
            } else {
                $_SESSION['error'] = "Error reverting request: " . mysqli_error($conn);
            }
        } else {
            $_SESSION['error'] = "Request not found!";
        }
        
        header("location: product.php");
        exit();
    } else {
        echo "Invalid action";
    }
}
