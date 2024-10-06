<?php
header('Content-Type: application/json');

// Database connection
$host = "localhost";
$username = "root";  // Replace with your database username
$password = "";      // Replace with your database password
$database = "ccs112_login";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch recent accounts
$sql = "SELECT Name, Username, CreatedAt FROM accounts ORDER BY CreatedAt DESC LIMIT 10";
$result = $conn->query($sql);

$activities = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $activities[] = [
            'user' => $row['Username'],
            'action' => 'Account created',
            'timestamp' => $row['CreatedAt']
        ];
    }
}

$conn->close();

echo json_encode(['activities' => $activities]);
