<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'db_connection.php'; // Make sure to create this file with your database connection details

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

try {
    // Query to get total users
    $totalUsersStmt = $pdo->query("SELECT COUNT(*) AS total FROM accounts");
    $totalUsers = $totalUsersStmt->fetchColumn();

    // Query to get new users today
    $newUsersStmt = $pdo->prepare("SELECT COUNT(*) AS new_today FROM accounts WHERE DATE(CreatedAt) = CURDATE()");
    $newUsersStmt->execute();
    $newUsers = $newUsersStmt->fetchColumn();

    // Query to get active users (assuming active users are those who logged in today)
    // You may need to adjust this query based on your definition of "active users"
    $activeUsersStmt = $pdo->prepare("SELECT COUNT(DISTINCT Username) AS active_today FROM user_activity WHERE DATE(ActivityDate) = CURDATE()");
    $activeUsersStmt->execute();
    $activeUsers = $activeUsersStmt->fetchColumn();

    // Fetch recent activity
    $stmt = $pdo->prepare("SELECT Username, 'Registered' AS Action, NOW() AS Date 
                           FROM accounts 
                           ORDER BY ID DESC 
                           LIMIT 10");
    $stmt->execute();
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'totalUsers' => $totalUsers,
        'newUsers' => $newUsers,
        'activeUsers' => $activeUsers,
        'activities' => $activities
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
}
