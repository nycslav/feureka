<?php declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
// TODO:
// Uncomment after authentication integration.
// requireLogin();

require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
?>
<?php require_once __DIR__ . '/../../includes/user-sidebar.php'; ?>

<main>

    <?php require_once __DIR__ . '/../components/hero.php'; ?>

    <?php require_once __DIR__ . '/../components/search-bar.php'; ?>

    <?php require_once __DIR__ . '/../components/category-filter.php'; ?>

    <?php

    $items = getApprovedItems();

    ?>

    <section class="items-section">

        <div class="container">

            <h2 class="section-title">

                Recently Found Items

            </h2>

            <div class="items-grid">

                <?php foreach ($items as $item): ?>

                    <?php require __DIR__ . '/../components/found-item-card.php'; ?>

                    <?php require __DIR__ . '/../components/item-details-modal.php'; ?>

                <?php endforeach; ?>

            </div>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>