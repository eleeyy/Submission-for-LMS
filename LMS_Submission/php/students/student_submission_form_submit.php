<?php
date_default_timezone_set('Asia/Manila');

$servername = "localhost";
$username = "root";
$pass = "";
$dbname = "pup_lms";

$conn = mysqli_connect($servername, $username, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userID = $_POST['userID'];
    $requirementCode = $_POST['requirementCode'];
    $subjectID = $_POST['subjectID'];
    $remarks = "-";
    $note = "-";
    $currentDate = date('Y-m-d'); // Current date in YYYY-MM-DD format
    $currentTime = date('H:i:s'); // Current time in HH:MM:SS format

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["submittedFile"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $valid_types = array("jpg", "jpeg", "png", "gif", "pdf", "pptx", "txt", "docx", "doc");

    // Add a JavaScript file to the page to ensure the functions are available
    echo "<script src='../../js/students/student_submission_form.js'></script>";
    
    if (!in_array($fileType, $valid_types)) {
        echo "<script>showErrorUpload();</script>";
        $uploadOk = 0;
    }

    if ($uploadOk == 1) {
        if (move_uploaded_file($_FILES["submittedFile"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["submittedFile"]["name"])) . " has been uploaded.";

            $submissionID = $userID . $requirementCode;

            $sql = "INSERT INTO student_submission (submission_ID, user_ID, subject_ID, requirement_Code, date, time, remarks, note, file_Path)
                    VALUES ('$submissionID', '$userID', '$subjectID', '$requirementCode', '$currentDate', '$currentTime', '$remarks', '$note', '$target_file')";

            if (mysqli_query($conn, $sql)) {
                echo "<script src='../../js/students/student_submission_form.js'></script>";
                echo "<script>showSubmitSuccess();</script>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

mysqli_close($conn);
?>
