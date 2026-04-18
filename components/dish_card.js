class DishCard extends HTMLElement {
    connectedCallback() {
        const id = this.getAttribute('data-id') || 0;
        const img = this.getAttribute('image') || '../src/Dishesimg/Goryachee_1.png';
        const name = this.getAttribute('name') || '';
        const gram = this.getAttribute('gram') || '500';
        const gr = this.getAttribute('gr') || 'гр.';
        const price = parseInt(this.getAttribute('price')) || 0;
        const sostav = this.getAttribute('sostav') || '';

        this.innerHTML = `
            <div class="dish-card">
                <div class="dish-image"><img src="${img}"></div>
                <div class="dish-content">
                    <div class="dish-title">
                        <span class="dish-name">${name}</span>
                        <span class="dish-gram">${gram} ${gr}</span>
                    </div>
                    <div class="dish-sostav"><p class="sostav-text">${sostav}</p></div>
                    <div class="price-container">
                        <span class="dish-price">${price} <span class="currency">₽</span></span>
                        <button class="add-to-cart-btn" data-id="${id}" data-name="${name}" data-price="${price}" data-image="${img}">
                            <img src="../src/icons/cart.png" class="cart-icon"><span class="add-plus">+</span>
                        </button>
                    </div>
                </div>
            </div>
        `;

        const btn = this.querySelector('.add-to-cart-btn');
        if (!btn) return;

        var self = this;
        btn.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!window.isLoggedIn) {
                alert('Войдите в аккаунт');
                window.location.href = 'auth.php';
                return;
            }
            
            var id = btn.getAttribute('data-id');
            var name = btn.getAttribute('data-name');
            var price = parseInt(btn.getAttribute('data-price'));
            var img = btn.getAttribute('data-image');
            
            if (window.addToCart) {
                window.addToCart({ id: id, name: name, price: price, image: img });
                if (window.updateCartCount) {
                    window.updateCartCount();
                }
            }
        };
    }
}

customElements.define('dish-card', DishCard);