<?php
declare(strict_types=1);

require_once '../../config/database.php';
require_once '../../includes/functions.php';

/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
*/

$categories = [];

$result = $conn->query("
    SELECT category_id, category_name
    FROM categories
    ORDER BY category_name ASC
");

if ($result) {
    $categories = $result->fetch_all(MYSQLI_ASSOC);
}

$pageTitle = 'Report Found Item | FEUreka';

require_once __DIR__ . '/../../includes/header.php';
require_once __DIR__ . '/../../includes/navbar.php';
require_once __DIR__ . '/../../includes/user-sidebar.php';
?>

<main>

    <section class="report-section">

        <div class="container">

            <div class="report-header">

                <h1 class="page-title">

                    Report Found Item

                </h1>

                <p class="page-description">

                    Found an item inside FEU Tech?

                    Complete the form below and submit it for administrator review.

                </p>

            </div>

            <div class="report-card">

                <form
                    id="foundItemForm"
                    action="../../actions/submit-found-item.php"
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

                                            <option value="<?= htmlspecialchars($category['category_id']) ?>">

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

                                <label for="date_found">

                                    Date Found

                                </label>

                                <div class="input-wrapper">

                                    <span class="material-symbols-outlined input-icon">

                                        calendar_today

                                    </span>

                                    <input
                                        type="date"
                                        id="date_found"
                                        name="date_found"
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
                                placeholder="Describe the appearance, color, brand, and other details."
                                required></textarea>

                            <small class="error-message"></small>

                        </div>

                    </div>

                    <!-- ====================================================== -->
                    <!-- LOCATION -->
                    <!-- ====================================================== -->

                    <div class="report-group">

                        <h2 class="report-group-title">

                            Location

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
                                        placeholder="Example: 4th Floor"
                                        required>

                                </div>

                                <small class="error-message"></small>

                            </div>

                        </div>

                        <div class="form-group">

                            <label for="location_description">

                                Specific Location

                            </label>

                            <div class="input-wrapper textarea-wrapper">

                                <span class="material-symbols-outlined input-icon">

                                    location_on

                                </span>

                                <textarea
                                    id="location_description"
                                    name="location_description"
                                    rows="3"
                                    placeholder="Describe exactly where the item was found."
                                    required></textarea>

                            </div>

                            <small class="error-message"></small>

                        </div>

                    </div>

                    <!-- ====================================================== -->
                    <!-- EVIDENCE -->
                    <!-- ====================================================== -->
                                         <div class="report-group">

                        <h2 class="report-group-title">

                            Evidence

                        </h2>

                        <div class="form-group">

                            <label for="image">

                                Upload Image

                            </label>

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

                                        Upload Item Photo

                                    </span>

                                    <span class="upload-subtitle">

                                        Click to browse your device

                                    </span>

                                    <span class="upload-format">

                                        JPG • JPEG • PNG • WEBP

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
                                accept=".jpg,.jpeg,.png,.webp"
                                required
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

</script>

<script src="../../assets/js/validation.js"></script>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>