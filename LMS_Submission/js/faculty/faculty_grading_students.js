document.addEventListener('DOMContentLoaded', function() {
    let modal = document.getElementById('editModal');
    let span = document.getElementsByClassName('close')[0];
    let saveButton = document.getElementById('saveChanges');
    let currentSubmissionID = null;

    let editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            currentSubmissionID = this.getAttribute('data-submission-id');
            let remarks = document.querySelector('.remarks[data-submission-id="' + currentSubmissionID + '"]').innerText;
            let note = document.querySelector('.note[data-submission-id="' + currentSubmissionID + '"]').innerText;

            document.getElementById('editRemarks').value = remarks;
            document.getElementById('editNote').value = note;
            modal.style.display = 'block';
        });
    });

    span.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    saveButton.onclick = function() {
        let newRemarks = document.getElementById('editRemarks').value;
        let newNote = document.getElementById('editNote').value;

        if (currentSubmissionID) {
            updateSubmission(currentSubmissionID, newRemarks, newNote);
            modal.style.display = 'none';
        }
    }

    function updateSubmission(submissionID, newRemarks, newNote) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_note_remarks.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText);
                if (newRemarks !== null) {
                    document.querySelector('.remarks[data-submission-id="' + submissionID + '"]').innerText = newRemarks;
                }
                if (newNote !== null) {
                    document.querySelector('.note[data-submission-id="' + submissionID + '"]').innerText = newNote;
                }
            }
        };
        let data = 'submission_id=' + submissionID;
        if (newRemarks !== null) {
            data += '&remarks=' + encodeURIComponent(newRemarks);
        }
        if (newNote !== null) {
            data += '&note=' + encodeURIComponent(newNote);
        }
        xhr.send(data);
    }
});
