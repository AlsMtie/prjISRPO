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


//Давай изменим написание моего кода более примитивно (это не означает коротко!) нужно чтобы сложные и не базовые функции и способы написания были изменены на более простые, максимально примитивные, чтобы было похоже что это сделал студент или новичок, но чтобы все работало. Особое внимание удели, чтобы мой код никак логически и по дизайну не изменился, только способ написания! Сделай комментарии в сложные участки, чтобы больше понимать код