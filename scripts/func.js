function sanitize(str) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#x27;',
        "/": '&#x2F;'
    };
    const reg = /[&<>"'/]/g;
    return str.replace(reg, (el) => map[el]);
}

function sanitizeForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;

    for (let i = 0; i < form.elements.length; i++) {
        const input = form.elements[i];
        if (input.type !== 'password' && input.value) {
            input.value = sanitize(input.value);
        }
    }
    return true;
}