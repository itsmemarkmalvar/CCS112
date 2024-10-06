function loadUserDataForEdit(userId) {
    fetch(`get_user_data.php?id=${userId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('userId').value = data.ID;
            document.getElementById('name').value = data.Name;
            document.getElementById('username').value = data.Username;
            document.getElementById('createdAt').value = data.CreatedAt;
            
            // Show the modal
            document.getElementById('editUserModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while loading user data');
        });
}
   // Add this JavaScript code
   document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editUserModal');
    const span = document.getElementsByClassName('close')[0];
    const editButtons = document.querySelectorAll('.btn-edit');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-id');
            fetchUserData(userId);
        });
    });

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    function fetchUserData(userId) {
        fetch(`get_user_data.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('userId').value = data.ID;
                document.getElementById('name').value = data.Name;
                document.getElementById('username').value = data.Username;
                document.getElementById('createdAt').value = data.CreatedAt;
                modal.style.display = "block";
            })
            .catch(error => console.error('Error:', error));
    }

    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Here you can add code to submit the form data to update the user information
        console.log('Form submitted');
        modal.style.display = "none";
    });

    const cancelButton = document.getElementById('cancelEdit');
    cancelButton.addEventListener('click', function() {
        modal.style.display = "none";
    });

    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        e.preventDefault();
        // Here you can add code to submit the form data to update the user information
        console.log('Form submitted');
        modal.style.display = "none";
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Determine which page we're on
    const currentPage = document.body.dataset.page || '';

    // Common functions
    function initializeCommonElements() {
        // Initialize elements common to all pages
    }

    // Page-specific functions
    function initializeUsersPage() {
        const editUserForm = document.getElementById('editUserForm');
        if (editUserForm) {
            editUserForm.addEventListener('submit', function(e) {
                e.preventDefault();
                updateUser();
            });
        }
    }

    function initializeReportsPage() {
        // Initialize reports page specific elements
    }

    // Run common initializations
    initializeCommonElements();

    // Run page-specific initializations
    switch (currentPage) {
        case 'users':
            initializeUsersPage();
            break;
        case 'reports':
            initializeReportsPage();
            break;
        // Add more cases as needed
    }
});

function updateUser() {
    const formData = new FormData(document.getElementById('editUserForm'));
    
    fetch('update_user.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.status === 'success') {
            alert('User updated successfully');
            closeModal();
            updateUserInList(formData.get('userId'), formData.get('name'), formData.get('username'));
        } else {
            if (data.message === 'Username already exists') {
                alert('Error: This username is already taken. Please choose a different username.');
            } else {
                alert('Error updating user: ' + data.message);
            }   
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the user');
    });
}

function closeModal() {
    document.getElementById('editUserModal').style.display = 'none';
}

function updateUserInList(userId, name, username) {
    const userRow = document.querySelector(`tr[data-user-id="${userId}"]`);
    if (userRow) {
        userRow.querySelector('.user-name').textContent = name;
        userRow.querySelector('.user-username').textContent = username;
        // Note: We don't update the ID or CreatedAt as these shouldn't change
    }
}

function generateUserActivityReport() {
    fetch('generate_user_activity_report.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status === 'success') {
                displayUserActivityReport(data.data);
            } else {
                throw new Error(data.message || 'Unknown error occurred');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while generating the report: ' + error.message);
        });
}

function displayUserActivityReport(activities) {
    const reportContainer = document.getElementById('reportContainer');
    if (!reportContainer) {
        console.error('Report container not found');
        return;
    }

    let html = '<h2>User Activity Report</h2>';
    if (activities.length === 0) {
        html += '<p>No user activity found.</p>';
    } else {
        html += '<table class="activity-report">';
        html += '<thead><tr><th>Username</th><th>Activity Date</th><th>Action</th></tr></thead><tbody>';
        activities.forEach(activity => {
            html += `<tr>
                <td>${escapeHtml(activity.username)}</td>
                <td>${escapeHtml(activity.activityDate)}</td>
                <td>${escapeHtml(activity.action)}</td>
            </tr>`;
        });
        html += '</tbody></table>';
    }

    reportContainer.innerHTML = html;
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

