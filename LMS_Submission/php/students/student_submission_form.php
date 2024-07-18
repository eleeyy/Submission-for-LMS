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

if (isset($_GET['requirement_Code'])) {
    $requirementCode = $_GET['requirement_Code'];
    
    $sql = "SELECT name, description, date_End, time_End FROM submission_requirement WHERE requirement_Code=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $requirementCode);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $dateEnd = $row['date_End'];
        $timeEnd = $row['time_End'];
    } else {
        die("No submission requirement found for the given code.");
    }

    $stmt->close();
} else {
    die("No requirement code provided.");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submission/Activity Form</title>
    <link rel="stylesheet" href="../../styles/students/student_submission_form.css">
</head>
<body>
    <h1>Submit Your Work</h1>
    <form class="content" action="student_submission_form_submit.php" method="post" enctype="multipart/form-data">
        <div class="div_left">
            <input type="hidden" name="userID" value="<?php echo htmlspecialchars($userID); ?>"> <!-- User ID dynamically inserted -->
            <input type="hidden" name="requirementCode" value="<?php echo htmlspecialchars($requirementCode); ?>"> <!-- Hidden field for requirement code -->
            <input type="hidden" name="subjectID" value="ABCDE12345"> <!-- Replace with actual subject ID -->
            <input type="hidden" name="dueDate" value="<?php echo htmlspecialchars($dateEnd); ?>"> <!-- Hidden field for due date -->
            <input type="hidden" name="timeDue" value="<?php echo htmlspecialchars($timeEnd); ?>"> <!-- Hidden field for time due -->
            <input type="text" class="name" name="name" maxlength="50" value="<?php echo htmlspecialchars($name); ?>" readonly><br><br>
            <textarea class="description" name="description" maxlength="100" readonly><?php echo htmlspecialchars($description); ?></textarea><br><br>
        </div>
        <div class="div_right">
            <label for="remarks">Remarks:</label>
            <input type="text" class="remarks" name="remarks" maxlength="1" value="-" readonly><br><br>
            <label for="note">Note:</label>
            <input type="text" class="note" name="note" maxlength="200" value="-" readonly><br><br>
            <label for="submittedFile">Submit Your File:</label><br>
            <input type="file" class="submittedFile" name="submittedFile" required><br><br>
            <label for="dueDate">Due Date:</label><br>
            <input type="text" class="dueDate" name="dueDate" value="<?php echo htmlspecialchars($dateEnd); ?>" readonly><br><br>
            <label for="timeDue">Time Due:</label><br>
            <input type="text" class="timeDue" name="timeDue" value="<?php echo htmlspecialchars($timeEnd); ?>" readonly><br><br>
            <input type="submit" class="submit" value="Submit">
        </div>
    </form>
</body>
</html>
