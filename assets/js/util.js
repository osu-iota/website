function hide(el) {
    el.style.display = 'none';
}

function show(el, display = 'block') {
    el.style.display = display;
}

function freezeEvent(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
}