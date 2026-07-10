<?php
// views/admin/dashboard.php

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../includes/functions.php';

// 1. The Bouncer: Instantly boots anyone who isn't an admin
requireAdmin();

// 2. Fetch the statistics
$stats = getDashboardCounts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - FEUreka</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <div class="admin-layout">
        
        <?php include __DIR__ . '/../../includes/admin-sidebar.php'; ?>

        <main class="admin-content">
            <header class="admin-header">
                <h1>Admin Dashboard</h1>
                <p>Welcome back. Here is the current system overview.</p>
            </header>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <h3>Pending Found Items</h3>
                    <p class="stat-number"><?php echo $stats['pending_found_items']; ?></p>
                    <a href="pending-found-items.php" class="btn btn-primary">Review Pending</a>
                </div>

                <div class="stat-card">
                    <h3>Approved Items</h3>
                    <p class="stat-number"><?php echo $stats['approved_found_items']; ?></p>
                    <a href="approved-found-items.php" class="btn btn-secondary">View Public Feed</a>
                </div>

                <div class="stat-card">
                    <h3>Missing Reports</h3>
                    <p class="stat-number"><?php echo $stats['missing_reports']; ?></p>
                    <a href="missing-item-reports.php" class="btn btn-secondary">Review Missing</a>
                </div>

                <div class="stat-card">
                    <h3>Archived Records</h3>
                    <p class="stat-number"><?php echo $stats['archived_records']; ?></p>
                    <a href="archive-records.php" class="btn btn-secondary">View Archives</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>