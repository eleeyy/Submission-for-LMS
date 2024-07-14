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

// Initialize variables
$name = "";
$description = "";
$date_end = "";
$time_end = "";
$requirement_code = "";

// Fetch activity details based on requirement_Code
if (isset($_GET['requirement_Code'])) {
    $requirement_code = $_GET['requirement_Code'];

    $stmt = $conn->prepare("SELECT name, description, date_End, time_End FROM submission_requirement WHERE requirement_Code = ?");
    $stmt->bind_param("s", $requirement_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = $row['name'];
        $description = $row['description'];
        $date_end = $row['date_End'];
        $time_end = $row['time_End'];
    } else {
        die("Error: Activity not found.");
    }

    $stmt->close();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Delete the activity from the database
        $requirement_code = $_POST['requirement_Code'];

        $stmt = $conn->prepare("DELETE FROM submission_requirement WHERE requirement_Code = ?");
        $stmt->bind_param("s", $requirement_code);

        if ($stmt->execute()) {
            // Redirect back to the form with a success message
            header("Location: " . $_SERVER['PHP_SELF'] . "?deleted=true");
            exit(); // Ensure that no further code is executed after redirection
        } else {
            echo "Error deleting activity: " . mysqli_error($conn);
        }

        $stmt->close();
    } else {
        // Update activity in the database
        $name = $_POST['name'];
        $description = $_POST['description'];
        $date_end = $_POST['date_end'];
        $time_end = $_POST['time_end'];
        $requirement_code = $_POST['requirement_Code'];

        $stmt = $conn->prepare("UPDATE submission_requirement SET name = ?, description = ?, date_End = ?, time_End = ? WHERE requirement_Code = ?");
        $stmt->bind_param("sssss", $name, $description, $date_end, $time_end, $requirement_code);

        if ($stmt->execute()) {
            // Redirect back to the form with a success message
            header("Location: " . $_SERVER['PHP_SELF'] . "?requirement_Code=" . urlencode($requirement_code) . "&updated=true");
            exit(); // Ensure that no further code is executed after redirection
        } else {
            echo "Error updating activity: " . mysqli_error($conn);
        }

        $stmt->close();
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Edit Activity</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_edit_activity.css">
</head>
<body>
    <h1>Edit Activity</h1>
    <div class="main">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?requirement_Code=" . urlencode($requirement_code); ?>" method="post">
            <input type="text" class="name" name="name" maxlength="50" placeholder="Name" value="<?php echo htmlspecialchars($name); ?>" required><br><br>
            
            <textarea class="description" name="description" maxlength="100" placeholder="Description of the Activity" required><?php echo htmlspecialchars($description); ?></textarea><br><br>
            
            <label for="dateEnd">Due Date: </label><br>
            <input type="date" class="date_end" name="date_end" value="<?php echo htmlspecialchars($date_end); ?>" required><br><br>

            <label for="timeEnd">Time Due: </label><br>
            <input type="time" class="time_end" name="time_end" value="<?php echo htmlspecialchars($time_end); ?>" required><br><br>
        
            <input type="hidden" name="requirement_Code" value="<?php echo htmlspecialchars($requirement_code); ?>">
            <input type="submit" class="submit" value="Update Activity">
        </form>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?requirement_Code=" . urlencode($requirement_code); ?>" method="post">
            <input type="hidden" name="requirement_Code" value="<?php echo htmlspecialchars($requirement_code); ?>">
            <input type="submit" name="delete" value="Delete Activity" class="delete-button" onclick="return confirm('Are you sure you want to delete this activity?')">
        </form>
    </div>
</body>
<script src="../../js/faculty/faculty_edit_activity.js"></script>
</html>
