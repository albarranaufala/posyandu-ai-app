const addBabyButton = document.getElementById('add-baby-button');
const addBabyForm = document.getElementById('add-baby-form');
const addBabyCard = document.querySelector('.card-add-baby');
const cancelAddButton = document.getElementById('cancel-add-button');
const babiesContainer = document.getElementById('babies-container');

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

addBabyForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    await fetch('/babies', {
            method: 'post',
            credentials: "same-origin",
            body: new FormData(addBabyForm)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
            data = json.data;
            renderBabies(data.babies)
        })
        .catch(function(error) {
            console.error(error);
        });

    addBabyButton.classList.toggle('hide');
    addBabyForm.classList.toggle('hide');
    addBabyCard.classList.toggle('shadow-sm');
})

fetch('/babies')
    .then(function(response) {
        return response.json();
    })
    .then(function(json) {
        data = json.data;
        renderBabies(data.babies);
        // console.log(data.babies);
    })
    .catch(function(error) {
        console.error(error);
    });

function renderBabies(babies) {
    babiesContainer.innerHTML = babies.map(baby => renderBaby(baby))
        .reduce((result, renderBaby) => result + renderBaby)
}

function renderBaby(baby) {
    return `
        <div class="card-add-baby mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 d-flex flex-column align-items-center justify-content-center text-center">
                        <span><strong>${baby.baby_name}</strong></span>
                        <span class="color-red"><small>${baby.unique_code}</small></span>
                    </div>
                    <div class="col-sm-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                        <span>${baby.baby_birthday}</span>
                        <span><small>${baby.gender}</small></span>
                    </div>
                    <div class="col-sm-4 d-flex flex-column align-items-center justify-content-center text-center color-grey">
                        <span>${baby.mother_name}</span>
                        <span><small>${baby.contact}</small></span>
                    </div>
                </div>
            </div>
        </div>
    `
}