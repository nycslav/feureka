document.addEventListener("DOMContentLoaded", () => {

    const buttons = document.querySelectorAll(".open-modal-btn");

    buttons.forEach(button => {

        button.addEventListener("click", () => {

            const modalId = button.dataset.modal;

            document
                .getElementById(modalId)
                .classList.add("active");

        });

    });

    document.querySelectorAll(".modal-close").forEach(button => {

        button.addEventListener("click", () => {

            button.closest(".modal-overlay")
                .classList.remove("active");

        });

    });

});

document.querySelectorAll(".modal-overlay").forEach(modal => {

    modal.addEventListener("click", e => {

        if(e.target === modal){

            modal.classList.remove("active");

        }

    });

});

const menuButton = document.getElementById("menu-toggle");
const sidebarOverlay = document.getElementById("sidebar-overlay");

if(menuButton){

    menuButton.addEventListener("click", ()=>{

        sidebarOverlay.classList.add("active");

    });

}

if(sidebarOverlay){

    sidebarOverlay.addEventListener("click", e=>{

        if(e.target===sidebarOverlay){

            sidebarOverlay.classList.remove("active");

        }

    });

}