import {Controller} from '@hotwired/stimulus';
import modalHandler from "../js/modalHandler"
import api from "../js/api"

let checkChecked = document.createElement('span');
checkChecked.classList.add('material-symbols-rounded', 'small');
checkChecked.textContent = 'check_box';

export default class extends Controller {
    connect()
    {
        // grave details ID
        const grave_id = this.element.querySelector("[data-target-id]").getAttribute('data-target-id');

        // add new person MODAL BOX
        modalHandler.modalHandler('data-add-new-person', this.element, false, false);

        // remove assigned person MODAL BOX
        modalHandler.modalHandler('data-remove-person', this.element, modalHandler.booleanChoiceApi, {
            'method': 'put',
            'apiData': 'clearGrave',
            'target': '/person/api/update'
        });

        // remove assigned person MODAL BOX
        modalHandler.modalHandler('data-remove-grave',  this.element, false, false);

        // select not assigned MODAL BOX
        let selectPersonModalBox = modalHandler.modalHandler('data-select-person', this.element, false, false);
        if (selectPersonModalBox) {
            if (selectPersonModalBox.querySelector("[data-person-table]")) {
                // apiData
                let apiData = {
                    'assignToGrave': []
                };

                let links = selectPersonModalBox.querySelectorAll("[data-person-row]");
                links.forEach(element => {
                    const person_id = element.getAttribute('data-person-row');
                    element.addEventListener('click', () => {
                        let checkbox = element.querySelector("[data-person-checkbox]");
                        let checked = checkChecked.cloneNode(true);
                        if (!checkbox.hasChildNodes()) {
                            checkbox.appendChild(checked);
                            apiData['assignToGrave'].push(person_id);
                        } else {
                            checkbox.textContent = '';
                            let index = apiData['people_id'].indexOf(person_id);
                            apiData['assignToGrave'].splice(index, 1);
                        }
                    })
                });

                const saveBtn = selectPersonModalBox.querySelector("[data-add-person-submit]");
                const closeBtn = selectPersonModalBox.querySelector("[data-modal-box-close]");

                saveBtn.addEventListener('click', () => {
                    api.sendDataAPI(
                        'put',
                        grave_id,
                        apiData,
                        '/grave/api/update',
                        modalHandler.updateComplete,
                        modalHandler.updateComplete,
                        closeBtn
                    );
                })
            }
        }
    }
}
