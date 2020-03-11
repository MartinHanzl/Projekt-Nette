'use strict'

let nav = document.getElementById('main-nav');
let bars = document.getElementById('bars-nav');
bars.addEventListener('click', function() {
    if(nav.style.display === "flex") {
        nav.style.display = "none";
        bars.className = "fas fa-bars";
    } else {
        nav.style.display = "flex";
        bars.className = "fas fa-times";
    }
});

