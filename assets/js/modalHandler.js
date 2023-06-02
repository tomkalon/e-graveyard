import $ from "jquery";

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

const scripts = {
    modalHandler
}

export default scripts;