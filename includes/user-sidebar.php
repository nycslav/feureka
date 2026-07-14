<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - User Sidebar
 * ---------------------------------------------------------
 * Displays the user navigation drawer.
 * Automatically highlights the active page.
 * ---------------------------------------------------------
 */

$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar-overlay" id="sidebar-overlay">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-header">

            <img
                src="../../assets/images/logo.png"
                alt="FEUreka Logo"
                class="sidebar-logo">

        </div>

        <nav class="sidebar-menu">

            <a
                href="home.php"
                class="sidebar-item <?= $currentPage === 'home.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    home
                </span>

                Home

            </a>

            <a
                href="report-found-item.php"
                class="sidebar-item <?= $currentPage === 'report-found-item.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    add_box
                </span>

                Report Found Item

            </a>

            <a
                href="report-missing-item.php"
                class="sidebar-item <?= $currentPage === 'report-missing-item.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    search
                </span>

                Report Missing Item

            </a>

            <a
                href="profile.php"
                class="sidebar-item <?= $currentPage === 'profile.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    person
                </span>

                Profile

            </a>

            <a
                href="about.php"
                class="sidebar-item <?= $currentPage === 'about.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    info
                </span>

                About

            </a>

            <a
                href="faq.php"
                class="sidebar-item <?= $currentPage === 'faq.php' ? 'active' : '' ?>">

                <span class="material-symbols-outlined">
                    help
                </span>

                FAQ

            </a>

        </nav>

        <div class="sidebar-footer">

            <a
                href="../auth/logout.php"
                class="sidebar-item logout">

                <span class="material-symbols-outlined">
                    logout
                </span>

                Logout

            </a>

        </div>

    </aside>

</div>