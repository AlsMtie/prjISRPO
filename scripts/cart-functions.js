window.addToCart = function (item) {

    //получаем корзину из localStorage
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        cart = JSON.parse(cart);
    } else {
        cart = [];
    }

    //проверяем, есть ли уже такой товар
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

    //сохраняем обратно
    localStorage.setItem('tomy_cart', JSON.stringify(cart));

    //обновляем счетчик
    window.updateCartCount();
};

//удаление товара из корзины
window.removeFromCart = function (itemId) {
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        cart = JSON.parse(cart);
        cart = cart.filter(item => item.id != itemId);
        localStorage.setItem('tomy_cart', JSON.stringify(cart));
    }
    window.updateCartCount();
};

//обновление количества товара
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

//очистка корзины
window.clearCart = function () {
    localStorage.removeItem('tomy_cart');
    window.updateCartCount();
};

//получение корзины
window.getCart = function () {
    let cart = localStorage.getItem('tomy_cart');
    if (cart) {
        return JSON.parse(cart);
    }
    return [];
};

//подсчет количества товаров в корзине
window.getCartCount = function () {
    const cart = window.getCart();
    return cart.reduce((sum, item) => sum + item.quantity, 0);
};

//подсчет общей суммы
window.getCartTotal = function () {
    const cart = window.getCart();
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
};


//обновление счетчика
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


document.addEventListener('DOMContentLoaded', function () {
    window.updateCartCount();
});