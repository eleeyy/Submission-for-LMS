<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pup_lms";

// Initialize variables
$userID = "";
$errorMessage = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user input
    if (!empty($_POST['userID'])) {
        $userID = $_POST['userID'];

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query to check if user ID exists
        $stmt = $conn->prepare("SELECT user_ID FROM USER_INFORMATION WHERE user_ID = ?");
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $stmt->store_result();

        // If user ID exists, redirect to student_page_lectures.php with user ID as parameter
        if ($stmt->num_rows > 0) {
            header("Location: student_page_lectures.php?userID=" . urlencode($userID));
            exit();
        } else {
            $errorMessage = "Invalid User ID. Please try again.";
        }

        $stmt->close();
        $conn->close();
    } else {
        $errorMessage = "User ID is required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link rel="stylesheet" href="styles/student_login.css">
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="userID">Enter Your User ID:</label><br>
            <input type="text" id="userID" name="userID" value="<?php echo htmlspecialchars($userID); ?>"><br>
            <span class="error"><?php echo htmlspecialchars($errorMessage); ?></span><br><br>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
