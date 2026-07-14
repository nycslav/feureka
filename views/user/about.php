<?php declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
// TODO:
// Uncomment after authentication integration.
// requireLogin();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>

<main>

    <section class="about-page container">

        <div class="about-hero">

            <h1>About FEUreka</h1>

            <p>
                FEUreka is a web-based Lost and Found Management System
                designed for the FEU Institute of Technology community.
            </p>

        </div>

        <section class="about-section">

            <h2>Our Mission</h2>

            <p>

                FEUreka aims to simplify the process of reporting,
                browsing, and recovering lost belongings by providing
                a centralized digital platform for students,
                faculty, and staff.

            </p>

        </section>

        <section class="about-section">

            <h2>What FEUreka Offers</h2>

            <div class="feature-grid">

                <div class="feature-card">

                    <span class="material-symbols-outlined">
                        inventory
                    </span>

                    <h3>Lost & Found Reporting</h3>

                    <p>

                        Submit found and missing item reports
                        quickly and securely.

                    </p>

                </div>

                <div class="feature-card">

                    <span class="material-symbols-outlined">
                        search
                    </span>

                    <h3>Easy Item Search</h3>

                    <p>

                        Browse approved found items using
                        categories and search.

                    </p>

                </div>

                <div class="feature-card">

                    <span class="material-symbols-outlined">
                        verified_user
                    </span>

                    <h3>Secure Verification</h3>

                    <p>

                        Item claims are verified by the
                        Lost and Found Office.

                    </p>

                </div>

            </div>

        </section>

    </section>

</main>

<?php require_once __DIR__ . '/../../includes/user-sidebar.php'; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>