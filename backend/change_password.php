<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../frontend/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    $query = "SELECT * FROM users WHERE id='$user_id'";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();

    if (password_verify($old_password, $user['password'])) {
        $new_password_hashed = password_hash($new_password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET password='$new_password_hashed' WHERE id='$user_id'";
        $conn->query($update_query);
        echo "Password changed successfully.";
    } else {
        echo "Incorrect old password.";
    }
}
?>
