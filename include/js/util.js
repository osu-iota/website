function hide(el) {
    el.style.display = 'none';
}

function show(el) {
    el.style.display = 'block';
}

function freezeEvent(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
}