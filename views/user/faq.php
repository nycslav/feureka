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

    <section class="faq-page container">

        <div class="faq-hero">

            <h1>Frequently Asked Questions</h1>

            <p>

                Everything you need to know about using FEUreka.

            </p>

        </div>

        <div class="faq-list">

            <details class="faq-item" open>

                <summary>
                    How do I claim a found item?
                </summary>

                <p>

                    Visit the FEU Institute of Technology Lost and Found Office
                    and present proof of ownership.

                </p>

            </details>

            <details class="faq-item">

                <summary>
                    How do I report a missing item?
                </summary>

                <p>

                    Open the Report Missing Item page and submit the required
                    information.

                </p>

            </details>

            <details class="faq-item">

                <summary>
                    Who can use FEUreka?
                </summary>

                <p>

                    FEUreka is available to students, faculty,
                    and staff of FEU Institute of Technology.

                </p>

            </details>

            <details class="faq-item">

                <summary>
                    Can I claim someone else's item?
                </summary>

                <p>

                    No. Ownership verification is required before any item
                    can be released.

                </p>

            </details>

        </div>

    </section>

</main>

<?php require_once __DIR__ . '/../../includes/user-sidebar.php'; ?>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>