<?php
date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pup_lms";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function generateRequirementCode($name) {
    $monthDay = date('md');
    $nameInitials = strtoupper(substr($name, 0, 2));
    return $monthDay . $nameInitials;
}

// Initialize variables
$name = "";
$description = "";
$date_end = "";
$time_end = "";

// Fetch subject_Id for "webdev"
$subject_name = "webdev";
$sql_subject = "SELECT subject_Id FROM subject WHERE subject_Name = ?";
$stmt_subject = $conn->prepare($sql_subject);
$stmt_subject->bind_param("s", $subject_name);
$stmt_subject->execute();
$result_subject = $stmt_subject->get_result();

if ($result_subject->num_rows > 0) {
    $row_subject = $result_subject->fetch_assoc();
    $subject_id = $row_subject['subject_Id'];
} else {
    die("Error: Subject 'webdev' not found.");
}

$stmt_subject->close();

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $date_end = $_POST['date_end'];
    $time_end = $_POST['time_end'];

    $requirementCode = generateRequirementCode($name);
    $date_start = date('Y-m-d');
    $time_start = date('H:i');

    // Insert into submission_requirement with subject_Id
    $sql = "INSERT INTO submission_requirement (requirement_Code, subject_Id, name, description, date_Start, time_Start, date_End, time_End)
            VALUES ('$requirementCode', '$subject_id', '$name', '$description', '$date_start', '$time_start', '$date_end', '$time_end')";

    if (mysqli_query($conn, $sql)) {
        echo '<script>window.onload = function() { showCreationSuccess(); }</script>';
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Creating Submission Form</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_creating_submission.css">
</head>
<body>
    <h1>Create Submission</h1>
    <div class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" class="name" name="name" maxlength="50" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
            
            <textarea class="description" name="description" maxlength="100" placeholder="Description of the Activity" required><?php echo htmlspecialchars($description); ?></textarea><br><br>
            
            <label for="dateEnd">Due Date: </label><br>
            <input type="date" class="date_end" name="date_end" value="<?php echo htmlspecialchars($date_end); ?>" required><br><br>

            <label for="timeEnd">Time Due: </label><br>
            <input type="time" class="time_end" name="time_end" value="<?php echo htmlspecialchars($time_end); ?>" required><br><br>
        
            <input type="submit" value="Create Submission">
        </form>
    </div>
</body>
<script src="../../js/faculty/faculty_creating_submission.js"></script>
</html>
