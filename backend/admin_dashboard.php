<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontend/index.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student details
$student_query = "SELECT * FROM users WHERE role='student'";
$student_result = $conn->query($student_query);
$students = $student_result->fetch_all(MYSQLI_ASSOC);

// Fetch course details
$course_query = "SELECT * FROM courses";
$course_result = $conn->query($course_query);
$courses = $course_result->fetch_all(MYSQLI_ASSOC);

// Fetch registered courses
$registration_query = "SELECT * FROM registrations";
$registration_result = $conn->query($registration_query);
$registrations = $registration_result->fetch_all(MYSQLI_ASSOC);

// Fetch certificates
$certificate_query = "SELECT * FROM certificates";
$certificate_result = $conn->query($certificate_query);
$certificates = $certificate_result->fetch_all(MYSQLI_ASSOC);

// Handle mapping and deadlines (example logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        // Process file upload for mapping
    }
    if (isset($_POST['deadline'])) {
        // Set deadline logic
    }
}
?>
