<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = "localhost";
$dbname = "ccs112_login";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate all fields are filled
    if (empty($username) || empty($password)) {
        echo "Username and password are required";
        exit();
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT Password FROM accounts WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            echo "Login successful";
        } else {
            echo "Invalid password";
        }
    } else {
        echo "User not found";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
