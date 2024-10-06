document.addEventListener('DOMContentLoaded', function() {
    // Determine which page we're on
    const currentPage = document.body.id;

    switch(currentPage) {
        case 'dashboard-page':
            initializeDashboard();
            break;
        case 'users-page':
            initializeUsersPage();
            break;
        // Add cases for other pages as needed
        default:
            console.log('Unknown page');
    }
});

function initializeDashboard() {
    // Dashboard-specific code
    fetchAndPopulateActivityTable();
    // Other dashboard initializations...
}

function initializeUsersPage() {
    // Users page-specific code
    // For example, initialize user table sorting or filtering
}

function fetchAndPopulateActivityTable() {
    fetch('get_recent_activity.php')
        .then(response => response.json())
        .then(data => {
            populateActivityTable(data);
        })
        .catch(error => {
            console.error('Error fetching activity data:', error);
        });
}

function populateActivityTable(activities) {
    const tableBody = document.querySelector('#activity-table tbody');
    
    if (!tableBody) {
        console.warn('Activity table body not found.');
        return;
    }

    if (activities.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="3">No recent activity</td></tr>';
        return;
    }

    tableBody.innerHTML = '';  // Clear existing rows

    activities.forEach(activity => {
        const row = `
            <tr>
                <td>${activity.user}</td>
                <td>${activity.action}</td>
                <td>${activity.timestamp}</td>
            </tr>
        `;
        tableBody.innerHTML += row;
    });
}

// Logout functionality
function logout() {
    fetch('/logout', {
        method: 'POST',
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text();  // Change this from response.json() to response.text()
    })
    .then(text => {
        try {
            // Try to parse the response as JSON
            const data = JSON.parse(text);
            // Handle successful logout
            window.location.href = '/login.php';  // Redirect to login page
        } catch (e) {
            // If it's not JSON, log the text and handle accordingly
            console.log('Received non-JSON response:', text);
            if (text.includes('logged out') || text.includes('login')) {
                // If the text suggests successful logout, redirect
                window.location.href = '/login.php';
            } else {
                throw new Error('Unexpected response from server');
            }
        }
    })
    .catch(error => {
        console.error('Logout error:', error);
        // Handle error (e.g., show error message to user)
    });
}

// Fetch and display recent activity
function fetchRecentActivity() {
    const activityTable = document.querySelector('#activity-table tbody');
    if (!activityTable) {
        console.warn('Activity table not found. This might be normal if not on the Dashboard page.');
        return;
    }

    fetch('get_recent_activity.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Debug info:', data);  // Log debug info
            populateActivityTable(data.activities);  // Pass only the activities array
        })
        .catch(error => {
            console.error('Error fetching activity data:', error);
            activityTable.innerHTML = '<tr><td colspan="3">Failed to load activity data. Please try again later.</td></tr>';
        });
}

// In your initialization code:
document.addEventListener('DOMContentLoaded', function() {
    if (document.body.id === 'dashboard-page') {
        fetchRecentActivity();
    }
    // Other initialization code...
});
