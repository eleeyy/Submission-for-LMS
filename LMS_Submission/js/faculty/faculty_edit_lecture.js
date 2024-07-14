window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('updated')) {
        alert('Activity updated successfully!');
        window.location.href = 'faculty_page_lectures.php';
    }
    
    if (urlParams.has('deleted')) {
        alert('Activity deleted successfully!');
        window.location.href = 'faculty_page_lectures.php';
    }
}