const kaderButton = document.getElementById('kader-button');
const ibuButton = document.getElementById('ibu-button');
const loginForm = document.getElementById('login-form');
const ibuForm = document.getElementById('ibu-form');
const loginContainer = document.getElementById('login-container');
const searchResultContainer = document.getElementById('search-result-container');
const inputCode = document.getElementById('kode_anak');

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
ibuForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    loginContainer.classList.add('d-none');
    let baby;
    searchResultContainer.innerHTML =
        `
        <div class="lds-ring m-auto">
            <div></div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        `
    await fetch('/search/' + inputCode.value)
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
    baby = data.baby;
    if (baby) {
        searchResultContainer.innerHTML = renderSearchResult(baby)
        renderChart(baby.checks);
    } else {
        searchResultContainer.innerHTML = `<div class="m-auto text-center">Anak tidak ditemukan 
                                            <br> <a href="#" onclick="backToLogin(event)">Kembali</a></div>`
    }
})

function backToLogin(e) {
    e.preventDefault();
    loginContainer.classList.remove('d-none');
    searchResultContainer.innerHTML = '';
}

function renderSearchResult(baby) {
    return `
            <div class="col-md-8 col-sm-10 mx-auto">
                <div class="card shadow">
                    <div class="card-body">
                        <div aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#" onclick="backToLogin(event)">Login</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Anak</li>
                            </ol>
                        </div>
                        <div class="alert alert-primary" role="alert">
                            <div class="row text-md-center">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div>Nama Anak</div>
                                        <div><strong>${baby.baby_name}</strong></div>
                                    </div>
                                    <div class="form-group">
                                        <div>Tanggal Lahir</div>
                                        <div><strong>${baby.baby_birthday}</strong></div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div>Nama Ibu</div>
                                        <div><strong>${baby.mother_name}</strong></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <div>Jenis Kelamin</div>
                                        <div><strong>${baby.gender}</strong></div>
                                    </div>
                                    <div class="form-group">
                                        <div>Kode Anak</div>
                                        <div><strong>${baby.unique_code}</strong></div>
                                    </div>
                                    <div class="form-group mb-0">
                                        <div>Kontak</div>
                                        <div><strong>${baby.contact}</strong></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <div>Alamat</div>
                                        <div><strong>${baby.address}</strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h3 class="mb-3"><strong>Pemeriksaan Anak</strong></h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Berat Badan</th>
                                            <th scope="col">Tinggi Badan</th>
                                            <th scope="col">Umur</th>
                                            <th scope="col">Tanggal Periksa</th>
                                            <th scope="col">Nilai Gizi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${renderBabyChecks(baby.checks)}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="chartContainer" class="mt-3"></div>
                    </div>
                </div>
            </div>
        `;
}

function renderBabyChecks(checks) {
    if (checks.length == 0) {
        return `<tr><td colspan='6'>Belum melakukan pemeriksaan</td></tr>`;
    }
    return checks.map((check, index) => renderBabyCheck(check, index))
        .reduce((finalRender, renderBabyCheck) => finalRender + renderBabyCheck);
}

function renderBabyCheck(check, order) {
    const date = new Date(check.created_at);
    const dateTimeFormat = new Intl.DateTimeFormat('en', { year: 'numeric', month: 'short', day: '2-digit' })
    const [{ value: month }, , { value: day }, , { value: year }] = dateTimeFormat.formatToParts(date)

    return `<tr>
                <th scope="row">${order+1}</th>
                <td>${check.body_weight}</td>
                <td>${check.body_height}</td>
                <td>${check.age}</td>
                <td>${day} ${month} ${year }</td>
                <td>${check.nutritional_value.toFixed(2)}</td>
            </tr>`;
}

function renderChart(checks) {
    if (checks.length) {
        document.getElementById('chartContainer').style.height = '300px'
        document.getElementById('chartContainer').style.width = '100%'
        let nutritionalValueDatas = checks.map(check => {
            return {
                y: check.nutritional_value
            }
        })
        let chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Grafik Nilai Gizi"
            },
            axisY: {
                includeZero: false
            },
            data: [{
                type: "line",
                indexLabelFontSize: 16,
                dataPoints: nutritionalValueDatas
            }]
        });
        chart.render();
    }
}