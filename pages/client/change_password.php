<?php
session_start();
require_once '../config.php';
// Check if user is logged in
if (!isset($_SESSION['username'])) {
   echo json_encode(['success' => false, 'message' => 'Not authenticated']);
   exit();
}

// Get JSON data
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current_password'] ?? '';
$new_password = $data['new_password'] ?? '';
if (empty($current_password) || empty($new_password)) {
   echo json_encode(['success' => false, 'message' => 'Missing required fields']);
   exit();
}

$user_id = $_SESSION['username'];
// Verify current password
$sql = "SELECT password FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!password_verify($current_password, $user['password'])) {
   echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
   exit();
}

// Update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update_sql = "UPDATE users SET password = ? WHERE username = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("si", $hashed_password, $user_id);
if ($update_stmt->execute()) {
   echo json_encode(['success' => true, 'message' => 'Password updated successfully']);
} else {
   echo json_encode(['success' => false, 'message' => 'Error updating password']);
}
