<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Dashboard</title>
    <link rel="stylesheet" href="DashboardStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body id="dashboard-page">
    <div class="dashboard">
        <nav class="sidebar">
            <div class="logo">
                <i class="fas fa-user-shield"></i>
                <span>Account Monitor</span>
            </div>
            <ul>
                <li><a href="Dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="Users.php"><i class="fas fa-users"></i> Users</a></li>
                <li><a href="Reports.html"><i class="fas fa-file-alt"></i> Reports</a></li>
                <li><a href="Settings.html"><i class="fas fa-cog"></i> Settings</a></li>
            </ul>
            <div class="logout-container">
                <a href="#" id="logout-btn" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
        <main class="content">
            <header>
                <h1>Dashboard</h1>
                <div class="user-info">
                    <span>Admin User</span>
                    <img src="https://via.placeholder.com/40" alt="Admin" class="avatar">
                </div>
            </header>
            <div class="user-stats">
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h3>Total Users</h3>
                    <p id="total-users">Loading...</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-plus"></i>
                    <h3>New Users (Today)</h3>
                    <p id="new-users">Loading...</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-user-clock"></i>
                    <h3>Active Users</h3>
                    <p id="active-users">Loading...</p>
                </div>
            </div>
            <div class="recent-activity">
                <h2>Recent Activity</h2>
                <table id="activity-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Action</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Activity rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </main>
      
    </div>
    <script src="DashboardScript.js"></script>
    <script src="LoginScript.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('fetch_dashboard_stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('total-users').textContent = data.totalUsers;
                    document.getElementById('new-users').textContent = data.newUsers;
                    document.getElementById('active-users').textContent = data.activeUsers;
                })
                .catch(error => {
                    console.error('Error fetching dashboard stats:', error);
                });

            const logoutBtn = document.getElementById('logout-btn');
            logoutBtn.addEventListener('click', function(event) {
                event.preventDefault();
                logout();
            });
        });
    </script>
</body>
</html>
