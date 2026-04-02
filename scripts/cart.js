let cartItems = [];

function loadCartFromStorage() {
    const saved = localStorage.getItem('cart');
    if (saved) {
        cartItems = JSON.parse(saved);
        renderCartItems();
    }
}

function saveCartToStorage() {
    localStorage.setItem('cart', JSON.stringify(cartItems));
}

function renderCartItems() {
    const container = document.getElementById('cart-items-list');
    const cartCountSpan = document.getElementById('cart-count');

    if (cartItems.length === 0) {
        container.innerHTML = '<p style="text-align: center; font-family: \'El Messiri\'; color: #9F9F9F;">Корзина пуста</p>';
        cartCountSpan.textContent = '0 позиций';
        updateSummary();
        return;
    }

    container.innerHTML = '';
    cartItems.forEach((item, index) => {
        const cartItem = document.createElement('cart-item');
        cartItem.setAttribute('image', item.image);
        cartItem.setAttribute('name', item.name);
        cartItem.setAttribute('price', item.price);
        cartItem.setAttribute('quantity', item.quantity);
        cartItem.setAttribute('id', index);
        container.appendChild(cartItem);
    });

    cartCountSpan.textContent = cartItems.length + ' ' + getWordForm(cartItems.length);
    updateSummary();
}

function getWordForm(n) {
    if (n % 10 === 1 && n % 100 !== 11) return 'позиция';
    if (n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 10 || n % 100 >= 20)) return 'позиции';
    return 'позиций';
}

function updateSummary() {
    const subtotal = cartItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const discount = 0;
    const total = subtotal - discount;
    const itemsCount = cartItems.reduce((sum, item) => sum + item.quantity, 0);

    document.getElementById('subtotal-items').textContent = itemsCount;
    document.getElementById('subtotal-amount').textContent = subtotal;
    document.getElementById('discount-amount').textContent = discount;
    document.getElementById('total-amount').textContent = total;
    document.getElementById('checkout-items').textContent = itemsCount + ' ' + getWordForm(itemsCount);
    document.getElementById('checkout-total').textContent = total + ' ₽';
}

document.getElementById('clear-cart-btn').addEventListener('click', () => {
    cartItems = [];
    saveCartToStorage();
    renderCartItems();
});

function showModal() {
    const modal = document.getElementById('modal');
    modal.style.display = 'block';
    setTimeout(() => {
        modal.style.display = 'none';
        window.location.href = '../index.php';
    }, 2000);
}

function renderMethodContent(method) {
    const container = document.getElementById('method-content');
    if (method === 'pickup') {
        container.innerHTML = `
            <div class="delivery-time">
                <span>Время</span>
                <span>Как можно скорее</span>
            </div>
            <div class="pickup-address">
                Место выдачи
                <div>г. Якутск ул. Петра Алексеева 25</div>
            </div>
            <div class="payment-type">
                <span>Тип оплаты</span>
                <span>Оплата по карте</span>
            </div>
            <div class="bonus-text">Бонусы</div>
            <div class="divider"></div>
            <div class="summary-row">
                <span>Ваши бонусы</span>
                <span>100</span>
            </div>
            <div class="bonus-input-wrapper">
                <input type="text" class="bonus-input" placeholder="Введите количество бонусов">
                <button class="apply-bonus-btn">Применить</button>
            </div>
            <div class="promo-text">Промокод</div>
            <div class="divider"></div>
            <div class="promo-input-wrapper">
                <input type="text" class="promo-input" placeholder="Введите промокод">
                <button class="apply-promo-btn">Применить</button>
            </div>
            <div class="divider"></div>
            <div class="summary-text">Подробности о стоимости</div>
            <div class="divider"></div>
            <div class="summary-row">
                <span>Подытог (<span id="subtotal-items">0</span>)</span>
                <span><span id="subtotal-amount">0</span> ₽</span>
            </div>
            <div class="summary-row">
                <span>Скидка</span>
                <span><span id="discount-amount">0</span> ₽</span>
            </div>
            <div class="summary-row summary-total">
                <span>Итого</span>
                <span><span id="total-amount">0</span> ₽</span>
            </div>
        `;
    } else {
        container.innerHTML = `
            <div class="delivery-time">
                <span>Время доставки</span>
                <span>Как можно скорее</span>
            </div>
            <div class="payment-type">
                <span>Тип оплаты</span>
                <span>Оплата по карте</span>
            </div>
            <div class="bonus-text">Бонусы</div>
            <div class="divider"></div>
            <div class="summary-row">
                <span>Ваши бонусы</span>
                <span>100</span>
            </div>
            <div class="bonus-input-wrapper">
                <input type="text" class="bonus-input" placeholder="Введите количество бонусов">
                <button class="apply-bonus-btn">Применить</button>
            </div>
            <div class="promo-text">Промокод</div>
            <div class="divider"></div>
            <div class="promo-input-wrapper">
                <input type="text" class="promo-input" placeholder="Введите промокод">
                <button class="apply-promo-btn">Применить</button>
            </div>
            <div class="divider"></div>
            <div class="summary-text">Подробности о стоимости</div>
            <div class="divider"></div>
            <div class="summary-row">
                <span>Подытог (<span id="subtotal-items">0</span>)</span>
                <span><span id="subtotal-amount">0</span> ₽</span>
            </div>
            <div class="summary-row">
                <span>Скидка</span>
                <span><span id="discount-amount">0</span> ₽</span>
            </div>
            <div class="summary-row summary-total">
                <span>Итого</span>
                <span><span id="total-amount">0</span> ₽</span>
            </div>
        `;
    }

    const payBonusBtn = container.querySelector('.apply-bonus-btn');
    if (payBonusBtn) {
        payBonusBtn.addEventListener('click', () => {
            alert('Бонусы применены!');
        });
    }

    const applyPromoBtn = container.querySelector('.apply-promo-btn');
    if (applyPromoBtn) {
        applyPromoBtn.addEventListener('click', () => {
            alert('Промокод применен!');
        });
    }

    updateSummary();
}

document.querySelectorAll('.method-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        renderMethodContent(btn.dataset.method);
    });
});

renderMethodContent('pickup');
loadCartFromStorage();

const payBtn = document.createElement('button');
payBtn.className = 'pay-btn';
payBtn.textContent = 'Оплатить';
payBtn.onclick = showModal;

const footer = document.createElement('div');
footer.className = 'checkout-footer';
footer.innerHTML = `
    <div class="checkout-info">
        <span class="checkout-items" id="checkout-items">0 позиций</span>
        <span class="checkout-total" id="checkout-total">0 ₽</span>
    </div>
`;
footer.appendChild(payBtn);
document.querySelector('.cart-right').appendChild(footer);