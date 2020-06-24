const addBabyButton = document.getElementById('add-baby-button');
const addBabyForm = document.getElementById('add-baby-form');
const addBabyCard = document.querySelector('.card-add-baby');
const cancelAddButton = document.getElementById('cancel-add-button');
const babiesContainer = document.getElementById('babies-container');
const babySearch = document.getElementById('baby-search');
const pageDetailBaby = document.getElementById('page-detail-baby');
const pageBabies = document.getElementById('page-babies');
let babies;

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
            babies = data.babies;
            renderBabies(data.babies);
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
        babies = data.babies;
        renderBabies(data.babies);
    })
    .catch(function(error) {
        console.error(error);
    });

function renderBabies(babies) {
    if (babies.length) {
        babiesContainer.innerHTML = babies.map(baby => renderBaby(baby))
            .reduce((result, renderBaby) => result + renderBaby);
    } else {
        babiesContainer.innerHTML = 'Tidak ada balita';
    }
}

function renderBaby(baby) {
    return `
        <div class="card-add-baby mb-3" onclick="toDetail(${baby.id})">
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

babySearch.addEventListener('input', function(e) {
    const searchResult = babies.filter(baby => {
        const nameCode = baby.unique_code + baby.baby_name;
        return nameCode.toLowerCase().match(babySearch.value.toLowerCase())
    });

    renderBabies(searchResult);
})

function toDetail(babyId) {
    const baby = babies.find(baby => baby.id == babyId);
    pageBabies.classList.add('d-none');
    pageDetailBaby.innerHTML = renderDetailBaby(baby);
    renderChart(baby.checks);
    scrollToTop();
}

function backToBabies(e) {
    e.preventDefault();
    pageBabies.classList.remove('d-none');
    pageDetailBaby.innerHTML = '';
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

function renderDetailBaby(baby) {
    return `
        <div aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#" onclick="backToBabies(event)">Daftar Balita</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detail Balita</li>
            </ol>
        </div>
        <div class="alert alert-primary" role="alert">
            <div class="row text-md-center">
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <div>Nama Balita</div>
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
                        <div>Kode Balita</div>
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
                        ${renderBabyChecks(baby.checks)}
                    </tbody>
                </table>
            </div>
        </div>
        <h3 class="my-3"><strong>Grafik Nilai Gizi</strong></h3>
        <div id="chartContainer" class="mt-3"></div>
        `;
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

function scrollToTop() {
    const c = document.documentElement.scrollTop || document.body.scrollTop;
    if (c > 0) {
        window.scrollTo(0, 0);
    }
}