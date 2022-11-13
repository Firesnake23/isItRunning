async function apiCall(url, data) {
    return await fetch(url, {
        body: JSON.stringify(data),
        headers: {
            'content-type': 'application/json'
        },
        method: 'post',
        redirect: 'manual',
    });
}