<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Upload Lecture</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_upload_lecture.css">
</head>
<body>
    <h1>Upload Lecture</h1>
    <div class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <!-- For Lecture Name -->
            <input type="text" class="name" name="name" maxlength="50" placeholder="Lecture Name" required><br><br>

            <!-- For the Description -->
            <textarea class="description" name="description" maxlength="100" placeholder="Description of the Lecture" required></textarea><br><br>

            <!-- For the File Upload-->
            <input type="file" class="file_path" name="file_path" required><br><br>

            <input type="submit" value="Submit" class="submit">
        </form>
    </div>

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

    // Process form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve subject_ID for subject_Name = 'webdev' (napapalitan)
        $subject_Name = "webdev";
        $stmt = $conn->prepare("SELECT subject_ID FROM subject WHERE subject_Name = ?");
        $stmt->bind_param("s", $subject_Name);
        $stmt->execute();
        $stmt->bind_result($subject_ID);
        $stmt->fetch();
        $stmt->close();

        if (!$subject_ID) {
            die("Subject 'webdev' not found.");
        }

        // Validate and process file upload
        if (isset($_FILES['file_path']) && $_FILES['file_path']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "../uploads/"; // Directory where files will be stored
            $target_file = $target_dir . basename($_FILES["file_path"]["name"]);
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check file type and size (modify as needed)
            $allowed_types = array("jpg", "jpeg", "png", "gif", "pdf", "pptx", "txt", "docx", "doc");
            $max_file_size = 50 * 1024 * 1024; // 50 MB (modify as needed)

            if (!in_array($file_type, $allowed_types)) {
                echo '<script>window.onload = function() { showErrorFileType(); }</script>';
            } elseif ($_FILES["file_path"]["size"] > $max_file_size) {
                echo '<script>window.onload = function() { showErrorFileSize(); }</script>';
            } else {
                // Move uploaded file to desired directory
                if (move_uploaded_file($_FILES["file_path"]["tmp_name"], $target_file)) {

                    // File upload success, now insert data into database
                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $file_path = $target_file; // Store the file path in database

                    $date = date('Y-m-d');
                    $time = date('H:i:s');

                    // Generate lecture_ID (adjust as per your requirement)
                    $month = date('m');
                    $day = date('d');
                    $name_initials = strtoupper(substr($name, 0, 2));
                    $lecture_ID = $month . $day . $name_initials;

                    // Insert data into database
                    $stmt = $conn->prepare("INSERT INTO UPLOAD_LECTURE (lecture_ID, subject_ID, name, description, date, time, file_path) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $lecture_ID, $subject_ID, $name, $description, $date, $time, $file_path);

                    if ($stmt->execute()) {
                        echo '<script>window.onload = function() { showUploadSuccess(); }</script>';
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }
        } else {
            echo "Error uploading file: " . $_FILES["file_path"]["error"];
        }
    }

    $conn->close();
    ?>
</body>
<script src="../../js/faculty/faculty_upload_lectures.js"></script>
</html>
