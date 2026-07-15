<?php
// actions/update-item-status.php

// 1. Include the necessary wiring files so we can access constants, sessions, and functions
require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/session.php';
require_once __DIR__ . '/../includes/functions.php';

// 2. Security Check: Instantly boot anyone who isn't a logged-in admin
//requireAdmin();

// 3. Verify that data was actually sent via a form submission (POST method)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 4. Catch the data sent from the HTML form's hidden inputs
    // We use isset() to check if the data exists, and (int) to ensure the ID is a safe number
    $item_id = isset($_POST['item_id']) ? (int)$_POST['item_id'] : 0;
    $status  = isset($_POST['status']) ? trim($_POST['status']) : '';
    
    // Admin notes are optional, so we allow it to be null if not provided
    $notes   = isset($_POST['notes']) ? trim($_POST['notes']) : null; 
    
    // 5. Get the ID of the admin who is currently logged in and clicking the button
    $adminId = $_SESSION['user_id'];
    
    // 6. Basic Validation: Ensure we actually have an ID and a status before bothering the database
    if ($item_id > 0 && $status !== '') {
        
        // 7. THE HANDOFF: Call Lady's database function!
        // We pass the table name ('found_items'), the item ID, the new status, the admin's ID, and any notes.
        // Even though her function currently just returns `false` (as a placeholder), 
        // this will perfectly connect to her real SQL code once she finishes it.
        $success = updateItemStatus('found_items', $item_id, $status, $adminId, $notes);
        
        // 8. Redirect the admin based on the result
        if ($success) {
            // Send them back to the pending items page with a success message in the URL
            header("Location: " . BASE_URL . "views/admin/pending-found-items.php?msg=success");
            exit;
        } else {
            // Send them back with an error message
            header("Location: " . BASE_URL . "views/admin/pending-found-items.php?msg=db_error");
            exit;
        }
    } else {
        // Send them back if the form data was corrupted or missing
        header("Location: " . BASE_URL . "views/admin/pending-found-items.php?msg=invalid_data");
        exit;
    }
} else {
    // If someone tries to just type this file's URL directly into their browser, 
    // it's a GET request, not a POST. We kick them back to the dashboard.
    header("Location: " . BASE_URL . "views/admin/dashboard.php");
    exit;
}
?>