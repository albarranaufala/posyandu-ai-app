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
            data = json.data;
        })
        .catch(function(error) {
            data = 'error';
        });

    if (data != 'error') {
        checkContainer.innerHTML = renderCheckResult(data.checkResult);
        renderChart(data.checkResult.baby.checks);
    } else {
        checkContainer.innerHTML = `
        <div style="height:250px" class="d-flex flex-column justify-content-center align-items-center">
            <div>Maaf, umur melebihi balita. Sistem tidak bisa melakukan perhitungan.</div>
            <a href="#" onclick="backToCheck(event)">< Kembali</a>
        </div>
        `
    }
})

function renderCheckResult(check) {
    return `
            <div>
                <div aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" onclick="backToCheck(event)">Periksa</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Nilai Gizi</li>
                    </ol>
                </div>
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="nilai-gizi">${check.nutritional_value.toFixed(2)}</div>
                        <div class="nilai-gizi-label">${check.nutritional_status}</div>
                    </div>
                </div>
                <div class="row my-3 text-center">
                    <div class="col-4">
                        <div><strong>${check.age}</strong></div>
                        <div>Umur (bulan)</div>
                    </div>
                    <div class="col-4">
                        <div><strong>${check.body_weight}</strong> </div>
                        <div>Berat Badan (kg)</div>
                    </div>
                    <div class="col-4">
                        <div><strong>${check.body_height}</strong></div>
                        <div>Tinggi Badan (cm)</div>
                    </div>
                </div>
    
                <div class="alert alert-primary" role="alert">
                    <div class="row text-md-center">
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <div>Nama Balita</div>
                                <div><strong>${check.baby.baby_name}</strong></div>
                            </div>
                            <div class="form-group">
                                <div>Tanggal Lahir</div>
                                <div><strong>${check.baby.baby_birthday}</strong></div>
                            </div>
                            <div class="form-group mb-0">
                                <div>Nama Ibu</div>
                                <div><strong>${check.baby.mother_name}</strong></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div>Jenis Kelamin</div>
                                <div><strong>${check.baby.gender}</strong></div>
                            </div>
                            <div class="form-group">
                                <div>Kode Balita</div>
                                <div><strong>${check.baby.unique_code}</strong></div>
                            </div>
                            <div class="form-group mb-0">
                                <div>Kontak</div>
                                <div><strong>${check.baby.contact}</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="mb-3"><strong>Pemeriksaan Balita</strong></h3>
                    <div class="table-responsive" style="max-height:500px">
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
                                ${renderBabyChecks(check.baby.checks)}
                            </tbody>
                        </table>
                    </div>
                </div>
                <h3 class="my-3"><strong>Grafik Nilai Gizi</strong></h3>
                <div id="chartContainer" class="mt-3"></div>
            </div>
        `
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