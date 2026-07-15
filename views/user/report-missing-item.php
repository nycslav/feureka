<?php
    declare(strict_types=1);

    require_once '../../config/database.php';
    require_once '../../includes/functions.php';

    $pageTitle = 'Report Missing Item | FEUreka';

    require_once __DIR__ . '/../../includes/header.php';
    require_once __DIR__ . '/../../includes/navbar.php';
    require_once __DIR__ . '/../../includes/user-sidebar.php';

    /*
    |--------------------------------------------------------------------------
    | Load Categories
    |--------------------------------------------------------------------------
    */

$categories = getCategories();
?>

<main>

    <section class="report-section">

        <div class="container">

            <div class="report-header">

                <h1 class="page-title">

                    Report Missing Item

                </h1>

                <p class="page-description">

                    Lost something inside FEU Tech?

                    Complete the form below to help the Lost and Found Office identify your missing item.

                </p>

            </div>

            <?php if (isset($_SESSION['success'])): ?>

                <div class="auth-message success">

                    <?= htmlspecialchars((string) $_SESSION['success']) ?>

                </div>

                <?php unset($_SESSION['success']); ?>

            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>

                <div class="auth-message error">

                    <?= htmlspecialchars((string) $_SESSION['error']) ?>

                </div>

                <?php unset($_SESSION['error']); ?>

            <?php endif; ?>

            <div class="report-card">

                <form
                    id="missingItemForm"
                    action="../../actions/submit-missing-report.php"
                    method="POST"
                    enctype="multipart/form-data">

                    <!-- ====================================================== -->
                    <!-- BASIC INFORMATION -->
                    <!-- ====================================================== -->

                    <div class="report-group">

                        <h2 class="report-group-title">

                            Basic Information

                        </h2>

                        <div class="report-grid">

                            <div class="form-group">

                                <label for="category_id">

                                    Category

                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">

                                        category

                                    </span>

                                    <select
                                        id="category_id"
                                        name="category_id"
                                        required>

                                        <option value="">
                                            -- Select Category --
                                        </option>

                                        <?php foreach ($categories as $category): ?>

                                            <option value="<?= htmlspecialchars((string)$category['category_id']) ?>">

                                                <?= htmlspecialchars($category['category_name']) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                    <span class="material-symbols-outlined select-arrow">

                                        expand_more

                                    </span>

                                </div>

                                <small class="error-message"></small>

                            </div>

                            <div class="form-group">

                                <label for="date_lost">

                                    Date Lost

                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">

                                        calendar_today

                                    </span>

                                    <input
                                        type="date"
                                        id="date_lost"
                                        name="date_lost"
                                        required>

                                </div>

                                <small class="error-message"></small>

                            </div>

                        </div>

                        <div class="form-group">

                            <label for="item_name">

                                Item Name

                            </label>

                            <div class="input-wrapper">

                                <span class="material-symbols-outlined input-icon">

                                    inventory_2

                                </span>

                                <input
                                    type="text"
                                    id="item_name"
                                    name="item_name"
                                    maxlength="150"
                                    placeholder="Enter the item name"
                                    required>

                            </div>

                            <small class="error-message"></small>

                        </div>

                        <div class="form-group">

                            <label for="item_description">

                                Description

                            </label>

                            <textarea
                                id="item_description"
                                name="item_description"
                                rows="4"
                                placeholder="Describe the appearance, color, brand, and other identifying details."
                                required></textarea>

                            <small class="error-message"></small>

                        </div>

                    </div>

                    <!-- ====================================================== -->
                    <!-- LOCATION -->
                    <!-- ====================================================== -->

                    <div class="report-group">

                        <h2 class="report-group-title">

                            Last Known Location

                        </h2>

                        <div class="report-grid">

                            <div class="form-group">

                                <label for="room">

                                    Room

                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">

                                        meeting_room

                                    </span>

                                    <input
                                        type="text"
                                        id="room"
                                        name="room"
                                        maxlength="100"
                                        placeholder="Example: Room 402"
                                        required>

                                </div>

                                <small class="error-message"></small>

                            </div>

                            <div class="form-group">

                                <label for="floor">

                                    Floor

                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">

                                        apartment

                                    </span>

                                    <input
                                        type="text"
                                        id="floor"
                                        name="floor"
                                        maxlength="50"
                                        placeholder="Example: 4th Floor"
                                        required>

                                </div>

                                <small class="error-message"></small>

                            </div>

                        </div>

                        <div class="form-group">

                            <label for="location_description">

                                Last Known Location

                            </label>

                            <div class="input-wrapper textarea-wrapper">

                                <span class="material-symbols-outlined input-icon">

                                    location_on

                                </span>

                                <textarea
                                    id="location_description"
                                    name="location_description"
                                    rows="3"
                                    placeholder="Describe where you last saw your item."
                                    required></textarea>

                            </div>

                            <small class="error-message"></small>

                        </div>

                        <div class="form-group">

                            <label for="contact_number">

                                Contact Number

                            </label>

                            <div class="input-wrapper">

                                <span class="material-symbols-outlined input-icon">

                                    call

                                </span>

                                <input
                                    type="text"
                                    id="contact_number"
                                    name="contact_number"
                                    maxlength="30"
                                    placeholder="0917-123-4567"
                                    required>

                            </div>

                            <small class="error-message"></small>

                        </div>

                    </div>

                    <!-- ====================================================== -->
                    <!-- EVIDENCE -->
                    <!-- ====================================================== -->
                                         <div class="report-group">

                        <h2 class="report-group-title">

                            Supporting Photo

                        </h2>

                        <div class="form-group">

                            <label
                                class="upload-area"
                                for="image"
                                id="uploadArea">

                                <img
                                    id="imagePreview"
                                    class="upload-preview"
                                    hidden
                                    alt="Preview">

                                <div id="uploadContent">

                                    <span class="material-symbols-outlined upload-icon">

                                        cloud_upload

                                    </span>

                                    <span class="upload-title">

                                        Upload Supporting Photo

                                    </span>

                                    <span class="upload-subtitle">

                                        Optional — Click to browse your device

                                    </span>

                                    <span class="upload-format">

                                        JPG • JPEG • PNG • GIF • WEBP

                                    </span>

                                </div>

                                <div
                                    id="replaceOverlay"
                                    class="replace-overlay"
                                    hidden>

                                    <span class="material-symbols-outlined">

                                        cached

                                    </span>

                                    <span>

                                        Change Image

                                    </span>

                                </div>

                            </label>

                            <input
                                type="file"
                                id="image"
                                name="image"
                                accept=".jpg,.jpeg,.png,.gif,.webp"
                                hidden>

                            <small class="error-message"></small>

                        </div>

                    </div>

                    <div class="report-actions">

                        <button
                            class="auth-button"
                            type="submit">

                            Submit Report

                        </button>

                        <button
                            class="report-secondary-button"
                            type="reset">

                            Clear Form

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>

</main>

<script>

const imageInput = document.getElementById('image');

const preview = document.getElementById('imagePreview');

const uploadContent = document.getElementById('uploadContent');

const overlay = document.getElementById('replaceOverlay');

if (imageInput) {

    imageInput.addEventListener('change', function () {

        if (!this.files.length) return;

        const file = this.files[0];

        const reader = new FileReader();

        reader.onload = ({ target }) => {

            preview.src = target.result;

            preview.hidden = false;

            uploadContent.hidden = true;

            overlay.hidden = false;

        };

        reader.readAsDataURL(file);

    });

}

</script>

<script src="../../assets/js/validation.js"></script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
