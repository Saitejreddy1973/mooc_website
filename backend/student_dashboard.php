<?php
session_start();
include('db.php');

// Check if user is logged in as a student
if ($_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student details
$sql = "SELECT * FROM students WHERE user_id = $user_id";
$result = mysqli_query($conn, $sql);
$student = mysqli_fetch_assoc($result);

// Fetch registered courses and credits
$sql_courses = "SELECT c.course_name, c.course_code, c.course_duration 
                FROM registrations r 
                JOIN courses c ON r.course_id = c.id 
                WHERE r.student_id = $student[id]";
$result_courses = mysqli_query($conn, $sql_courses);
$courses = mysqli_fetch_all($result_courses, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <ul class="list-group">
                    <li class="list-group-item"><a href="student_dashboard.php">Dashboard</a></li>
                    <li class="list-group-item"><a href="course_registration.php">Course Registration</a></li>
                    <li class="list-group-item"><a href="certificate_upload.php">Certificate Upload</a></li>
                    <li class="list-group-item"><a href="profile.php">Profile</a></li>
                    <li class="list-group-item"><a href="change_password.php">Change Password</a></li>
                    <li class="list-group-item"><a href="logout.php">Logout</a></li>
                </ul>
            </div>
            <div class="col-md-9">
                <h2>Dashboard</h2>
                <p>Maximum Credits: 24</p>
                <p>Courses Completed: <?php echo count($courses); ?></p>
                <p>Credits Earned: <?php echo $student['credits_earned']; ?></p>
                <p>Credits to be Earned: <?php echo 24 - $student['credits_earned']; ?></p>
                <h3>Registered Courses</h3>
                <ul>
                    <?php foreach ($courses as $course): ?>
                        <li><?php echo $course['course_name']; ?> (<?php echo $course['course_code']; ?>) - <?php echo $course['course_duration']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
