<?php
// Database connection
$host = "localhost";
$username = "root";   // Replace with your database username
$password = "";       // Replace with your database password
$database = "ccs112_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Total Users
$totalUsersQuery = "SELECT COUNT(*) as total FROM accounts";
$totalUsersResult = $conn->query($totalUsersQuery);
$totalUsers = $totalUsersResult->fetch_assoc()['total'];

// New Users (Today)
$todayStart = date('Y-m-d 00:00:00');
$newUsersQuery = "SELECT COUNT(*) as new FROM accounts WHERE CreatedAt >= '$todayStart'";
$newUsersResult = $conn->query($newUsersQuery);
$newUsers = $newUsersResult->fetch_assoc()['new'];

// Active Users (assuming active means created in the last 30 days)
$thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
$activeUsersQuery = "SELECT COUNT(*) as active FROM accounts WHERE CreatedAt >= '$thirtyDaysAgo'";
$activeUsersResult = $conn->query($activeUsersQuery);
$activeUsers = $activeUsersResult->fetch_assoc()['active'];

$conn->close();

// Return data as JSON
echo json_encode([
    'totalUsers' => $totalUsers,
    'newUsers' => $newUsers,
    'activeUsers' => $activeUsers
]);
?>
