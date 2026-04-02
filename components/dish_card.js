class DishCard extends HTMLElement {
    connectedCallback() {
        const image = this.getAttribute('image') || '../images/Goryachee_1.png';
        const icon_cart = this.getAttribute('icon_cart') || '../icons/cart.png';
        const name = this.getAttribute('name') || '';
        const gram = this.getAttribute('gram') || '500';
        const gr = this.getAttribute('gr') || '';
        const price = this.getAttribute('price') || '450';
        const sostav = this.getAttribute('sostav') || 'sostav';

        this.innerHTML = `
            <div class="dish-card">
                <div class="dish-image">
                    <img src="${image}" alt="imgdish">
                </div>
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
                    <button class="add-to-cart-btn">
                        <img src="../${icon_cart}" alt="icon" class="cart-icon">
                        <span class="add-plus">+</span>
                    </button>
                </div>
            </div>

            </div>`;
    }
}
customElements.define('dish-card', DishCard);