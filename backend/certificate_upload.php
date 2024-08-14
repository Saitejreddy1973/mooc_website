<?php
session_start();
include('db.php');

// Check if user is logged in as a student
if ($_SESSION['role'] != 'student') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch student ID
$sql_student = "SELECT id FROM students WHERE user_id = $user_id";
$result_student = mysqli_query($conn, $sql_student);
$student = mysqli_fetch_assoc($result_student);
$student_id = $student['id'];

// Fetch registered courses
$sql_courses = "SELECT c.id, c.course_name, c.course_code 
                FROM registrations r 
                JOIN courses c ON r.course_id = c.id 
                WHERE r.student_id = $student_id";
$result_courses = mysqli_query($conn, $sql_courses);
$courses = mysqli_fetch_all($result_courses, MYSQLI_ASSOC);

// Handle certificate upload
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $marks = $_POST['marks'];
    $certificate_path = 'uploads/' . basename($_FILES['certificate']['name']);

    if (move_uploaded_file($_FILES['certificate']['tmp_name'], $certificate_path)) {
        $sql_upload = "INSERT INTO certificates (student_id, course_id, marks, certificate_path) 
                       VALUES ($student_id, $course_id, $marks, '$certificate_path')";
        mysqli_query($conn, $sql_upload);
        header('Location: student_dashboard.php');
    } else {
        $error = "Failed to upload certificate.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate Upload</title>
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
                <h2>Certificate Upload</h2>
                <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                <form action="certificate_upload.php" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="course_id">Select Course:</label>
                        <select class="form-control" id="course_id" name="course_id">
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo $course['id']; ?>"><?php echo $course['course_name']; ?> (<?php echo $course['course_code']; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="marks">Marks:</label>
                        <input type="number" class="form-control" id="marks" name="marks" required>
                    </div>
                    <div class="form-group">
                        <label for="certificate">Upload Certificate:</label>
                        <input type="file" class="form-control-file" id="certificate" name="certificate" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
