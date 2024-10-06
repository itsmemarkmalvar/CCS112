<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "ccs112_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => "Connection failed: " . $conn->connect_error]));
}

function logUserActivity($username, $action) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO user_activity (Username, Action) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $action);
    $stmt->execute();
    $stmt->close();
    // Don't output anything here
}
?>
