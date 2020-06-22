const checkForm = document.getElementById('check-form');

checkForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    await fetch('/home/checks', {
            method: 'post',
            credentials: "same-origin",
            body: new FormData(checkForm)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
            console.log(json);
        })
        .catch(function(error) {
            console.error(error);
        });
})