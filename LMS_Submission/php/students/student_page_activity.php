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

// Fetch activities with subject_ID 'ABCDE12345'
$subject_ID = 'ABCDE12345'; // Replace with actual subject_ID
$stmt = $conn->prepare("SELECT sr.requirement_Code, sr.name, sr.date_End, sr.time_End,
                        IFNULL(ss.submission_ID, '') AS submission_exists
                        FROM submission_requirement sr
                        LEFT JOIN student_submission ss ON sr.requirement_Code = ss.requirement_Code AND ss.user_ID = ?
                        WHERE sr.subject_ID = ?");
$stmt->bind_param("ss", $userID, $subject_ID);
$stmt->execute();
$result = $stmt->get_result();

$activities = [];
while ($row = $result->fetch_assoc()) {
    $activities[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Activity Page</title>
    <link rel="stylesheet" href="../../styles/students/student_page_activity.css">
</head>
<body>
    <h1>Student Side - Activity Page</h1>

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
        <?php foreach ($activities as $activity): ?>
            <div class="submission">
                <h2><?php echo htmlspecialchars($activity['name']); ?></h2>
                <p>Date Due: <?php echo htmlspecialchars($activity['date_End']); ?></p>
                <p>Time Due: <?php echo htmlspecialchars($activity['time_End']); ?></p>
                <?php if (!empty($activity['submission_exists'])): ?>
                    <span style="color: green; font-weight: bold;">Done</span>
                <?php endif; ?>
                <a href="student_submission_form.php?requirement_Code=<?php echo urlencode($activity['requirement_Code']); ?>&userID=<?php echo urlencode($userID); ?>" class="view-button">View Submission</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
