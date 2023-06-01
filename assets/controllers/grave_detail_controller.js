import {Controller} from '@hotwired/stimulus';
import modalHandler from "../js/modalHandler"
import api from "../js/api"

export default class extends Controller {
    connect() {
        if ( window.history.replaceState ) {
            window.history.replaceState( null, null, window.location.href );
        }
        modalHandler.modalHandler('data-add-new-person', this.element);
        if (modalHandler.modalHandler('data-select-person', this.element))
        {
            api.getDataAPI('get', false, '/manager/person/api/get/not_assigned');
        }
    }
}
