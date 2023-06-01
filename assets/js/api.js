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

function getDataAPI(method, id, target) {
    if (id) {
        target = target + "/" + id + "/";
    }
    console.log(target);

    fetch(target, {
        method: method, headers: {
            "Content-Type": "application/json",
        }
    })
        .then((response) => response.json())
        .then(data => {
            console.log(data);
            return data;
        })
        .catch((error) => {
            console.log("API communication error!");
            console.error("Error:", error);
            return false;
        });
}

const apiFunctions = {
    sendDataAPI, getDataAPI
}

export default apiFunctions;