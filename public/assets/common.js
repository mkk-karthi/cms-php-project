// get api
function AjaxGet(url) {

    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = () => {
            if (xhttp.status == 200)
                resolve(xhttp.responseText)
            else
                reject(xhttp.statusText)
        }
        xhttp.open("GET", url);
        xhttp.send();
    })
}

// post api
function AjaxPost(url, data = {}) {

    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = () => {
            if (xhttp.status == 200)
                resolve(xhttp.responseText)
            else
                reject(xhttp.statusText)
        }
        xhttp.open("POST", url);
        xhttp.setRequestHeader('Content-type', 'application/json; charset=UTF-8')
        xhttp.send(JSON.stringify(data));
    })
}

// upload file api "multipart/form-data"
function fileUpload(url, datas = {}, files) {

    const fileData = new FormData();
    for (let key in datas) {
        fileData.append(key, datas[key]);
    }

    for (let key in files) {
        fileData.append(key, files[key]);
    }

    return new Promise((resolve, reject) => {
        const xhttp = new XMLHttpRequest();
        xhttp.onload = () => {
            if (xhttp.status == 200)
                resolve(xhttp.responseText)
            else
                reject(xhttp.statusText)
        }
        xhttp.open("POST", url);
        xhttp.send(fileData);
    })
}

function capitalize(word) {
    return word.charAt(0).toUpperCase() + word.slice(1)
}

function HtmlEncode(s) {
    var el = document.createElement("div");
    el.innerText = s;
    return el.innerHTML;
}

// notification
function appNotification(type, message, time = 5000, callback = null) {
    const notifyElement = document.getElementById("notifications");

    message = HtmlEncode(message);
    const alertContent = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>`;
    notifyElement.innerHTML += alertContent;

    // autoclose notification
    setTimeout(() => {
        notifyElement.innerHTML = notifyElement.innerHTML.replace(alertContent, "");

        if (callback != null)
            callback();
    }, time);
}