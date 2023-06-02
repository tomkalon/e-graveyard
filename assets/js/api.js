function sendDataAPI(method, id, send, target, callback, args) {
    if (id) {
        target = target + "/" + id;
    }
    console.log(target);
    console.log(send);

    fetch(target, {
        method: method, headers: {
            "Content-Type": "application/json",
        }, body: JSON.stringify(send),
    })
        .then((response) => response.json())
        .then(data => {
            if (data) {
                callback(args);
            } else {
                console.log(null);
            }
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
        });
}

function getDataAPI(method, id, target, args, callback) {
    if (id) {
        target = target + "/" + id + "/";
    }

    fetch(target, {
        method: method, headers: {
            "Content-Type": "application/json",
        }
    })
        .then((response) => response.json())
        .then(data => {
            if (typeof callback === 'function') {
                callback(args, data);
            }
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
        });
}

const apiFunctions = {
    sendDataAPI, getDataAPI
}

export default apiFunctions;