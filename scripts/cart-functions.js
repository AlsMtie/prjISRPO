window.addToCart = function (item) {

    // Получаем корзину из localStorage
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        cart = JSON.parse(cart);
    } else {
        cart = [];
    }

    // Проверяем, есть ли уже такой товар
    const existingIndex = cart.findIndex(cartItem => cartItem.id == item.id);

    if (existingIndex !== -1) {
        cart[existingIndex].quantity += 1;
    } else {
        cart.push({
            id: item.id,
            name: item.name,
            price: item.price,
            image: item.image,
            quantity: 1
        });
    }

    // Сохраняем обратно
    localStorage.setItem('tomy_cart', JSON.stringify(cart));

    // Обновляем счетчик
    window.updateCartCount();
};

// Удаление товара из корзины
window.removeFromCart = function (itemId) {
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        cart = JSON.parse(cart);
        cart = cart.filter(item => item.id != itemId);
        localStorage.setItem('tomy_cart', JSON.stringify(cart));
    }
    window.updateCartCount();
};

// Обновление количества товара
window.updateQuantity = function (itemId, newQuantity) {
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        cart = JSON.parse(cart);
        const item = cart.find(item => item.id == itemId);
        if (item) {
            if (newQuantity <= 0) {
                window.removeFromCart(itemId);
            } else {
                item.quantity = newQuantity;
                localStorage.setItem('tomy_cart', JSON.stringify(cart));
            }
        }
    }
    window.updateCartCount();
};

// Очистка корзины
window.clearCart = function () {
    localStorage.removeItem('tomy_cart');
    window.updateCartCount();
};

// Получение корзины
window.getCart = function () {
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        return JSON.parse(cart);
    }
    return [];
};

// Подсчет количества товаров в корзине
window.getCartCount = function () {
    const cart = window.getCart();
    return cart.reduce((sum, item) => sum + item.quantity, 0);
};

// Подсчет общей суммы
window.getCartTotal = function () {
    const cart = window.getCart();
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
};


// Обновление счетчика
window.updateCartCount = function () {
    const count = window.getCartCount();
    const cartButtons = document.querySelectorAll('.cart-button');

    cartButtons.forEach(btn => {
        const existingBadge = btn.querySelector('.cart-badge');
        if (existingBadge) {
            existingBadge.remove();
        }

        if (count > 0) {
            const badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.textContent = count;
            badge.style.cssText = `
                position: absolute;
                top: -5px;
                right: -5px;
                background-color: #B13720;
                color: white;
                border-radius: 50%;
                width: 22px;
                height: 22px;
                font-size: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'El Messiri';
                font-weight: bold;
            `;
            btn.style.position = 'relative';
            btn.appendChild(badge);
        }
    });
};
window.loadCart = function () {
    const cart = window.getCart();
    if (window.updateCartCount) window.updateCartCount();
    return cart;
};

window.saveCart = function () {
    console.log('saveCart вызван');
};

document.addEventListener('DOMContentLoaded', function () {
    window.updateCartCount();
});