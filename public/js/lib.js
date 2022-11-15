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

function fillDataTarget(element) {
    let sourceId = element.getAttribute('data-source').replace('#', '');
    let targetId = element.getAttribute('data-target').replace('#', '');
    
    console.log(sourceId);
    console.log(targetId);

    let sourceHtml = document.getElementById(sourceId).innerHTML;
    let targetElement = document.getElementById(targetId);

    targetElement.className = targetElement.className.replace(' d-none', '');

    targetElement.innerHTML = sourceHtml;
}