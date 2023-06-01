import $ from "jquery";

function modalHandler(modal, container) {

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
            })
        })
        return true;
    } else {
        return false;
    }
}

const scripts = {
    modalHandler
}

export default scripts;