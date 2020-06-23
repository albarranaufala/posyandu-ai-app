const checkForm = document.getElementById('check-form');
const checkContainer = document.getElementById('check-container');
const checkFormContainer = document.getElementById('check-form-container');

function backToCheck(e) {
    e.preventDefault();
    checkFormContainer.classList.remove('d-none');
    document.getElementById('nama_anak').value = '';
    document.getElementById('berat_badan').value = '';
    document.getElementById('tinggi_badan').value = '';
    checkContainer.innerHTML = ''
}

checkForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    let checkResult;
    let data;

    checkFormContainer.classList.add('d-none');
    checkContainer.innerHTML = `
        <div style="height:250px" class="d-flex justify-content-center align-items-center">
            <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
        </div>
    `;

    await fetch('/home/checks', {
            method: 'post',
            credentials: "same-origin",
            body: new FormData(checkForm)
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
            checkResult = json.data.checkResult;
            data = json.data;
        })
        .catch(function(error) {
            console.error(error);
        });

    checkContainer.innerHTML = `
        <div>
            <div aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" onclick="backToCheck(event)">Periksa</a></li>
                <li class="breadcrumb-item active" aria-current="page">Nilai Gizi</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <div class="nilai-gizi">${checkResult.nutritional_value.toFixed(2)}</div>
                    <div class="nilai-gizi-label">${checkResult.nutritional_status}</div>
                </div>
            </div>
            <div class="row my-3 text-center">
                <div class="col-4">
                    <div><strong>${data.ageMonth}</strong></div>
                    <div>Umur (bulan)</div>
                </div>
                <div class="col-4">
                    <div><strong>${checkResult.body_weight}</strong> </div>
                    <div>Berat Badan (kg)</div>
                </div>
                <div class="col-4">
                    <div><strong>${checkResult.body_height}</strong></div>
                    <div>Tinggi Badan (cm)</div>
                </div>
            </div>

            <div class="alert alert-primary" role="alert">
                <div class="row text-md-center">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <div>Nama Anak</div>
                            <div><strong>${checkResult.baby.baby_name}</strong></div>
                        </div>
                        <div class="form-group">
                            <div>Tanggal Lahir</div>
                            <div><strong>${checkResult.baby.baby_birthday}</strong></div>
                        </div>
                        <div class="form-group mb-0">
                            <div>Nama Ibu</div>
                            <div><strong>${checkResult.baby.mother_name}</strong></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <div>Jenis Kelamin</div>
                            <div><strong>${checkResult.baby.gender}</strong></div>
                        </div>
                        <div class="form-group">
                            <div>Kode Anak</div>
                            <div><strong>${checkResult.baby.unique_code}</strong></div>
                        </div>
                        <div class="form-group mb-0">
                            <div>Kontak</div>
                            <div><strong>${checkResult.baby.contact}</strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
})