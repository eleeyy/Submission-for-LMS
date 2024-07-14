<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Grade Students</title>
    <link rel="stylesheet" href="../../styles/faculty/faculty_grading_students.css">
</head>
<body>
    <h1>Faculty Side - Grading Students</h1>

    <div class="content">
        <?php
        // Database connection details
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

        // Get requirement code from URL parameter
        if (isset($_GET['requirement_Code'])) {
            $requirementCode = $_GET['requirement_Code'];

            // Prepare SQL to fetch data from STUDENT_SUBMISSION and USER_INFORMATION tables
            $sql = "SELECT u.first_Name, u.middle_Name, u.last_Name, ss.submission_ID,
                        CASE
                            WHEN ss.date > sr.date_End OR (ss.date = sr.date_End AND ss.time > sr.time_End) THEN 'Late'
                            ELSE 'On-time'
                        END AS submission_status,
                        ss.file_path, 
                        ss.remarks, 
                        ss.note
                    FROM STUDENT_SUBMISSION ss
                    INNER JOIN USER_INFORMATION u ON ss.user_ID = u.user_ID
                    INNER JOIN SUBMISSION_REQUIREMENT sr ON ss.requirement_Code = sr.requirement_Code
                    WHERE ss.requirement_Code = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $requirementCode);
            $stmt->execute();
            $result = $stmt->get_result();

            // Table headers
            echo "<table>";
            echo "<tr>
                    <th class='student-name'>Student Name</th>
                    <th class='submission-status'>Submission Status</th>
                    <th class='file-submitted'>File Submitted</th>
                    <th class='remarks'>Remarks</th>
                    <th class='note'>Note</th>
                    <th class='action'>Action</th>
                </tr>";

            // Fetch and display data rows
            while ($row = $result->fetch_assoc()) {
                $studentName = $row['first_Name'] . " " . $row['middle_Name'] . " " . $row['last_Name'];
            
                echo "<tr>";
                echo "<td>" . htmlspecialchars($studentName) . "</td>";
                echo "<td>" . htmlspecialchars($row['submission_status']) . "</td>";
                echo "<td><a href='" . htmlspecialchars($row['file_path']) . "' target='_blank' download>Download File</a></td>";
                echo "<td class='remarks' data-submission-id='" . htmlspecialchars($row['submission_ID']) . "'>" . htmlspecialchars($row['remarks']) . "</td>";
                echo "<td class='note' data-submission-id='" . htmlspecialchars($row['submission_ID']) . "'>" . htmlspecialchars($row['note']) . "</td>";
                echo "<td><button class='edit-button' data-submission-id='" . htmlspecialchars($row['submission_ID']) . "'>Edit</button></td>";
                echo "</tr>";
            }
            
            echo "</table>";

            $stmt->close();
        } else {
            echo "No requirement code provided.";
        }

        $conn->close();
        ?>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Submission</h2>
            <label for="editRemarks">Remarks:</label>
            <input type="text" id="editRemarks" name="editRemarks">
            <label for="editNote">Note:</label>
            <input type="text" id="editNote" name="editNote">
            <button id="saveChanges">Save Changes</button>
        </div>
    </div>

    <script src="../../js/faculty/faculty_grading_students.js"></script>
</body>
</html>
