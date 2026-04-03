class CartItem extends HTMLElement {
    connectedCallback() {
        const id = this.getAttribute('data-id');
        const image = this.getAttribute('image') || '';
        const name = this.getAttribute('name') || '';
        const price = parseInt(this.getAttribute('price')) || 0;
        const quantity = parseInt(this.getAttribute('quantity')) || 1;

        this.innerHTML = `
            <div class="cart-item" data-id="${id}">
                <img src="${image}" alt="${name}" class="cart-item-image">
                <span class="cart-item-name">${name}</span>
                <span class="cart-item-price">${price} ₽</span>
                <button class="cart-item-minus">
                    <img src="../icons/minus.png" alt="Удалить">
                </button>
                <span class="cart-item-quantity">${quantity}</span>
                <button class="cart-item-plus">
                    <img src="../icons/plus.png" alt="Добавить">
                </button>
                <span class="cart-item-total">${price * quantity} ₽</span>
            </div>
        `;

        const minusBtn = this.querySelector('.cart-item-minus');
        const plusBtn = this.querySelector('.cart-item-plus');
        const quantitySpan = this.querySelector('.cart-item-quantity');
        const totalSpan = this.querySelector('.cart-item-total');

        minusBtn.addEventListener('click', () => {
            let newQuantity = parseInt(quantitySpan.textContent) - 1;
            if (newQuantity < 1) {
                this.remove();
                if (typeof window.updateCartItemQuantity === 'function') {
                    window.updateCartItemQuantity(id, 0);
                }
                return;
            }
            quantitySpan.textContent = newQuantity;
            totalSpan.textContent = (price * newQuantity) + ' ₽';
            if (typeof window.updateCartItemQuantity === 'function') {
                window.updateCartItemQuantity(id, newQuantity);
            }
        });

        plusBtn.addEventListener('click', () => {
            let newQuantity = parseInt(quantitySpan.textContent) + 1;
            quantitySpan.textContent = newQuantity;
            totalSpan.textContent = (price * newQuantity) + ' ₽';
            if (typeof window.updateCartItemQuantity === 'function') {
                window.updateCartItemQuantity(id, newQuantity);
            }
        });
    }
}

if (!customElements.get('cart-item')) {
    customElements.define('cart-item', CartItem);
}