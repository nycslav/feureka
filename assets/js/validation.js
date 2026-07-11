/*
|--------------------------------------------------------------------------
| FEUreka - Reporting Form Validation
|--------------------------------------------------------------------------
|
| Used by:
| - report-found-item.php
| - report-missing-item.php (future)
|
*/

document.addEventListener("DOMContentLoaded", () => {

    const foundItemForm = document.getElementById("foundItemForm");

    if (foundItemForm) {

        initializeFoundItemValidation(foundItemForm);

    }

});


/* ==========================================================
   INITIALIZE
========================================================== */

function initializeFoundItemValidation(form) {

    form.addEventListener("submit", function (event) {

        if (!validateFoundItemForm()) {

            event.preventDefault();

        }

    });

    attachLiveValidation();

}


/* ==========================================================
   FORM VALIDATION
========================================================== */

function validateFoundItemForm() {

    let valid = true;

    clearAllErrors();

    valid = validateRequired("category_id", "Please select a category.") && valid;

    valid = validateRequired("item_name", "Item name is required.") && valid;

    valid = validateRequired("item_description", "Description is required.") && valid;

    valid = validateRequired("room", "Room is required.") && valid;

    valid = validateRequired("floor", "Floor is required.") && valid;

    valid = validateRequired(
        "location_description",
        "Location description is required."
    ) && valid;

    valid = validateDate("date_found") && valid;

    valid = validateImage("image") && valid;

    return valid;

}


/* ==========================================================
   LIVE VALIDATION
========================================================== */

function attachLiveValidation() {

    document.querySelectorAll(
        "#foundItemForm input, #foundItemForm textarea, #foundItemForm select"
    ).forEach(input => {

        const eventType =
            input.type === "file" ||
            input.tagName === "SELECT"
                ? "change"
                : "input";

        input.addEventListener(eventType, () => {

            clearError(input);

        });

    });

}


/* ==========================================================
   REQUIRED FIELD
========================================================== */

function validateRequired(inputId, message) {

    const input = document.getElementById(inputId);

    if (!input || input.value.trim() === "") {

        showError(input, message);

        return false;

    }

    return true;

}


/* ==========================================================
   DATE
========================================================== */

function validateDate(inputId) {

    const input = document.getElementById(inputId);

    if (!input.value) {

        showError(input, "Please select a date.");

        return false;

    }

    const selectedDate = new Date(input.value);

    const today = new Date();

    today.setHours(0, 0, 0, 0);

    if (selectedDate > today) {

        showError(input, "Date cannot be in the future.");

        return false;

    }

    return true;

}


/* ==========================================================
   IMAGE
========================================================== */

function validateImage(inputId) {

    const input = document.getElementById(inputId);

    if (!input.files.length) {

        showError(input, "Please upload an image.");

        return false;

    }

    const file = input.files[0];

    const allowedTypes = [

        "image/jpeg",
        "image/png",
        "image/webp"

    ];

    if (!allowedTypes.includes(file.type)) {

        showError(
            input,
            "Only JPG, JPEG, PNG and WEBP are allowed."
        );

        return false;

    }

    const maxSize = 5 * 1024 * 1024;

    if (file.size > maxSize) {

        showError(
            input,
            "Maximum image size is 5 MB."
        );

        return false;

    }

    return true;

}


/* ==========================================================
   ERROR HELPERS
========================================================== */

function showError(input, message) {

    if (!input) return;

    input.style.borderColor = "#dc3545";

    const error = input.parentElement.querySelector(".error-message");

    if (error) {

        error.textContent = message;

    }

}


function clearError(input) {

    if (!input) return;

    input.style.borderColor = "";

    const error = input.parentElement.querySelector(".error-message");

    if (error) {

        error.textContent = "";

    }

}


function clearAllErrors() {

    document.querySelectorAll(".error-message").forEach(error => {

        error.textContent = "";

    });

    document.querySelectorAll(
        "#foundItemForm input, #foundItemForm textarea, #foundItemForm select"
    ).forEach(input => {

        input.style.borderColor = "";

    });

}