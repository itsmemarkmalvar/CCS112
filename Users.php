<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Account Dashboard</title>
    <link rel="stylesheet" href="DashboardStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body id="users-page"  data-page="users">
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
                <a href="LoginForm.html" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </nav>
        <main class="content">
            <header>
                <h1>Users</h1>
                <div class="user-info">
                    <span>Admin User</span>
                    <img src="https://via.placeholder.com/40" alt="Admin" class="avatar">
                </div>
            </header>
            <div class="card user-list">
                <h2>User List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Database connection
                        $host = "localhost";  // or your database host
                        $username = "root";   // your MySQL username
                        $password = "";       // your MySQL password
                        $database = "ccs112_login";  // your database name

                        $conn = new mysqli($host, $username, $password, $database);

                        // Check connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Fetch users from the database
                        $query = "SELECT ID, Name, Username, CreatedAt FROM accounts";
                        $result = $conn->query($query);

                        // Check if the query was successful
                        if (!$result) {
                            die("Database query failed.");
                        }

                        if ($result->num_rows > 0) {
                            while ($user = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr data-user-id="<?php echo $user['ID']; ?>">
                                    <td class="user-id"><?php echo htmlspecialchars($user['ID']); ?></td>
                                    <td class="user-name"><?php echo htmlspecialchars($user['Name']); ?></td>
                                    <td class="user-username"><?php echo htmlspecialchars($user['Username']); ?></td>
                                    <td class="user-created-at"><?php echo htmlspecialchars($user['CreatedAt']); ?></td>
                                    <td>
                                        <button onclick="loadUserDataForEdit(<?php echo $user['ID']; ?>)">Edit</button>
                                    </td>
                                </tr>
                                <?php
                            }   
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Add this modal form -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit User</h2>
                <span class="close">&times;</span>
            </div>
            <form id="editUserForm">
                <input type="hidden" id="userId" name="userId">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="createdAt">Created At:</label>
                    <input type="text" id="createdAt" name="createdAt" readonly>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="DashboardScript.js"></script>
    <script src="Users.js"></script>
</body>
</html>
