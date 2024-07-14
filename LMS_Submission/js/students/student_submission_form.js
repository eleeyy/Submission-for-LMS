function showSubmitSuccess() {
    alert("Submitted successfully!");
    window.location.href = 'student_page_activity.php'; // Change the redirect URL as needed
}

function showErrorUpload() {
    alert("Sorry, only JPG, JPEG, PNG, GIF, PDF, PPTX, TXT, DOCX, and DOC files are allowed.");
    window.location.href = 'student_page_activity.php'; // Change the redirect URL as needed
}
