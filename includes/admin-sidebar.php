<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Admin Sidebar
 * ---------------------------------------------------------
 * Styled to match the user sidebar structure and behavior.
 * ---------------------------------------------------------
 */

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar-overlay" id="sidebar-overlay">
    

    <aside class="sidebar" id="sidebar">
        <img
            src="../../assets/images/logo.png"
            alt="FEUreka Logo"
            class="sidebar-logo">
        <div class="sidebar-header" style="background-color: #08110d; border: 1px solid rgba(247, 197, 77, 0.2); padding: 15px; border-radius: 15px; margin: 15px;">
            <h2 style="color: #F7C54D; text-align: center; margin: 0; font-size: 1.25rem;">Admin Panel</h2>
        </div>

        <nav class="sidebar-menu">
            <a href="dashboard.php" class="sidebar-item <?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">dashboard</span> Dashboard
            </a>
            <a href="pending-found-items.php" class="sidebar-item <?= $currentPage === 'pending-found-items.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">pending_actions</span> Pending Found
            </a>
            <a href="approved-found-items.php" class="sidebar-item <?= $currentPage === 'approved-found-items.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">check_circle</span> Active Found
            </a>
            <a href="missing-item-reports.php" class="sidebar-item <?= $currentPage === 'missing-item-reports.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">report_problem</span> Missing Reports
            </a>
            <a href="archive-records.php" class="sidebar-item <?= $currentPage === 'archive-records.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">archive</span> Archive Records
            </a>
            <a href="user-management.php" class="sidebar-item <?= $currentPage === 'user-management.php' ? 'active' : '' ?>">
                <span class="material-symbols-outlined">group</span> User Management
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="../../actions/logout-actions.php" class="sidebar-item logout">
                <span class="material-symbols-outlined">logout</span>
                Log Out
            </a>
        </div>
    </aside>
</div>