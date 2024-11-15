<?php
session_start();
require_once '../../config.php';

//Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['username'];

// Fetch user data
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];
    $phone_number = $_POST['phone_number'];
    $company_size = $_POST['company_size'];
    $company_name = $_POST['company_name'];
    $email = $_POST['email'];

    $update_sql = "UPDATE users SET 
                   first_name = ?, 
                   last_name = ?, 
                   address = ?, 
                   state = ?, 
                   postal_code = ?, 
                   phone_number = ?, 
                   company_size = ?, 
                   company_name = ?, 
                   email = ? 
                   WHERE username = ?";
                   
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssssssssi", 
        $first_name, 
        $last_name, 
        $address, 
        $state, 
        $postal_code, 
        $phone_number, 
        $company_size, 
        $company_name, 
        $email, 
        $user_id
    );

    if ($update_stmt->execute()) {
        $success_message = "Profile updated successfully!";
        // Refresh user data
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $error_message = "Error updating profile!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="../../assets/css/profile.css">
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css"/>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
      integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
</head>
<body>
    <?php
    include "../../includes/sidebar.php";
    ?>
    <div class="profile-container">
        <h2>Profile Settings</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form id="profileForm" method="POST" action="">
            <div class="form-group">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="postal_code">Postal Code:</label>
                <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="company_size">Company Size:</label>
                <input type="text" id="company_size" name="company_size" value="<?php echo htmlspecialchars($user['company_size']); ?>" readonly class="readonly-mode">
            </div>

            <div class="form-group">
                <label for="company_name">Company Name:</label>
                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($user['company_name']); ?>" readonly class="readonly-mode">
            </div>

            <button type="button" id="editBtn" class="btn">Edit Profile</button>
            <button type="submit" id="saveBtn" class="btn" style="display: none;">Save Changes</button>
        </form>
    </div>

    <script src="../../assets/js/profile.js"></script>
</body>
</html>
