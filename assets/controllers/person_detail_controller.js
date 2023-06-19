import {Controller} from '@hotwired/stimulus';
import modalHandler from "../js/modalHandler";

export default class extends Controller {
    connect()
    {
        modalHandler.modalHandler('data-remove-person-all', this.element, false, false);
    }
}
