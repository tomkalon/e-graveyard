import {Controller} from '@hotwired/stimulus';
import $ from "jquery";

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {


        const addNewModal = this.element.querySelector('[data-add-new-person]');
        const addNewModalClose = addNewModal.querySelector('[data-modal-box-close]');
        const addNewModalOpen = this.element.querySelector('[data-controller-name="data-add-new-person"]');
        addNewModalClose.addEventListener('click', () => {
            $(addNewModal).fadeOut(500);
        })
        addNewModalOpen.addEventListener('click', () => {
            $(addNewModal).fadeIn(500);
        })

    }
}
