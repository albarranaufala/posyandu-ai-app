const nav = document.querySelector('nav');
const toChecksButton = document.getElementById('to-checks-button');
const toBabiesButton = document.getElementById('to-babies-button');
const toRulesButton = document.getElementById('to-rules-button');
const toLogoutButton = document.getElementById('to-logout-button');

toChecksButton.addEventListener('click', function() {
    location.href = '/home/checks';
})
toBabiesButton.addEventListener('click', function() {
    location.href = '/home/babies';
})
toRulesButton.addEventListener('click', function() {
    location.href = '/home/rules';
})
toLogoutButton.addEventListener('click', function() {
    location.href = '/logout';
})

window.addEventListener('scroll', function() {
    let scroll = window.pageYOffset;
    if (scroll > 0) {
        nav.classList.add("shadow-sm");
    } else {
        nav.classList.remove("shadow-sm");
    }
});