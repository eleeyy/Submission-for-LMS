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

if (isset($_GET['requirement_Code'])) {
    $requirementCode = $_GET['requirement_Code'];
    
    $sql = "SELECT name, description, date_End, time_End FROM submission_requirement WHERE requirement_Code='$requirementCode'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $name = $row['name'];
        $description = $row['description'];
        $dateEnd = $row['date_End'];
        $timeEnd = $row['time_End'];
    } else {
        die("No submission requirement found for the given code.");
    }
} else {
    die("No requirement code provided.");
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submission/Activty Form</title>
    <link rel="stylesheet" href="../../styles/students/student_submission_form.css">
</head>
<body>
    <h1>Submit Your Work</h1>
    <form class="content" action="student_submission_form_submit.php" method="post" enctype="multipart/form-data">
        <div class="div_left">
            <input type="hidden" name="userID" value="faculty123"> <!-- Replace with actual user ID -->
            <input type="hidden" name="requirementCode" value="<?php echo $requirementCode; ?>"> <!-- Hidden field for requirement code -->
            <input type="hidden" name="subjectID" value="webdev"> <!-- Replace with actual subject ID -->
            <input type="hidden" name="dueDate" value="<?php echo $dateEnd; ?>"> <!-- Hidden field for due date -->
            <input type="hidden" name="timeDue" value="<?php echo $timeEnd; ?>"> <!-- Hidden field for time due -->
            <input type="text" class="name" name="name" maxlength="50" value="<?php echo $name; ?>" readonly><br><br>
            <textarea class="description" name="description" maxlength="100" readonly><?php echo $description; ?></textarea><br><br>
        </div>
        <div class="div_right">
            <label for="remarks">Remarks:</label>
            <input type="text" class="remarks" name="remarks" maxlength="1" value="-" readonly><br><br>
            <label for="note">Note:</label>
            <input type="text" class="note" name="note" maxlength="200" value="-" readonly><br><br>
            <label for="submittedFile">Submit Your File:</label><br>
            <input type="file" class="submittedFile" name="submittedFile" required><br><br>
            <label for="dueDate">Due Date:</label><br>
            <input type="text" class="dueDate" name="dueDate" value="<?php echo $dateEnd; ?>" readonly><br><br>
            <label for="timeDue">Time Due:</label><br>
            <input type="text" class="timeDue" name="timeDue" value="<?php echo $timeEnd; ?>" readonly><br><br>
            <input type="submit" class="submit" value="Submit">
        </div>
    </form>
</body>
</html>
