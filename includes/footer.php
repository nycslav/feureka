<?php declare(strict_types=1);

/**
 * ---------------------------------------------------------
 * FEUreka - Lost and Found Management System
 * ---------------------------------------------------------
 * File: footer.php
 * Module: User Interface & Public Pages
 *
 * Purpose:
 * Displays the global footer.
 * ---------------------------------------------------------
 */
?>

<footer class="footer">

    <div class="container footer-container">

        <div class="footer-brand">

            <img
                src="../../assets/images/logo.png"
                alt="FEUreka Logo"
                class="footer-logo">

            <p class="footer-description">
                Connecting lost items with their owners within the FEU Tech campus.
            </p>

        </div>

        <div class="footer-copyright">

            © <?= date('Y') ?> FEUreka Lost and Found Management System

        </div>

    </div>

</footer>

<script src="../../assets/js/modal.js"></script>

<script src="../../assets/js/sidebar.js"></script>
</body>
</html>