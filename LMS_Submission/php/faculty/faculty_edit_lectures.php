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

// Initialize variables
$name = "";
$description = "";
$file_path = "";
$lecture_ID = "";

// Process if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['delete'])) {
        // Delete the lecture from database
        $lecture_ID = $_POST['lecture_ID']; // Assuming lecture_ID is passed via POST

        $stmt = $conn->prepare("DELETE FROM UPLOAD_LECTURE WHERE lecture_ID = ?");
        $stmt->bind_param("s", $lecture_ID);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=true");
            exit();
        } else {
            echo "Error deleting lecture: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Update lecture details in database
        $name = $_POST['name'];
        $description = $_POST['description'];
        $lecture_ID = $_POST['lecture_ID']; // Assuming lecture_ID is passed via POST

        // Validate lecture_ID (you might want to validate against database here)

        $stmt = $conn->prepare("UPDATE UPLOAD_LECTURE SET name = ?, description = ? WHERE lecture_ID = ?");
        $stmt->bind_param("sss", $name, $description, $lecture_ID);

        if ($stmt->execute()) {
            $stmt->close();
            header("Location: " . $_SERVER['PHP_SELF'] . "?updated=true");
            exit();
        } else {
            echo "Error updating lecture: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Fetch lecture details based on lecture_ID
if (isset($_GET['lecture_ID'])) {
    $lecture_ID = $_GET['lecture_ID'];

    $stmt = $conn->prepare("SELECT name, description FROM UPLOAD_LECTURE WHERE lecture_ID = ?");
    $stmt->bind_param("s", $lecture_ID);
    $stmt->execute();
    $stmt->bind_result($name, $description);
    $stmt->fetch();

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Edit Lecture</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_edit_lectures.css">
</head>
<body>
    <h1>Edit Lecture</h1>
    <div class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">

            <!-- Hidden field to pass lecture_ID -->
            <input type="hidden" name="lecture_ID" value="<?php echo htmlspecialchars($lecture_ID); ?>">

            <!-- For Lecture Name -->
            <input type="text" class="name" name="name" maxlength="50" placeholder="Lecture Name" value="<?php echo htmlspecialchars($name); ?>"><br><br>

            <!-- For the Description -->
            <textarea class="description" name="description" maxlength="100" placeholder="Description of the Lecture"><?php echo htmlspecialchars($description); ?></textarea><br><br>
        
            <input type="submit" value="Update Lecture" class="submit">

            <!-- Delete button -->
            <?php if (!empty($lecture_ID)): ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="lecture_ID" value="<?php echo htmlspecialchars($lecture_ID); ?>">
                    <input type="submit" name="delete" value="Delete Lecture" class="delete-button" onclick="return confirm('Are you sure you want to delete this lecture?')">
                </form>
            <?php endif; ?>
        </form>
    </div>
</body>
<script src="../../js/faculty/faculty_edit_lecture.js"></script>
</html>
