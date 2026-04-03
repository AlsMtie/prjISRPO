class DishCard extends HTMLElement {
    connectedCallback() {
        const id = this.getAttribute('data-id') || Date.now().toString();
        const image = this.getAttribute('image') || '../images/default.png';
        const name = this.getAttribute('name') || '';
        const gram = this.getAttribute('gram') || '500';
        const gr = this.getAttribute('gr') || 'гр.';
        const price = parseInt(this.getAttribute('price')) || 0;
        const sostav = this.getAttribute('sostav') || '';

        this.innerHTML = `
            <div class="dish-card">
                <div class="dish-image">
                    <img src="${image}" alt="${name}" onerror="this.src='../DishesImg/Goryachee_1.png'">
                </div>
                <div class="dish-content">
                    <div class="dish-title">
                        <span class="dish-name">${name}</span>
                        <span class="dish-gram">${gram} ${gr}</span>
                    </div>
                    <div class="dish-sostav">
                        <p class="sostav-text">${sostav}</p>
                    </div>
                    <div class="price-container">
                        <span class="dish-price">
                            ${price} <span class="currency">₽</span>
                        </span>
                        <button class="add-to-cart-btn" data-id="${id}" data-name="${name}" data-price="${price}" data-image="${image}">
                            <img src="../icons/cart.png" alt="В корзину" class="cart-icon">
                            <span class="add-plus">+</span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        const addButton = this.querySelector('.add-to-cart-btn');
        if (addButton) {
            addButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                const id = addButton.getAttribute('data-id');
                const name = addButton.getAttribute('data-name');
                const price = parseInt(addButton.getAttribute('data-price'));
                const image = addButton.getAttribute('data-image');

                if (typeof window.addToCart === 'function') {
                    window.addToCart({ id, name, price, image });
                    if (typeof window.updateCartCount === 'function') {
                        window.updateCartCount();
                    }
                }
            });
        }
    }
}

if (!customElements.get('dish-card')) {
    customElements.define('dish-card', DishCard);
}