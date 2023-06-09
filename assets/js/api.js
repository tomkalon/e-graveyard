function sendDataAPI(method, id, send, target, callback, callbackFailed, args) {
    if (id) {
        target = target + "/" + id;
    }

    // console.log(method);
    // console.log(id);
    // console.log(target);
    // console.log(send);
    // console.log(args);

    fetch(target, {
        method: method, headers: {
            "Content-Type": "application/json",
        }, body: JSON.stringify(send),
    })
        .then((response) => response.json())
        .then(data => {
            console.log(data);
            if (data && typeof callback === 'function') {
                callback(args, data);
            } else {
                if (typeof callbackFailed === 'function') {
                    callbackFailed(args);
                }
            }
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
        });
}

function getDataAPI(id, target, callback, callbackFailed, args) {
    if (id) {
        target = target + "/" + id;
    }

    // console.log(id);
    // console.log(target);
    // console.log(args);

    fetch(target, {
        method: 'get', headers: {
            "Content-Type": "application/json",
        }
    })
        .then((response) => response.json())
        .then(data => {
            console.log(data);
            if (data && typeof callback === 'function') {
                callback(args, data);
            } else {
                if (typeof callbackFailed === 'function') {
                    callbackFailed(args);
                }
            }
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
        });
}

function deleteDataAPI(id, target, callback, callbackFailed, args) {
    if (id) {
        target = target + "/" + id;
    }

    // console.log(id);
    // console.log(target);
    // console.log(args);

    fetch(target, {
        method: 'delete'
    })
        .then((response) => response.json())
        .then(data => {
            console.log(data);
            if (data && typeof callback === 'function') {
                callback(args, data);
            } else {
                if (typeof callbackFailed === 'function') {
                    callbackFailed(args);
                }
            }
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
        });
}

const apiFunctions = {
    sendDataAPI, getDataAPI, deleteDataAPI
}

export default apiFunctions;