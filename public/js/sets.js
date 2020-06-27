const btnAddSet = document.getElementById('btn-add-set');
const formAddSet = document.getElementById('form-add-set');
const btnCancelAddSet = document.getElementById('btn-cancel-add-set');
const btnEditSets = document.querySelectorAll('.btn-edit-set');
const btnDeleteSets = document.querySelectorAll('.btn-delete-set');
const btnSubmit = document.getElementById('submit-form');
const variableIdInput = document.getElementById('variabel');
const setNameInput = document.getElementById('nama_himpunan');
const setCodeInput = document.getElementById('kode_himpunan');
const setRangeInput = document.getElementById('range');
const setCurveInput = document.getElementById('kurva');

let isEdit = false;
saveMode();

btnAddSet.addEventListener('click', function(e){
    btnAddSet.classList.add('d-none');
    formAddSet.classList.remove('d-none');
    saveMode();
});

btnCancelAddSet.addEventListener('click', function(e){
    btnAddSet.classList.remove('d-none');
    formAddSet.classList.add('d-none');
});

function saveMode(){
    btnSubmit.innerHTML = "Tambah";
    isEdit = false;
    formAddSet.action = '/sets';
    variableIdInput.value = '';
    setNameInput.value = '';
    setCodeInput.value = '';
    setRangeInput.value = '';
    setCurveInput.value = '';
    variableIdInput.focus();
}

function editMode(id){
    btnSubmit.innerHTML = "Simpan Perubahan";
    isEdit = true;
    formAddSet.action = '/sets/edit/' + id;
    set = sets.find(set => set.id == id);
    variableIdInput.value = set.variable_id;
    setNameInput.value = set.name;
    setCodeInput.value = set.code;
    setRangeInput.value = set.range;
    setCurveInput.value = set.curve;
    variableIdInput.focus();
}

btnEditSets.forEach(btnEditSet => {
    btnEditSet.addEventListener('click', function(e){
        const setId = btnEditSet.dataset.setId;
        btnAddSet.classList.add('d-none');
        formAddSet.classList.remove('d-none');
        editMode(setId);
    })
});

btnDeleteSets.forEach(btnDeleteSet => {
    btnDeleteSet.addEventListener('click', function(e){
        const formDelete = btnDeleteSet.parentElement;
        let isDelete = confirm('Yakin ingin menghapus himpunan?');
        if(isDelete){
            formDelete.submit();
        }
    })
})