const api = {
    post: (url, data) => new Promise((resolve, reject) => {
        fetch(url, {
            method: 'POST',
            cache: 'no-cache',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        }).then(response => {
            if (!response.ok) {
                response.json().then(data => reject(data));
            } else {
                response.json().then(data => resolve(data));
            }
        });
    }),

    get: (url) => new Promise((resolve, reject) => {
        fetch(url, {
            method: 'GET',
            cache: 'no-cache',
            headers: {
                'Accept': 'application/json'
            }
        }).then(response => {
            if (!response.ok) {
                return reject(new Error('Failed to retrieve data from "' + url + '"'));
            } else {
                response.json().then(data => resolve(data));
            }
        })
    }),

    load: (url, elid) => new Promise((resolve, reject) => {
        fetch(url).then(response => {
            if (!response.ok) {
                reject(new Error('Failed to load HTML from "' + url + '"'))
            } else {
                response.text().then(data => {
                    document.getElementById(elid).innerHTML = data;
                    resolve(true);
                });
            }
        });
    })
};