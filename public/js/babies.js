const addBabyButton = document.getElementById('add-baby-button');
const addBabyForm = document.getElementById('add-baby-form');
const addBabyCard = document.querySelector('.card-add-baby');
const cancelAddButton = document.getElementById('cancel-add-button');

addBabyButton.addEventListener('click', function() {
    addBabyButton.classList.toggle('hide');
    addBabyForm.classList.toggle('hide');
    addBabyCard.classList.toggle('shadow-sm');
});

cancelAddButton.addEventListener('click', function() {
    addBabyButton.classList.toggle('hide');
    addBabyForm.classList.toggle('hide');
    addBabyCard.classList.toggle('shadow-sm');
})