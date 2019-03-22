// Simple object that provides nice, clean functions for making asynchronous HTTP requests to our backend
const api = {
    get: url => request('GET', url),

    post: (url, data = {}, encoded = false) => request('POST', url, data, encoded),

    patch: (url, data = {}, encoded = false) => request('PATCH', url, data, encoded),

    delete: url => request('DELETE', url)
};

function request(method, url, data, encoded) {
    return new Promise((resolve, reject) => {
        let xhr = new XMLHttpRequest();
        xhr.onload = function () {
            let data = JSON.parse(this.response);
            if (this.status >= 200 && this.status < 300) {
                return resolve(data);
            } else {
                return reject(data);
            }
        };
        xhr.open(method, 'api' + url, true);
        let type = encoded ? 'multipart/form-data' : 'application/json';
        xhr.setRequestHeader('Content-Type', type);
        xhr.setRequestHeader('Accept', 'application/json');
        if (data) {
            if (encoded) {
                xhr.send(data);
            } else {
                xhr.send(JSON.stringify(data));
            }
        } else {
            xhr.send();
        }
    });
}