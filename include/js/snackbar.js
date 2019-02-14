let snackbarQueue = [];

function snackbar(message, type = 'info') {
    let sb = document.createElement('div');
    sb.classList.add('snackbar');
    setSnackbarStyle(sb, type);
    sb.appendChild(document.createTextNode(message));
    snackbarQueue.push(sb);
    if (snackbarQueue.length === 1) {
        showSnackbar();
    }
}

function showSnackbar() {
    let el = snackbarQueue.shift();
    document.getElementsByTagName('body')[0].appendChild(el);
    el.classList.add('show');
    setTimeout(function () {
        el.classList.remove('show');
        if (snackbarQueue.length > 0) {
            showSnackbar();
        }
        el.remove();
    }, 3000);
}

function setSnackbarStyle(el, type) {
    let i = document.createElement('i');
    switch (type) {
        case 'error':
            i.classList.add('fas', 'fa-exclamation-circle');
            el.classList.add('error');
            break;
        case 'warn':
            i.classList.add('fas', 'fa-exclamation-triangle');
            el.classList.add('warn');
            break;
        case 'info':
            i.classList.add('fas', 'fa-exclamation-circle');
            el.classList.add('info');
            break;
        case 'success':
            i.classList.add('fas', 'fa-check-circle');
            el.classList.add('success');
            break;
        default:
            break;
    }
    el.appendChild(i);
}