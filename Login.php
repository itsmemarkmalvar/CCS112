<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start output buffering
ob_start();

// Log the start of the script execution
error_log("Login.php script started");

session_start();

// Include your database connection
require_once 'db_connection.php';

// Ensure this is the first output
header('Content-Type: application/json');

// Database connection parameters
$host = "localhost";
$dbname = "ccs112_login";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

error_log("Database connection successful");

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    error_log("Received login attempt for username: " . $username);

    // Validate all fields are filled
    if (empty($username) || empty($password)) {
        error_log("Empty username or password");
        echo json_encode(["status" => "error", "message" => "Username and password are required"]);
        exit();
    }

    // Prepare and execute the query
    $stmt = $conn->prepare("SELECT ID, Password FROM accounts WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            $_SESSION['user_id'] = $row['ID'];
            $_SESSION['username'] = $username;
            logUserActivity($username, 'Logged in');
            error_log("Login successful for user: " . $username);
            echo json_encode(["status" => "success", "message" => "Login successful"]);
        } else {
            error_log("Invalid password for user: " . $username);
            echo json_encode(["status" => "error", "message" => "Invalid password"]);
        }
    } else {
        error_log("User not found: " . $username);
        echo json_encode(["status" => "error", "message" => "User not found"]);
    }

    // Close statement
    $stmt->close();
} else {
    error_log("Invalid request method");
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close connection
$conn->close();

// Get the buffered content
$output = ob_get_clean();

// Log the output
error_log("Output before sending: " . $output);

// Send the output
echo $output;

// Exit to prevent any additional output
exit();
?>
