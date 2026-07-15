<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/admin-navbar.php';


requireAdmin();

$counts = getDashboardCounts();
$users = getUsers();
$totalUsers = count($users);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FEUreka</title>
    <!-- Google Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>

    <div class="admin-layout">
        
        <!-- SIDEBAR -->
                <!-- THE UNIFORM SIDEBAR -->
        <?php 
            // Pure PHP way to get the current file name (e.g., "user-management.php")
            $currentPage = basename($_SERVER['PHP_SELF']); 
        ?>
        <?php require_once __DIR__ . '/../../includes/admin-sidebar.php'; ?>
        <!-- MAIN CONTENT -->
        <main class="admin-content">
            <div class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Welcome back! System overview and active reports.</p>
            </div>

            <!-- UNIFORM FLASHCARDS GRID -->
            <div class="dashboard-grid">
                
                <!-- 1. Pending -->
                <a href="pending-found-items.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">hourglass_empty</span>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['pending_found_items']) ?></div>
                    <div class="stat-label">Pending Items</div>
                </a>

                <!-- 2. Approved -->
                <a href="approved-found-items.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">check_circle</span>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['approved_found_items']) ?></div>
                    <div class="stat-label">Active Items</div>
                </a>

                <!-- 3. Missing -->
                <a href="missing-item-reports.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">search</span>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['missing_reports']) ?></div>
                    <div class="stat-label">Missing Reports</div>
                </a>

                <!-- 4. Update Status (Links to Approved Items list to pick an item) -->
                <a href="approved-found-items.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">edit_square</span>
                    <div class="stat-number">-</div>
                    <div class="stat-label">Update Status</div>
                </a>

                <!-- 5. Archive -->
                <a href="archive-records.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">inventory_2</span>
                    <div class="stat-number"><?= htmlspecialchars((string)$counts['archived_records']) ?></div>
                    <div class="stat-label">Archived Records</div>
                </a>

                <!-- 6. Users -->
                <a href="user-management.php" class="stat-card">
                    <span class="material-symbols-outlined stat-icon">group</span>
                    <div class="stat-number"><?= htmlspecialchars((string)$totalUsers) ?></div>
                    <div class="stat-label">Total Users</div>
                </a>

            </div>
        </main>
    </div>

</body>
</html>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>