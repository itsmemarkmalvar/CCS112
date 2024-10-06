<?php
session_start();
require_once 'db_connection.php';

// Add authentication check here

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT ID, Name, Username, CreatedAt FROM accounts WHERE ID = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['error' => 'User not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No user ID provided']);
}

$conn->close();
?>
