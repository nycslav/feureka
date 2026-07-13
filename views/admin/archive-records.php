<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure only admins can access this view
requireAdmin();

$archivedFoundItems = getArchivedFoundItems();
$archivedMissingReports = getArchivedMissingReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archive Records - FEUreka Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css">
</head>
<body>
    <!-- Replace with your actual admin sidebar/navbar inclusion -->
    <?php // include __DIR__ . '/../partials/admin_navbar.php'; ?>

    <div class="container mt-5">
        <div class="mb-4">
            <h2>Archive Records</h2>
            <p class="text-muted">A read-only history of all resolved and closed items.</p>
        </div>

        <!-- Section: Archived Found Items -->
        <h4 class="mt-5 mb-3">Archived Found Items</h4>
        <div class="card shadow-sm mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Date Found</th>
                                <th>Processed By</th>
                                <th>Admin Notes</th>
                                <th>Date Archived</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($archivedFoundItems)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No archived found items.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($archivedFoundItems as $item): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($item['item_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($item['category_name']) ?></td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($item['date_found']))) ?></td>
                                        <td>
                                            <?= $item['processed_by_first_name'] ? htmlspecialchars($item['processed_by_first_name'] . ' ' . $item['processed_by_last_name']) : '<span class="text-muted">Unknown</span>' ?>
                                        </td>
                                        <td>
                                            <?= $item['admin_notes'] ? htmlspecialchars($item['admin_notes']) : '<span class="text-muted">-</span>' ?>
                                        </td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($item['updated_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Section: Archived Missing Reports -->
        <h4 class="mb-3">Archived Missing Reports</h4>
        <div class="card shadow-sm mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item Name</th>
                                <th>Category</th>
                                <th>Date Lost</th>
                                <th>Reporter</th>
                                <th>Admin Notes</th>
                                <th>Date Archived</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($archivedMissingReports)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4">No archived missing reports.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($archivedMissingReports as $report): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($report['item_name']) ?></strong></td>
                                        <td><?= htmlspecialchars($report['category_name']) ?></td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($report['date_lost']))) ?></td>
                                        <td>
                                            <?= htmlspecialchars($report['reporter_first_name'] . ' ' . $report['reporter_last_name']) ?>
                                        </td>
                                        <td>
                                            <?= $report['admin_notes'] ? htmlspecialchars($report['admin_notes']) : '<span class="text-muted">-</span>' ?>
                                        </td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($report['updated_at']))) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Replace with your actual footer inclusion -->
    <?php // include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>