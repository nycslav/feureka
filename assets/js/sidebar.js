document.addEventListener("DOMContentLoaded",()=>{

    const menu=document.getElementById("menu-toggle");

    const overlay=document.getElementById("sidebar-overlay");

    if(menu){

        menu.addEventListener("click",()=>{

            overlay.classList.add("active");

        });

    }

    overlay.addEventListener("click",(e)=>{

        if(e.target===overlay){

            overlay.classList.remove("active");

        }

    });

});