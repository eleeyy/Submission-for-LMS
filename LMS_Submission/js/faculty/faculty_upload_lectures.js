// Function to show upload success message
function showUploadSuccess() {
    alert("Lecture uploaded successfully!");
    redirectToLecturesPage();
}

// Function to show error message for file type
function showErrorFileType() {
    alert("Sorry, only JPG, JPEG, PNG, GIF, PDF, PPTX, TXT, DOCX, and DOC files are allowed.");
    redirectToLecturesPage();
}

// Function to show error message for file size
function showErrorFileSize() {
    alert("Sorry, your file is too large. Maximum file size is 50 MB.");
    redirectToLecturesPage();
}