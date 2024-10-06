<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json');

try {
    session_start();
    // Include your database connection file
    require_once 'db_connection.php';

    // Check if the user is logged in and has permission to edit users
    // Add your authentication check here

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userId = $_POST['userId'];
        $name = $_POST['name'];
        $username = $_POST['username'];

        // Check if the new username already exists for a different user
        $checkStmt = $conn->prepare("SELECT ID FROM accounts WHERE Username = ? AND ID != ?");
        $checkStmt->bind_param("si", $username, $userId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Username already exists']);
            exit;
        }
        $checkStmt->close();

        // If username is unique, proceed with the update
        $updateStmt = $conn->prepare("UPDATE accounts SET Name = ?, Username = ? WHERE ID = ?");
        $updateStmt->bind_param("ssi", $name, $username, $userId);

        if ($updateStmt->execute()) {
            echo json_encode(['status' => 'success']);
            $update_successful = true;
            logUserActivity($username, 'Updated profile');
        } else {
            echo json_encode(['status' => 'error', 'message' => $updateStmt->error]);
        }

        $updateStmt->close();
    } else {
        throw new Exception('Invalid request method');
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
