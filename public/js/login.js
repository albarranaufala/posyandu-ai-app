const kaderButton = document.getElementById('kader-button');
const ibuButton = document.getElementById('ibu-button');
const loginForm = document.getElementById('login-form');
const ibuForm = document.getElementById('ibu-form');

kaderButton.addEventListener('click', function() {
    kaderButton.classList.add('active');
    ibuButton.classList.remove('active');
    ibuForm.classList.add('hide');
    loginForm.classList.remove('hide');
});
ibuButton.addEventListener('click', function() {
    ibuButton.classList.add('active');
    kaderButton.classList.remove('active');
    loginForm.classList.add('hide');
    ibuForm.classList.remove('hide');
});