import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    }
}
