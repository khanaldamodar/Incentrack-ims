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
    $update_stmt->bind_param(
        "sssssssssi",
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

    // Handle file upload
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_photo']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../../uploads/profile_photos/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Generate unique filename
            $new_filename = uniqid('profile_') . '.' . $file_ext;
            $destination = $upload_dir . $new_filename;

            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $destination)) {
                // Update database with new photo filename
                $photo_sql = "UPDATE users SET profile_photo = ? WHERE username = ?";
                $photo_stmt = $conn->prepare($photo_sql);
                $photo_stmt->bind_param("si", $new_filename, $user_id);

                if ($photo_stmt->execute()) {
                    $success_message = "Profile photo updated successfully!";
                    // Delete old photo if exists
                    if (!empty($user['profile_photo']) && file_exists($upload_dir . $user['profile_photo'])) {
                        unlink($upload_dir . $user['profile_photo']);
                    }
                } else {
                    $error_message = "Error updating profile photo!";
                }
            } else {
                $error_message = "Error uploading file!";
            }
        } else {
            $error_message = "Invalid file type! Allowed types: jpg, jpeg, png, gif";
        }
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
    <link rel="stylesheet" href="../../assets/css/client-dashboard.css" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    include "../../includes/sidebar.php";
    ?>
    <div class="main-content">
        <div class="profile-container">
            <div class="profile-header">
                <h1><i class="fas fa-user-circle"></i> Profile Settings</h1>
                <?php if (isset($success_message)): ?>
                    <div class="alert success">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="profile-content">
                <div class="profile-sidebar">
                    <div class="profile-image">
                        <img src="<?php echo !empty($user['profile_photo']) ? '../../uploads/profile_photos/' . $user['profile_photo'] : '../../assets/img/profile.png'; ?>" alt="Profile Picture" id="preview-image">
                        <label for="photo-upload" class="upload-overlay">
                            <i class="fas fa-camera"></i>
                            <span>Change Photo</span>
                        </label>
                        <input type="file" id="photo-upload" name="profile_photo" accept="image/*" style="display: none;" form="profile-form">
                    </div>
                    <div class="profile-info">
                        <h2><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h2>
                        <p><i class="fas fa-building"></i> <?php echo htmlspecialchars($user['company_name']); ?></p>
                        <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>

                <div class="profile-details">
                    <form method="POST" action="" class="profile-form" enctype="multipart/form-data" id="profile-form">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Phone Number</label>
                                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="company_name">Company Name</label>
                                <input type="text" id="company_name" name="company_name" value="<?php echo htmlspecialchars($user['company_name']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="company_size">Company Size</label>
                                <select  id="company_size" name="company_size" required>
                                    <option value="1-10" <?php echo $user['company_size'] == '1-10' ? 'selected' : ''; ?>>1-10 employees</option>
                                    <option value="11-50" <?php echo $user['company_size'] == '11-50' ? 'selected' : ''; ?>>11-50 employees</option>
                                    <option value="51-200" <?php echo $user['company_size'] == '51-200' ? 'selected' : ''; ?>>51-200 employees</option>
                                    <option value="201+" <?php echo $user['company_size'] == '201+' ? 'selected' : ''; ?>>201+ employees</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="state">State</label>
                                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($user['state']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" id="postal_code" name="postal_code" value="<?php echo htmlspecialchars($user['postal_code']); ?>" required>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>

    
    </div>

    <style>
        .profile-container {
            padding: 2rem;
            /* max-width: 1200px; */
            width: 100%;
            margin: 0 auto;
        }

        .profile-header {
            margin-bottom: 2rem;
        }

        .profile-header h1 {
            color: #333;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .profile-header h1 i {
            color: #f7aa46;
        }

        .alert {
            padding: 1rem;
            border-radius: 5px;
            margin: 1rem 0;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .profile-content {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .profile-sidebar {
            padding: 2rem;
            background: #f8f9fa;
            border-right: 1px solid #dee2e6;
        }

        .profile-image {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 1.5rem;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .upload-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            padding: 0.5rem;
            text-align: center;
            cursor: pointer;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .profile-image:hover .upload-overlay {
            opacity: 1;
        }

        .profile-info {
            text-align: center;
        }

        .profile-info h2 {
            color: #333;
            margin-bottom: 1rem;
        }

        .profile-info p {
            color: #666;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .profile-details {
            padding: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form-actions {
            margin-top: 2rem;
            display: flex;
            gap: 1rem;
        }
        select, option {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            color: #555;
        }

        .btn-save,
        .btn-change-password {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background-color 0.3s;
        }

        .btn-save {
            background-color: #f7aa46;
            color: white;
        }

        .btn-save:hover {
            background-color: #f59c2a;
        }

        .btn-change-password {
            background-color: #6c757d;
            color: white;
        }

        .btn-change-password:hover {
            background-color: #5a6268;
        }

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
            width: 90%;
            max-width: 500px;
            margin: 10% auto;
            padding: 2rem;
            border-radius: 10px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 1.5rem;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .profile-content {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .profile-sidebar {
                border-right: none;
                border-bottom: 1px solid #dee2e6;
            }
        }
    </style>

    <script src="../../assets/js/profile.js"></script>
    <script>
        document.getElementById('photo-upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Preview the image
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                }
                reader.readAsDataURL(file);

                // Automatically submit the form when a new image is selected
                document.getElementById('profile-form').submit();
                document.getElementById('saveBtn').style.display = 'inline-block';
            }
        });
    </script>
</body>

</html>