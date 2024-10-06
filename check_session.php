<?php
session_start();
if (isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "success", "username" => $_SESSION['username']]);
} else {
    echo json_encode(["status" => "error", "message" => "Not logged in"]);
}
?>
