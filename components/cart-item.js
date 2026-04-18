class CartItem extends HTMLElement {
    connectedCallback() {
        const id = this.getAttribute('data-id');
        const image = this.getAttribute('image') || '';
        const name = this.getAttribute('name') || '';
        const price = parseInt(this.getAttribute('price')) || 0;
        const qty = parseInt(this.getAttribute('quantity')) || 1;

        this.innerHTML = `
            <div class="cart-item" data-id="${id}">
                <img src="${image}" class="cart-item-image">
                <span class="cart-item-name">${name}</span>
                <span class="cart-item-price">${price} ₽</span>
                <button class="cart-item-minus">
                    <img src="../src/icons/minus.png" alt="Удалить">
                </button>
                <span class="cart-item-quantity">${qty}</span>
                <button class="cart-item-plus">
                    <img src="../src/icons/plus.png" alt="Добавить">
                </button>
                <span class="cart-item-total">${price * qty} ₽</span>
            </div>
        `;

        const minus = this.querySelector('.cart-item-minus');
        const plus = this.querySelector('.cart-item-plus');
        const span = this.querySelector('.cart-item-quantity');
        const total = this.querySelector('.cart-item-total');

        var self = this;
        minus.onclick = function() {
            var n = parseInt(span.innerText) - 1;
            if (n < 1) {
                self.remove();
                if (window.updateCartItemQuantity) {
                    window.updateCartItemQuantity(id, 0);
                }
                return;
            }
            span.innerText = n;
            total.innerText = (price * n) + ' ₽';
            if (window.updateCartItemQuantity) {
                window.updateCartItemQuantity(id, n);
            }
        };
        
        plus.onclick = function() {
            var n = parseInt(span.innerText) + 1;
            span.innerText = n;
            total.innerText = (price * n) + ' ₽';
            if (window.updateCartItemQuantity) {
                window.updateCartItemQuantity(id, n);
            }
        };
    }
}

customElements.define('cart-item', CartItem);