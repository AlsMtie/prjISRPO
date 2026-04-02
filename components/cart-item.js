class CartItem extends HTMLElement {
    connectedCallback() {
        const image = this.getAttribute('image') || '';
        const name = this.getAttribute('name') || '';
        const price = parseInt(this.getAttribute('price')) || 0;
        const quantity = parseInt(this.getAttribute('quantity')) || 1;
        const itemId = this.getAttribute('id') || Date.now();

        this.innerHTML = `
            <div class="cart-item" data-id="${itemId}">
                <img src="${image}" alt="${name}" class="cart-item-image">
                <span class="cart-item-name">${name}</span>
                <span class="cart-item-price">${price} ₽</span>
                <button class="cart-item-minus">-</button>
                <span class="cart-item-quantity">${quantity}</span>
                <button class="cart-item-plus">+</button>
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
                updateCartSummary();
                return;
            }
            quantitySpan.textContent = newQuantity;
            totalSpan.textContent = (price * newQuantity) + ' ₽';
            updateCartSummary();
        });

        plusBtn.addEventListener('click', () => {
            let newQuantity = parseInt(quantitySpan.textContent) + 1;
            quantitySpan.textContent = newQuantity;
            totalSpan.textContent = (price * newQuantity) + ' ₽';
            updateCartSummary();
        });
    }
}

customElements.define('cart-item', CartItem);