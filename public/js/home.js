const nav = document.querySelector('nav');

window.addEventListener('scroll', function() {
    let scroll = window.pageYOffset;
    if (scroll > 0) {
        nav.classList.add("shadow-sm");
    } else {
        nav.classList.remove("shadow-sm");
    }
});