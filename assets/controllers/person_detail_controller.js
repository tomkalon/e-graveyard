import {Controller} from '@hotwired/stimulus';
import modalHandler from "../js/modalHandler";

export default class extends Controller {
    connect() {
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        modalHandler.modalHandler('data-remove-person-all', this.element, false, false);
    }
}
