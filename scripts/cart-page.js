function renderCartItems() {
    const container = document.getElementById('cart-items-list');
    if (!container) return;

    const cart = window.getCart ? window.getCart() : JSON.parse(localStorage.getItem('tomy_cart') || '[]');

    if (cart.length === 0) {
        container.innerHTML = '<p style="text-align: center; font-family: \'El Messiri\'; color: #9F9F9F;">Корзина пуста</p>';
        updateCartSummary();
        return;
    }

    container.innerHTML = '';
    cart.forEach((item, index) => {
        const cartItem = document.createElement('cart-item');
        cartItem.setAttribute('data-id', item.id);
        cartItem.setAttribute('image', item.image);
        cartItem.setAttribute('name', item.name);
        cartItem.setAttribute('price', item.price);
        cartItem.setAttribute('quantity', item.quantity);
        container.appendChild(cartItem);
    });

    updateCartSummary();
}

function updateCartSummary() {
    let cart = [];
    let subtotal = 0;
    let itemsCount = 0;

    if (window.getCart) {
        cart = window.getCart();
        subtotal = window.getCartTotal();
        itemsCount = window.getCartCount();
    } else {
        cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
        subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        itemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
    }

    const subtotalItemsSpan = document.getElementById('subtotal-items');
    const subtotalAmountSpan = document.getElementById('subtotal-amount');
    const totalAmountSpan = document.getElementById('total-amount');
    const checkoutItemsSpan = document.getElementById('checkout-items');
    const checkoutTotalSpan = document.getElementById('checkout-total');
    const cartCountSpan = document.getElementById('cart-count');

    if (subtotalItemsSpan) subtotalItemsSpan.textContent = itemsCount;
    if (subtotalAmountSpan) subtotalAmountSpan.textContent = subtotal;
    if (totalAmountSpan) totalAmountSpan.textContent = subtotal;
    if (checkoutItemsSpan) checkoutItemsSpan.textContent = itemsCount + ' ' + getWordForm(itemsCount);
    if (checkoutTotalSpan) checkoutTotalSpan.textContent = subtotal + ' ₽';
    if (cartCountSpan) cartCountSpan.textContent = itemsCount + ' ' + getWordForm(itemsCount);
}

function getWordForm(n) {
    if (n % 10 === 1 && n % 100 !== 11) return 'товар';
    if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) return 'товара';
    return 'товаров';
}

window.updateItemQuantity = function (itemId, newQuantity) {
    if (window.updateQuantity) {
        window.updateQuantity(itemId, newQuantity);
    } else {
        let cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
        const item = cart.find(i => i.id == itemId);
        if (item) {
            if (newQuantity <= 0) {
                cart = cart.filter(i => i.id != itemId);
            } else {
                item.quantity = newQuantity;
            }
            localStorage.setItem('tomy_cart', JSON.stringify(cart));
        }
    }
    renderCartItems();
    if (window.updateCartCount) window.updateCartCount();
};

window.removeCartItem = function (itemId) {
    if (window.removeFromCart) {
        window.removeFromCart(itemId);
    } else {
        let cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
        cart = cart.filter(i => i.id != itemId);
        localStorage.setItem('tomy_cart', JSON.stringify(cart));
    }
    renderCartItems();
    if (window.updateCartCount) window.updateCartCount();
};

window.updateCartFromItems = function () {
    updateCartSummary();
    if (window.updateCartCount) window.updateCartCount();
};

function initCartPage() {
    renderCartItems();

    const clearBtn = document.getElementById('clear-cart-btn');
    if (clearBtn) {
        clearBtn.addEventListener('click', () => {
            if (window.clearCart) {
                window.clearCart();
            } else {
                localStorage.removeItem('tomy_cart');
            }
            renderCartItems();
            if (window.updateCartCount) window.updateCartCount();
            if (window.showNotification) {
                window.showNotification('Корзина очищена');
            } else {
                alert('Корзина очищена');
            }
        });
    }

    const payBtn = document.getElementById('pay-btn');
    if (payBtn) {
        payBtn.addEventListener('click', () => {
            const cart = window.getCart ? window.getCart() : JSON.parse(localStorage.getItem('tomy_cart') || '[]');
            if (cart.length === 0) {
                alert('Корзина пуста!');
                return;
            }

            const modal = document.getElementById('modal');
            if (modal) {
                modal.style.display = 'block';

                setTimeout(() => {
                    modal.style.display = 'none';
                    if (window.clearCart) {
                        window.clearCart();
                    } else {
                        localStorage.removeItem('tomy_cart');
                    }
                    renderCartItems();
                    if (window.updateCartCount) window.updateCartCount();
                    window.location.href = '../index.php';
                }, 2000);
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', initCartPage);