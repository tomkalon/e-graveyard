function sendDataAPI(method, id, send, target) {
    if (typeof send['value'] === 'boolean') {
        if (send['value']) {
            send['value'] = 1;
        } else {
            send['value'] = 0;
        }
    }

    fetch(target, {
        method: method, headers: {
            "Content-Type": "application/json",
        }, body: JSON.stringify(send),
    })
        .then((response) => response.json())
        .then(data => {})
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
            callback(args, data);
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