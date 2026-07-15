<?php declare(strict_types=1); 

/**
 * ---------------------------------------------------------
 * FEUreka - Lost and Found Management System
 * ---------------------------------------------------------
 * File: navbar.php
 * Module: User Interface & Public Pages
 *
 * Purpose:
 * Displays the top navigation bar used across all
 * public user pages.
 *
 * Owner:
 * Mark Jastin Andaya
 *
 * Last Updated:
 * 2026-07-13
 * ---------------------------------------------------------
 */
?>

<header class="navbar">

    <nav class="navbar-container">

        <!-- Left Section -->
        <div class="navbar-left">

            <button
                id="menu-toggle"
                class="navbar-menu-button"
                type="button"
                aria-label="Open Navigation">

                <span class="material-symbols-outlined">
                    menu
                </span>

            </button>

            <a href="home.php" class="navbar-logo">

                <img
                    src="../../assets/images/logo.png"
                    alt="FEUreka Logo"
                    class="navbar-logo-image">

            </a>

        </div>

        <!-- Right Section -->
        <div class="navbar-right">

            <!-- Guest Actions -->
            <div class="navbar-guest-actions">

                <!-- Reserved for Login button -->

            </div>

            <!-- Authenticated User Actions -->
            <div class="navbar-user-actions">

                <button
                    class="navbar-icon-button"
                    type="button"
                    aria-label="Notifications">

                    <span class="material-symbols-outlined">
                        notifications
                    </span>

                </button>

                <button
                    class="navbar-icon-button"
                    type="button"
                    aria-label="Profile">

                    <span class="material-symbols-outlined">
                        person
                    </span>

                </button>

            </div>

        </div>

    </nav>

</header>