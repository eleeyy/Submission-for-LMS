<?php
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

// Fetch activities with subject_ID 'ABCDE12345'
$subject_ID = 'ABCDE12345'; // Replace with actual subject_ID
$stmt = $conn->prepare("SELECT requirement_Code, name, date_End, time_End FROM submission_requirement WHERE subject_ID = ?");
$stmt->bind_param("s", $subject_ID);
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
    <title>Faculty Activities Page</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_page_activity.css">
</head>
<body>
    <h1>Faculty Side - Activity Page</h1>

    <div class="header">
    </div>

    <div class="nav">
        <nav>
            <ul>
                <li><a href="faculty_page_lectures.php">Lectures</a></li>
                <li><a href="faculty_page_activity.php">Activities</a></li>
                <li><a href="#assessments">Assessments</a></li>
                <li><a href="#interactive-video">Inter-active Video</a></li>
            </ul>
        </nav>
    </div>

    <div class="content">
        <div class="activity">
            <a href="faculty_creating_submission.php" class="button">Add Submission/Activity</a>
        </div>
        <?php foreach ($activities as $activity): ?>
            <div class="submission">
                <h2><?php echo htmlspecialchars($activity['name']); ?></h2>
                <p>Date Due: <?php echo htmlspecialchars($activity['date_End']); ?></p>
                <p>Time Due: <?php echo htmlspecialchars($activity['time_End']); ?></p>
                <a href="faculty_edit_activity.php?requirement_Code=<?php echo urlencode($activity['requirement_Code']); ?>" class="edit-button">Edit Submission</a>
                <a href="faculty_grading_students.php?requirement_Code=<?php echo urlencode($activity['requirement_Code']); ?>" class="grade-button">Grade Students</a>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
