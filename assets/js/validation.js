/*
|--------------------------------------------------------------------------
| FEUreka Reporting Validation
|--------------------------------------------------------------------------
|
| Shared validation for:
| - Report Found Item
| - Report Missing Item
|
*/

document.addEventListener("DOMContentLoaded", () => {

    const foundForm = document.getElementById("foundItemForm");
    const missingForm = document.getElementById("missingItemForm");

    if (foundForm) {

        initializeFoundItem(foundForm);

    }

    if (missingForm) {

        initializeMissingItem(missingForm);

    }

});


/* ==========================================================
   INITIALIZE
========================================================== */

function initializeFoundItem(form) {

    attachLiveValidation(form);

    form.addEventListener("submit", function (event) {

        const valid = validateFoundItem();

        if (!valid) {

            event.preventDefault();

            scrollToFirstError("foundItemForm");

        }

    });

}

function initializeMissingItem(form) {

    attachLiveValidation(form);

    form.addEventListener("submit", function (event) {

        const valid = validateMissingItem();

        if (!valid) {

            event.preventDefault();

            scrollToFirstError("missingItemForm");

        }

    });

}


/* ==========================================================
   FOUND ITEM
========================================================== */

function validateFoundItem() {

    let valid = true;

    clearAllErrors("foundItemForm");

    valid = validateRequired("category_id", "Please select a category.") && valid;
    valid = validateRequired("item_name", "Item name is required.") && valid;
    valid = validateRequired("item_description", "Description is required.") && valid;
    valid = validateRequired("room", "Room is required.") && valid;
    valid = validateRequired("floor", "Floor is required.") && valid;
    valid = validateRequired(
        "location_description",
        "Location description is required."
    ) && valid;

    valid = validateDate(
        "date_found",
        "Please select the date the item was found."
    ) && valid;

    valid = validateImage("image", false) && valid;

    return valid;

}


/* ==========================================================
   MISSING ITEM
========================================================== */

function validateMissingItem() {

    let valid = true;

    clearAllErrors("missingItemForm");

    valid = validateRequired("category_id", "Please select a category.") && valid;
    valid = validateRequired("item_name", "Item name is required.") && valid;
    valid = validateRequired("item_description", "Description is required.") && valid;
    valid = validateRequired("room", "Room is required.") && valid;
    valid = validateRequired("floor", "Floor is required.") && valid;
    valid = validateRequired(
        "location_description",
        "Last known location is required."
    ) && valid;

    valid = validateDate(
        "date_lost",
        "Please select the date the item was lost."
    ) && valid;

    valid = validateContactNumber() && valid;

    valid = validateImage("image", false) && valid;

    return valid;

}


/* ==========================================================
   LIVE VALIDATION
========================================================== */

function attachLiveValidation(form) {

    form.querySelectorAll("input, textarea, select").forEach(input => {

        const eventType =
            input.type === "file" || input.tagName === "SELECT"
                ? "change"
                : "input";

        input.addEventListener(eventType, () => {

            clearError(input);

        });

    });

}


/* ==========================================================
   REQUIRED
========================================================== */

function validateRequired(id, message) {

    const input = document.getElementById(id);

    if (!input || input.value.trim() === "") {

        showError(input, message);

        return false;

    }

    return true;

}


/* ==========================================================
   DATE
========================================================== */

function validateDate(id, message) {

    const input = document.getElementById(id);

    if (!input.value) {

        showError(input, message);

        return false;

    }

    const selected = new Date(input.value);

    const today = new Date();

    today.setHours(0,0,0,0);

    if (selected > today) {

        showError(
            input,
            "Date cannot be in the future."
        );

        return false;

    }

    return true;

}


/* ==========================================================
   CONTACT NUMBER
========================================================== */

function validateContactNumber() {

    const input = document.getElementById("contact_number");

    const value = input.value.trim();

    const pattern = /^[0-9+\-\s()]{7,30}$/;

    if (!pattern.test(value)) {

        showError(
            input,
            "Please enter a valid contact number."
        );

        return false;

    }

    return true;

}


/* ==========================================================
   IMAGE
========================================================== */

function validateImage(id, required) {

    const input = document.getElementById(id);

    if (!input.files.length) {

        if (required) {

            showError(
                input,
                "Please upload an image."
            );

            return false;

        }

        return true;

    }

    const file = input.files[0];

    const allowed = [

        "image/jpeg",
        "image/png",
        "image/gif",
        "image/webp"

    ];

    if (!allowed.includes(file.type)) {

        showError(
            input,
            "Only JPG, JPEG, PNG, GIF and WEBP files are allowed."
        );

        return false;

    }

    if (file.size > 5 * 1024 * 1024) {

        showError(
            input,
            "Maximum image size is 5 MB."
        );

        return false;

    }

    return true;

}


/* ==========================================================
   HELPERS
========================================================== */

function showError(input, message) {

    if (!input) return;

    input.style.borderColor = "#dc3545";

    const error =
    input.closest(".form-group")?.querySelector(".error-message");

    if (error) {

        error.textContent = message;

    }

}

function clearError(input) {

    if (!input) return;

    input.style.borderColor = "";

    const error =
    input.closest(".form-group")?.querySelector(".error-message");

    if (error) {

        error.textContent = "";

    }

}

function scrollToFirstError(formId) {

    const form = document.getElementById(formId);

    if (!form) return;

    const firstError = form.querySelector(".error-message:not(:empty)");

    if (!firstError) return;

    const group = firstError.closest(".form-group");

    if (!group) return;

    group.scrollIntoView({

        behavior: "smooth",
        block: "center"

    });

    const input = group.querySelector("input, textarea, select");

    if (input) {

        input.focus();

    }

}

function clearAllErrors(formId) {

    const form = document.getElementById(formId);

    if (!form) return;

    form.querySelectorAll(".error-message").forEach(error => {

        error.textContent = "";

    });

    form.querySelectorAll("input, textarea, select").forEach(input => {

        input.style.borderColor = "";

    });

}
