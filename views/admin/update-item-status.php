<?php
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdmin();

$error = '';

// 1. Handle Form Submission (if the admin clicked Save)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table = filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING);
    $recordId = filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_STRING);
    $notes = filter_input(INPUT_POST, 'admin_notes', FILTER_SANITIZE_STRING) ?: null;
    $adminId = $_SESSION['user_id'];

    if ($table && $recordId && $status) {
        // Call Lady's backend function
        if (updateItemStatus($table, $recordId, $status, $adminId, $notes)) {
            $_SESSION['success_message'] = 'Item status and notes successfully updated.';
            $redirect = $table === 'found_items' ? 'approved-found-items.php' : 'missing-item-reports.php';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Failed to update. Ensure you selected a valid status for this item type.';
        }
    } else {
        $error = 'Missing required fields.';
    }
}

// 2. Handle Page Load (grab the item details from the URL)
$table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING) ?? filter_input(INPUT_POST, 'table', FILTER_SANITIZE_STRING);
$recordId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_POST, 'record_id', FILTER_VALIDATE_INT);

// Boot them out if they didn't pass an ID
if (!$table || !$recordId) {
    $_SESSION['error_message'] = 'No record was selected for update.';
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Status - FEUreka Admin</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/admin.css"><!-- Add your CSS framework links here -->
</head>
<body>
    <!-- Replace with your actual admin sidebar/navbar inclusion -->
    <?php // include __DIR__ . '/../partials/admin_navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Update Record Status</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form action="update-item-status.php" method="POST">
                            <input type="hidden" name="table" value="<?= htmlspecialchars($table) ?>">
                            <input type="hidden" name="record_id" value="<?= htmlspecialchars((string)$recordId) ?>">

                            <div class="mb-3">
                                <label for="status" class="form-label">New Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="" disabled selected>Select a status...</option>
                                    
                                    <?php 
                                    // Lady's backend strictly separates statuses based on the table name.
                                    if ($table === 'found_items'): 
                                    ?>
                                        <option value="<?= STATUS_PENDING ?>">Pending</option>
                                        <option value="<?= STATUS_APPROVED ?>">Approved</option>
                                        <option value="<?= STATUS_UNDER_REVIEW ?>">Under Review</option>
                                        <option value="<?= STATUS_CLAIMED ?>">Claimed</option>
                                        <option value="<?= STATUS_REJECTED ?>">Rejected</option>
                                        <option value="<?= STATUS_ARCHIVED ?>">Archived</option>
                                    <?php elseif ($table === 'missing_reports'): ?>
                                        <option value="<?= STATUS_OPEN ?>">Open</option>
                                        <option value="<?= STATUS_POSSIBLE_MATCH ?>">Possible Match</option>
                                        <option value="<?= STATUS_RESOLVED ?>">Resolved</option>
                                        <option value="<?= STATUS_ARCHIVED ?>">Archived</option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4" placeholder="Enter details about this status change..."></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="dashboard.php" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save Updates</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Replace with your actual footer inclusion -->
    <?php // include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>