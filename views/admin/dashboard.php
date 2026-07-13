<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

// Fetch the statistics from the database
$counts = getDashboardCounts();

// Fetch all users to get the total account count for the new User Management card
$users = getUsers();
$totalUsers = count($users);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FEUreka</title>
    <!-- Link to your custom CSS -->
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

    <div class="admin-layout">
        
        <!-- SIDEBAR NAVIGATION -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h2>FEUreka Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="pending-found-items.php">Pending Found Items</a></li>
                    <li><a href="approved-found-items.php">Approved Found Items</a></li>
                    <li><a href="missing-item-reports.php">Missing Item Reports</a></li>
                    <li><a href="archive-records.php">Archive Records</a></li>
                    <li><a href="user-management.php">User Management</a></li>
                </ul>
            </nav>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Welcome back! Here is an overview of the FEUreka system.</p>
            </div>

            <!-- THE 6-PAGE GRID SYSTEM -->
            <div class="dashboard-grid">
                
                <!-- 1. Pending Found Items -->
                <a href="pending-found-items.php" class="stat-card">
                    <h3>Pending Items</h3>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['pending_found_items']) ?></div>
                    <span class="btn btn-secondary">Review Needed</span>
                </a>

                <!-- 2. Approved Found Items -->
                <a href="approved-found-items.php" class="stat-card">
                    <h3>Approved Items</h3>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['approved_found_items']) ?></div>
                    <span class="btn btn-primary">View Inventory</span>
                </a>

                <!-- 3. Missing Item Reports -->
                <a href="missing-item-reports.php" class="stat-card">
                    <h3>Missing Reports</h3>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['missing_reports']) ?></div>
                    <span class="btn btn-secondary">Manage Reports</span>
                </a>

                <!-- 4. Update Item Status (Tool) -->
                <a href="#" class="stat-card" onclick="alert('To update a status, please navigate to a specific item in the Approved or Missing lists first!'); return false;">
                    <h3>Update Status</h3>
                    <div class="stat-number">⚙️</div>
                    <span class="btn btn-primary">Action Tool</span>
                </a>

                <!-- 5. Archive Records -->
                <a href="archive-records.php" class="stat-card">
                    <h3>Archive Records</h3>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['archived_records']) ?></div>
                    <span class="btn btn-secondary">View History</span>
                </a>

                <!-- 6. User Management -->
                <a href="user-management.php" class="stat-card">
                    <h3>User Accounts</h3>
                    <div class="stat-number"><?= htmlspecialchars((string)$totalUsers) ?></div>
                    <span class="btn btn-primary">Manage Users</span>
                </a>

            </div>
        </main>
    </div>

</body>
</html>