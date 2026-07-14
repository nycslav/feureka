<?php
declare(strict_types=1);

require_once __DIR__ . '/../../includes/functions.php';

$categories = getCategories();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Missing Item | FEUreka</title>
</head>

<body>

<main>

    <section>

        <h1>Report Missing Item</h1>

        <p>
            Lost something inside FEU Tech?
            Complete the form below to help the Lost and Found Office identify your item.
        </p>

        <form
            id="missingItemForm"
            action="../../actions/submit-missing-report.php"
            method="POST"
            enctype="multipart/form-data">

            <!-- ===================================================== -->
            <!-- Category -->
            <!-- ===================================================== -->

            <div>

                <label for="category_id">
                    Category
                </label>

                <select
                    id="category_id"
                    name="category_id"
                    required>

                    <option value="">
                        -- Select Category --
                    </option>

                    <?php foreach ($categories as $category): ?>

                        <option value="<?= htmlspecialchars((string) $category['category_id']) ?>">
                            <?= htmlspecialchars($category['category_name']) ?>
                        </option>

                    <?php endforeach; ?>

                </select>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Item Name -->
            <!-- ===================================================== -->

            <div>

                <label for="item_name">
                    Item Name
                </label>

                <input
                    type="text"
                    id="item_name"
                    name="item_name"
                    maxlength="150"
                    required>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Description -->
            <!-- ===================================================== -->

            <div>

                <label for="item_description">
                    Description
                </label>

                <textarea
                    id="item_description"
                    name="item_description"
                    rows="5"
                    required></textarea>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Room -->
            <!-- ===================================================== -->

            <div>

                <label for="room">
                    Room
                </label>

                <input
                    type="text"
                    id="room"
                    name="room"
                    maxlength="100"
                    placeholder="Example: Room 402"
                    required>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Floor -->
            <!-- ===================================================== -->

            <div>

                <label for="floor">
                    Floor
                </label>

                <input
                    type="text"
                    id="floor"
                    name="floor"
                    maxlength="50"
                    placeholder="Example: 4th Floor"
                    required>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Location Description -->
            <!-- ===================================================== -->

            <div>

                <label for="location_description">
                    Last Known Location
                </label>

                <textarea
                    id="location_description"
                    name="location_description"
                    rows="4"
                    placeholder="Describe where you last saw your item."
                    required></textarea>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Date Lost -->
            <!-- ===================================================== -->

            <div>

                <label for="date_lost">
                    Date Lost
                </label>

                <input
                    type="date"
                    id="date_lost"
                    name="date_lost"
                    required>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Contact Number -->
            <!-- ===================================================== -->

            <div>

                <label for="contact_number">
                    Contact Number
                </label>

                <input
                    type="text"
                    id="contact_number"
                    name="contact_number"
                    maxlength="30"
                    placeholder="0917-123-4567"
                    required>

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Image (Optional) -->
            <!-- ===================================================== -->

            <div>

                <label for="image">
                    Upload Image (Optional)
                </label>

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.gif,.webp">

                <small class="error-message"></small>

            </div>

            <!-- ===================================================== -->
            <!-- Buttons -->
            <!-- ===================================================== -->

            <div>

                <button type="submit">
                    Submit Report
                </button>

                <button type="reset">
                    Clear Form
                </button>

            </div>

        </form>

    </section>

</main>

<script src="../../assets/js/validation.js"></script>

</body>

</html>
