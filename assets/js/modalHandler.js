import $ from "jquery";
import api from "./api";

function modalHandler(modal, container, callback, args) {

    if (container.querySelector("[" + modal + "]")) {
        const addNewModal = container.querySelector("[" + modal + "]");
        const addNewModalClose = addNewModal.querySelectorAll('[data-modal-box-close]');
        const addNewModalOpen = container.querySelectorAll('[data-modal-box-open="' + modal + '"]');
        addNewModalClose.forEach(element => {
            element.addEventListener('click', () => {
                $(addNewModal).fadeOut(300);
            })
        })
        addNewModalOpen.forEach(element => {
            element.addEventListener('click', () => {
                $(addNewModal).fadeIn(300);
                if (typeof callback === 'function') {
                    callback(addNewModal, element, args);
                }
            })
        })
        return addNewModal;
    } else {
        return false;
    }
}

function updateComplete(close) {
    close.click();
    location.reload();
}

function removeComplete(args, data) {
    window.location.replace(data);}

function booleanChoiceAction(modal, source, args) {
    let content = modal.querySelector("[data-remove-content]");
    const closeBtn = modal.querySelector("[data-modal-box-close]");
    const submitBtn = modal.querySelector("[data-action-submit]");
    const id = source.getAttribute('data-item-id');
    content.textContent = source.getAttribute('data-modal-box-prop');

    if (args['method'] === 'put') {
        // apiData
        let apiData = {};
        apiData[args['apiData']] = true;

        submitBtn.addEventListener('click', () => {
            api.sendDataAPI(args['method'], id, apiData, args['target'], updateComplete, updateComplete, null);
        })
    } else if (args['method'] === 'delete') {
        submitBtn.addEventListener('click', () => {
            api.deleteDataAPI(id, args['target'], removeComplete, updateComplete, closeBtn);
        })
    }

}

const scripts = {
    modalHandler, updateComplete, booleanChoiceAction
}

export default scripts;