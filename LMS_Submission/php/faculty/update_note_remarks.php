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

// Get data from AJAX request
$sub_id = $_POST['submission_id'];
$new_remarks = isset($_POST['remarks']) ? $_POST['remarks'] : null;
$new_note = isset($_POST['note']) ? $_POST['note'] : null;

// Update remarks and note in database
if ($new_remarks !== null) {
    $stmt = $conn->prepare("UPDATE STUDENT_SUBMISSION SET remarks = ? WHERE submission_ID = ?");
    $stmt->bind_param("ss", $new_remarks, $sub_id);
    $stmt->execute();
    $stmt->close();
}

if ($new_note !== null) {
    $stmt = $conn->prepare("UPDATE STUDENT_SUBMISSION SET note = ? WHERE submission_ID = ?");
    $stmt->bind_param("ss", $new_note, $sub_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();

echo "Remarks and/or note updated successfully";
?>
