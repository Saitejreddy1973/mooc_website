<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fa') {
    header("Location: ../frontend/index.html");
    exit();
}

// Fetch students of FA's section
$user_id = $_SESSION['user_id'];
$fa_query = "SELECT * FROM users WHERE id='$user_id'";
$fa_result = $conn->query($fa_query);
$fa = $fa_result->fetch_assoc();
$fa_name = $fa['name'];

$query = "SELECT * FROM users WHERE fa_name='$fa_name'";
$result = $conn->query($query);
$students = $result->fetch_all(MYSQLI_ASSOC);
?>
