<?php
session_start();
require_once 'db_connection.php';

$username = $_SESSION['username'] ?? 'Unknown';
logUserActivity($username, 'Logged out');

session_destroy();
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Logged out successfully']);
exit;
?>
