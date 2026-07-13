<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

// Ensure only admins can access this view
requireAdmin();

$missingReports = getMissingReports();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Missing Item Reports - FEUreka Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css"><!-- Add your CSS framework links here -->
</head>
<body>
    <!-- Replace with your actual admin sidebar/navbar inclusion -->
    <?php // include __DIR__ . '/../partials/admin_navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Missing Item Reports</h2>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Date Lost</th>
                                <th>Location (Room/Floor)</th>
                                <th>Reporter</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($missingReports)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">No active missing item reports found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($missingReports as $report): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($report['item_name']) ?></strong>
                                            <?php if ($report['image']): ?>
                                                <br><small><a href="../../uploads/<?= htmlspecialchars($report['image']) ?>" target="_blank">View Image</a></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($report['category_name']) ?></td>
                                        <td><?= htmlspecialchars(date('M d, Y', strtotime($report['date_lost']))) ?></td>
                                        <td>
                                            <?= htmlspecialchars($report['room'] . ' / ' . $report['floor']) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($report['reporter_first_name'] . ' ' . $report['reporter_last_name']) ?><br>
                                            <small class="text-muted"><?= htmlspecialchars($report['contact_number']) ?></small>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($report['status'] === STATUS_OPEN) $badgeClass = 'bg-warning text-dark';
                                            if ($report['status'] === STATUS_POSSIBLE_MATCH) $badgeClass = 'bg-info text-dark';
                                            if ($report['status'] === STATUS_RESOLVED) $badgeClass = 'bg-success';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(strtoupper($report['status'])) ?></span>
                                        </td>
                                        <td>
                                            <!-- The action form for updating statuses -->
                                            <form action="../../actions/update-status.php" method="POST" class="d-inline">
                                                <input type="hidden" name="table" value="missing_reports">
                                                <input type="hidden" name="record_id" value="<?= htmlspecialchars((string)$report['report_id']) ?>">
                                                
                                                <select name="status" class="form-select form-select-sm d-inline-block w-auto mb-1" onchange="this.form.submit()">
                                                    <option value="" disabled selected>Update Status</option>
                                                    <option value="<?= STATUS_OPEN ?>">Open</option>
                                                    <option value="<?= STATUS_POSSIBLE_MATCH ?>">Possible Match</option>
                                                    <option value="<?= STATUS_RESOLVED ?>">Resolved</option>
                                                    <option value="<?= STATUS_ARCHIVED ?>">Archive</option>
                                                </select>
                                            </form>
                                        </td>
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