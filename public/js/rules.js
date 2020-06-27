const rulesContainer = document.getElementById('rules-container');
const formAddRule = document.getElementById('form-add-rule');
const btnAddRule = document.getElementById('btn-add-rule');
const btnCancelAddRule = document.getElementById('btn-cancel-add-rule');
renderTable(rules)
listenerEditButton();
listenerDeleteButton();

function listenerEditButton(){
    let editButtons = document.querySelectorAll('.edit-icon');
    let rows = document.querySelectorAll('tr');
    let rowsArray = Array.from(rows);
    editButtons.forEach(editButton => {
        let isEdit = false;
        editButton.addEventListener('click', async function(e) {
            if (!isEdit) {
                editButton.innerHTML = 'save';
                rowEdit = rowsArray.find(row => row.dataset.ruleId == editButton.dataset.ruleId);
                rule = rules.find(rule => rule.id == rowEdit.dataset.ruleId);
                for (let i = 0; i < rowEdit.children.length; i++) {
                    if (i != 0 && i != rowEdit.children.length - 1) {
                        let value = rowEdit.children[i].innerHTML;
                        rowEdit.children[i].innerHTML = `<select class="form-control">
                            ${variables[i-1].sets.map(set => {
                                if(i != rowEdit.children.length - 2){
                                    if(set.id == rule.input_sets[i-1].id){
                                        return `<option value="${set.id}" selected>${set.name}</option>`
                                    }
                                }else{
                                    if(set.id == rule.output_set.id){
                                        return `<option value="${set.id}" selected>${set.name}</option>`
                                    }
                                }
                                return `<option value="${set.id}">${set.name}</option>`
                            }).reduce((acc, set) => acc + set)}
                        </select>`
                    }
                }
    
                isEdit = true;
            } else {
                editButton.innerHTML = 'edit';
                rowEdit = rowsArray.find(row => row.dataset.ruleId == editButton.dataset.ruleId);
                
                let values = [];
                for (let i = 0; i < rowEdit.children.length; i++) {
                    if (i != 0 && i != rowEdit.children.length - 1) {
                        values.push(rowEdit.children[i].children[0].value);
                    }
                }
    
                await fetch('/rules/edit/' + rule.id, {
                    method: 'post',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": csrfToken
                    },
                    credentials: "same-origin",
                    body: 
                        JSON.stringify({
                            values: values
                        })
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(json) {
                    rules = json.data.rules;
                })
                .catch(function(error) {
                    data = 'error';
                });
    
                rule = rules.find(rule => rule.id == rowEdit.dataset.ruleId);
    
                for (let i = 0; i < rowEdit.children.length; i++) {
                    if (i != 0 && i != rowEdit.children.length - 1) {
                        let value;
                        if (i != rowEdit.children.length - 2) {
                            value = rule.input_sets[i - 1];
                        } else {
                            value = rule.output_set;
                        }
                        rowEdit.children[i].innerHTML = `${value.name}`
                    }
                }
    
    
                isEdit = false;
            }
        });
    });
}

function listenerDeleteButton(){
    let deleteButtons = document.querySelectorAll('.delete-icon');
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener('click', async function(e){
            let isDelete = confirm('Yakin ingin menghapus aturan?');
            if(isDelete){
                await fetch('/rules/delete/' + deleteButton.dataset.ruleId, {
                    method: 'post',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": csrfToken
                    },
                    credentials: "same-origin"
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(json) {
                    rules = json.data.rules;
                    renderTable(rules);
                    listenerEditButton();
                    listenerDeleteButton();
                })
                .catch(function(error) {
                    data = 'error';
                });
            }
        })
    });
}

function renderTable(rules) {
    rulesContainer.innerHTML =
        `<div class = "table-responsive">
            <table class = "table table-borderless mb-0">
                <tr>
                    <th scope = "row">Kode</th>
                    <th>Umur</th> 
                    <th>Berat Badan</th> 
                    <th>Tinggi Badan</th> 
                    <th>Status Gizi</th> 
                </tr>
                ${renderRules(rules)}
            </table>
        </div>`
}

function renderRules(rules) {
    return rules.map(rule => {
        return `<tr class = "card-posyandu" data-rule-id = "${rule.id}">
                    <td scope = "row" > ${rule.code} </td>
                    ${renderInputSets(rule)}
                    <td>${rule.output_set.name}</td> 
                    <td><i class = "material-icons icon-posyandu edit-icon" data-rule-id = "${rule.id}">edit</i><i class = "material-icons icon-posyandu delete-icon" data-rule-id = "${rule.id}">delete</i></td>
                </tr>`
    }).reduce((acc, rule) => acc + rule);
}

function renderInputSets(rule) {
    return rule.input_sets.map(input_set => `<td>${input_set.name}</td>`).reduce((acc, input_set) => acc + input_set)
}

btnAddRule.addEventListener('click', function(e){
    formAddRule.classList.remove('d-none');
    btnAddRule.classList.add('d-none');
})

btnCancelAddRule.addEventListener('click', function(e){
    formAddRule.classList.add('d-none');
    btnAddRule.classList.remove('d-none');
})

formAddRule.addEventListener('submit', async function(e){
    e.preventDefault();

    await fetch('/rules', {
        method: 'post',
        credentials: "same-origin",
        body: new FormData(formAddRule)
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(json) {
        data = json.data;
        rules = data.rules
        renderTable(rules);
        listenerEditButton();
        listenerDeleteButton();
        formAddRule.classList.add('d-none');
        btnAddRule.classList.remove('d-none');
    })
    .catch(function(error) {
        data = 'error';
    });
})