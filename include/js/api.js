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
    })
};