import {Controller} from '@hotwired/stimulus';
import modalHandler from "../js/modalHandler"
import api from "../js/api"

export default class extends Controller {

    renderTable = (target, data) => {
        let tableBody = target.querySelector("[data-person-body]");
        const id = tableBody.getAttributeNames();
        const tableRow = target.querySelector("[data-person-row]").cloneNode();
        const tableBodyCheckbox = target.querySelector("[data-person-checkbox]").cloneNode();
        const tableBodySurname = target.querySelector("[data-person-surname]").cloneNode();
        const tableBodyName = target.querySelector("[data-person-name]").cloneNode();
        const tableBodyBorn = target.querySelector("[data-person-born]").cloneNode();
        const tableBodyDeath = target.querySelector("[data-person-death]").cloneNode();
        tableBody.textContent = '';

        const submitBtn = target.querySelector("[data-add-person-submit]");
        submitBtn.addEventListener('click', () => {
            console.log(id);
        })

        let checkChecked = document.createElement('span');
        checkChecked.classList.add('material-symbols-rounded', 'small');
        checkChecked.textContent = 'check_box';

        data.forEach((item, key) => {
            let row = tableRow.cloneNode();

            let checkbox = tableBodyCheckbox.cloneNode();
            let checked = checkChecked.cloneNode(true);

            let surname = tableBodySurname.cloneNode();
            surname.textContent = item['surname'];
            let name = tableBodyName.cloneNode();
            name.textContent = item['name'];
            let born = tableBodyBorn.cloneNode();
            born.textContent = item['born'];
            let death = tableBodyDeath.cloneNode();
            death.textContent = item['death'];

            row.appendChild(checkbox);
            row.appendChild(surname);
            row.appendChild(name);
            row.appendChild(born);
            row.appendChild(death);

            row.addEventListener('click', () => {
                if (!checkbox.hasChildNodes()) {
                    checkbox.appendChild(checked);
                    row.setAttribute('data-person-selected', item['id']);
                } else {
                    checkbox.textContent = '';
                    row.removeAttribute('data-person-selected');
                }
            })
            tableBody.appendChild(row);
        });
    }

    connect() {
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        modalHandler.modalHandler('data-add-new-person', this.element);
        if (modalHandler.modalHandler('data-select-person', this.element))
        {
            let modal = this.element.querySelector("[data-select-person]").querySelector(".modal-body");
            api.getDataAPI('get', false, '/manager/person/api/get/not_assigned', modal, this.renderTable);
        }
    }
}
