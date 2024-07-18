<?php
session_start(); // Start session to store user ID

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pup_lms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user ID from session or query string (for example, after login)
$userID = isset($_SESSION['userID']) ? $_SESSION['userID'] : (isset($_GET['userID']) ? $_GET['userID'] : '');

// Fetch lectures with subject_ID 'webdev' (example)
$subject_ID = 'ABCDE12345'; // Replace with actual subject_ID
$stmt = $conn->prepare("SELECT lecture_ID, name, date FROM UPLOAD_LECTURE WHERE subject_ID = ?");
$stmt->bind_param("s", $subject_ID);
$stmt->execute();
$result = $stmt->get_result();

$lectures = [];
while ($row = $result->fetch_assoc()) {
    $lectures[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Lectures Page</title>
    <link rel="stylesheet" href="../../styles/students/student_page_lectures.css">
</head>
<body>
    <h1>Student Side - Lectures Page</h1>

    <div class="header">
    </div>

    <div class="nav">
        <nav>
            <ul>
                <li><a href="student_page_lectures.php?userID=<?php echo urlencode($userID); ?>">Lectures</a></li>
                <li><a href="student_page_activity.php?userID=<?php echo urlencode($userID); ?>">Activities</a></li>
                <li><a href="#assessments">Assessments</a></li>
                <li><a href="#interactive-video">Inter-active Video</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <?php foreach ($lectures as $lecture): ?>
            <div class="lecture">
                <h2><?php echo htmlspecialchars($lecture['name']); ?></h2>
                <p>Date: <?php echo htmlspecialchars($lecture['date']); ?></p>
                <a href="student_download_lecture.php?lecture_ID=<?php echo urlencode($lecture['lecture_ID']); ?>&userID=<?php echo urlencode($userID); ?>" class="button">View Lecture</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
