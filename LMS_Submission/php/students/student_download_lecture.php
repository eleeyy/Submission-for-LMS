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

// Get the lecture_ID from the URL
$lecture_ID = isset($_GET['lecture_ID']) ? $_GET['lecture_ID'] : '';

// Fetch lecture details based on lecture_ID from UPLOAD_LECTURE table
$sql = "SELECT name, description, file_path FROM UPLOAD_LECTURE WHERE lecture_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $lecture_ID);
$stmt->execute();
$result = $stmt->get_result();

// Initialize variables to store fetched data
$name = "";
$description = "";
$file_path = "";

if ($result->num_rows > 0) {
    // Output data of the selected lecture (assuming only 1 result is expected)
    $row = $result->fetch_assoc();
    $name = $row['name'];
    $description = $row['description'];
    $file_path = $row['file_path'];
} else {
    echo "Lecture not found.";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Download Lecture</title>
    <link rel="stylesheet" href="../../styles/students/student_download_lecture.css">
</head>
<body>
    <h1>Lecture</h1>
    <div class="main">
        <form action="<?php echo $file_path; ?>" method="get">
            <!-- Display Lecture Name -->
            <input type="text" class="name" name="name" maxlength="50" value="<?php echo htmlspecialchars($name); ?>" readonly><br><br>

            <!-- Display Description -->
            <textarea class="description" name="description" maxlength="100" readonly><?php echo htmlspecialchars($description); ?></textarea><br><br>

            <!-- Download Link for File -->
            <br><br>
            <label for="uploadedFile">Uploaded File:</label><br>

            <!-- File Preview Section -->
            <div class="file-preview">
                <?php
                $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'pptx', 'pdf', 'docx', 'doc', 'txt');

                if (in_array($file_extension, $allowed_extensions)) {
                    // Display file name as the preview
                    echo '<p>File Preview: ' . htmlspecialchars(basename($file_path)) . '</p>';
                } else {
                    // Default message for unsupported file types
                    echo '<p>Preview not available for this file type.</p>';
                }
                ?>
            </div>

            <a href="<?php echo htmlspecialchars($file_path); ?>" download>Download file</a>
        </form>
    </div>
</body>
</html>
