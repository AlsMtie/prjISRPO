window.addToCart = function(item) {
    var cartStr = localStorage.getItem('tomy_cart');
    var cart = [];
    if (cartStr) {
        cart = JSON.parse(cartStr);
    }
    
    var existingIndex = -1;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id == item.id) {
            existingIndex = i;
            break;
        }
    }
    
    if (existingIndex !== -1) {
        cart[existingIndex].quantity = cart[existingIndex].quantity + 1;
    } else {
        var newItem = {
            id: item.id,
            name: item.name,
            price: item.price,
            image: item.image,
            quantity: 1
        };
        cart.push(newItem);
    }
    
    localStorage.setItem('tomy_cart', JSON.stringify(cart));
    window.updateCartCount();
};

window.removeFromCart = function(itemId) {
    var cartStr = localStorage.getItem('tomy_cart');
    if (!cartStr) return;
    var cart = JSON.parse(cartStr);
    var newCart = [];
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id != itemId) {
            newCart.push(cart[i]);
        }
    }
    localStorage.setItem('tomy_cart', JSON.stringify(newCart));
    window.updateCartCount();
};

window.updateQuantity = function(itemId, newQuantity) {
    var cartStr = localStorage.getItem('tomy_cart');
    if (!cartStr) return;
    var cart = JSON.parse(cartStr);
    var found = false;
    for (var i = 0; i < cart.length; i++) {
        if (cart[i].id == itemId) {
            if (newQuantity <= 0) {
                window.removeFromCart(itemId);
            } else {
                cart[i].quantity = newQuantity;
                localStorage.setItem('tomy_cart', JSON.stringify(cart));
            }
            found = true;
            break;
        }
    }
    window.updateCartCount();
};

window.clearCart = function() {
    localStorage.removeItem('tomy_cart');
    window.updateCartCount();
};

window.getCart = function() {
    var cartStr = localStorage.getItem('tomy_cart');
    if (cartStr) {
        return JSON.parse(cartStr);
    }
    return [];
};

window.getCartCount = function() {
    var cart = window.getCart();
    var sum = 0;
    for (var i = 0; i < cart.length; i++) {
        sum = sum + cart[i].quantity;
    }
    return sum;
};

window.getCartTotal = function() {
    var cart = window.getCart();
    var total = 0;
    for (var i = 0; i < cart.length; i++) {
        total = total + (cart[i].price * cart[i].quantity);
    }
    return total;
};

window.updateCartCount = function() {
    var count = window.getCartCount();
    var cartButtons = document.querySelectorAll('.cart-button');
    
    for (var i = 0; i < cartButtons.length; i++) {
        var btn = cartButtons[i];
        var existingBadge = btn.querySelector('.cart-badge');
        if (existingBadge) {
            existingBadge.remove();
        }
        
        if (count > 0) {
            var badge = document.createElement('span');
            badge.className = 'cart-badge';
            badge.textContent = count;
            badge.style.cssText = 'position: absolute; top: -5px; right: -5px; background-color: #B13720; color: white; border-radius: 50%; width: 22px; height: 22px; font-size: 12px; display: flex; align-items: center; justify-content: center; font-family: \'El Messiri\'; font-weight: bold;';
            btn.style.position = 'relative';
            btn.appendChild(badge);
        }
    }
};

window.loadCart = function() {
    var cart = window.getCart();
    if (window.updateCartCount) {
        window.updateCartCount();
    }
    return cart;
};

window.saveCart = function() {
};

document.addEventListener('DOMContentLoaded', function() {
    window.updateCartCount();
});