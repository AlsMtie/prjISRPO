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
    
    const inputs = form.querySelectorAll('input, textarea');
    inputs.forEach(input => {
        if (input.type !== 'password' && input.value) {
            input.value = sanitize(input.value);
        }
    });
    return true;
}