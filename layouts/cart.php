<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Корзина</title>

    <link rel="stylesheet" href="../style/fonts/fonts.css">

    <link rel="stylesheet" href="../style/css/cart.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/cart-item.css">
    
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../components/cart-item.js"></script>
</head>
<body>
    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $base = 'tomuLvovaAlesya';
    
    $conn = mysqli_connect($host, $user, $pass, $base);
    $query = "SELECT id, address FROM cafes WHERE is_active = 1";
    $result = mysqli_query($conn, $query);
    $cafes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $cafes[] = $row;
    }
    mysqli_close($conn);
    ?>
    <my-header></my-header>
    
    <div class="cart-page">
        <div class="cart-left">
            <div class="cart-header">
                <h2>Ваша корзина <span class="cart-count" id="cart-count">0</span></h2>
                <button class="clear-cart" id="clear-cart-btn">
                    <img src="../icons/trash_cart.png" alt="Очистить корзину">
                    <span class="clear-text">Очистить корзину</span>
                </button>
            </div>
            <div class="cart-items-list" id="cart-items-list">
                <p style="text-align: center; font-family: 'El Messiri'; color: #9F9F9F;">Корзина пуста</p>
            </div>
        </div>

        <div class="cart-right">
            <div class="delivery-methods">
                <button class="method-btn active" data-method="pickup">Самовывоз</button>
                <button class="method-btn" data-method="delivery">Доставка</button>
            </div>
            <div class="method-content" id="method-content"></div>
            <div class="checkout-footer">
                <div class="checkout-info">
                    <span class="checkout-items" id="checkout-items">0 товаров</span>
                    <span class="checkout-total" id="checkout-total">0 ₽</span>
                </div>
                <button class="pay-btn" id="pay-btn">Оплатить</button>
            </div>
        </div>
    </div>

    <my-footer></my-footer>

    <div id="modal" class="modal">
        <div class="modal-content">
            <p>Заказ принят!</p>
        </div>
    </div>

    <script>
        const cafes = <?php echo json_encode($cafes); ?>;
        let cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
        let selectedCafeId = cafes.length > 0 ? cafes[0].id : null;
        
        window.updateCartItemQuantity = function(itemId, newQuantity) {
            const itemIndex = cart.findIndex(item => item.id == itemId);
            if (itemIndex !== -1) {
                if (newQuantity <= 0) {
                    cart.splice(itemIndex, 1);
                } else {
                    cart[itemIndex].quantity = newQuantity;
                }
                localStorage.setItem('tomy_cart', JSON.stringify(cart));
                updateSummary();
                updateCartCount();
                
                if (cart.length === 0) {
                    renderCartItems();
                }
            }
        };
        
        function updateCartCount() {
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            const cartCountSpan = document.getElementById('cart-count');
            if (cartCountSpan) cartCountSpan.textContent = count;
        }
        
        function renderCartItems() {
            const container = document.getElementById('cart-items-list');
            const cartCountSpan = document.getElementById('cart-count');
            
            if (cart.length === 0) {
                container.innerHTML = '<p style="text-align: center; font-family: \'El Messiri\'; color: #9F9F9F;">Корзина пуста</p>';
                cartCountSpan.textContent = '0';
                updateSummary();
                return;
            }
            
            container.innerHTML = '';
            cart.forEach((item) => {
                const cartItem = document.createElement('cart-item');
                cartItem.setAttribute('data-id', item.id);
                cartItem.setAttribute('image', item.image);
                cartItem.setAttribute('name', item.name);
                cartItem.setAttribute('price', item.price);
                cartItem.setAttribute('quantity', item.quantity);
                container.appendChild(cartItem);
            });
            
            const count = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCountSpan.textContent = count;
            updateSummary();
        }
        
        function updateSummary() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const discount = 0;
            const total = subtotal - discount;
            const itemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            
            const subtotalItemsSpan = document.getElementById('subtotal-items');
            const subtotalAmountSpan = document.getElementById('subtotal-amount');
            const discountAmountSpan = document.getElementById('discount-amount');
            const totalAmountSpan = document.getElementById('total-amount');
            const checkoutItemsSpan = document.getElementById('checkout-items');
            const checkoutTotalSpan = document.getElementById('checkout-total');
            
            if (subtotalItemsSpan) subtotalItemsSpan.textContent = itemsCount;
            if (subtotalAmountSpan) subtotalAmountSpan.textContent = subtotal;
            if (discountAmountSpan) discountAmountSpan.textContent = discount;
            if (totalAmountSpan) totalAmountSpan.textContent = total;
            if (checkoutItemsSpan) checkoutItemsSpan.textContent = itemsCount + ' товаров';
            if (checkoutTotalSpan) checkoutTotalSpan.textContent = total + ' ₽';
        }
        
        function updateCartAndSave() {
            localStorage.setItem('tomy_cart', JSON.stringify(cart));
            renderCartItems();
        }
        
        document.getElementById('clear-cart-btn').addEventListener('click', () => {
            cart = [];
            updateCartAndSave();
        });
        
        function renderPickupAddresses() {
            if (cafes.length === 0) {
                return '<div class="pickup-address">Нет доступных адресов</div>';
            }
            
            let html = '<div class="pickup-addresses">';
            cafes.forEach(cafe => {
                html += `
                    <label class="pickup-address-item ${selectedCafeId == cafe.id ? 'active' : ''}">
                        <input type="radio" name="pickup_address" value="${cafe.id}" ${selectedCafeId == cafe.id ? 'checked' : ''}>
                        <span>${cafe.address}</span>
                    </label>
                `;
            });
            html += '</div>';
            return html;
        }
        
        function renderMethodContent(method) {
            const container = document.getElementById('method-content');
            if (method === 'pickup') {
                container.innerHTML = `
                    <div class="delivery-time">
                        <span>Время</span>
                        <span>Как можно скорее</span>
                    </div>
                    <div class="pickup-section">
                        <div class="pickup-label">Место выдачи</div>
                        ${renderPickupAddresses()}
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
                    <div class="promo-divider"></div>
                    <div class="promo-input-wrapper">
                        <input type="text" class="promo-input" placeholder="Введите промокод">
                        <button class="apply-promo-btn">Применить</button>
                    </div>
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
                
                const radioButtons = container.querySelectorAll('input[name="pickup_address"]');
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        selectedCafeId = parseInt(e.target.value);
                        const items = container.querySelectorAll('.pickup-address-item');
                        items.forEach(item => {
                            item.classList.remove('active');
                        });
                        e.target.closest('.pickup-address-item').classList.add('active');
                    });
                });
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
                    <div class="promo-divider"></div>
                    <div class="promo-input-wrapper">
                        <input type="text" class="promo-input" placeholder="Введите промокод">
                        <button class="apply-promo-btn">Применить</button>
                    </div>
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
            
            const applyBonusBtn = container.querySelector('.apply-bonus-btn');
            if (applyBonusBtn) {
                applyBonusBtn.addEventListener('click', () => {
                    alert('Бонусы применены!');
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
        renderCartItems();
        
        document.getElementById('pay-btn').addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Корзина пуста!');
                return;
            }
            const modal = document.getElementById('modal');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.style.display = 'none';
                cart = [];
                updateCartAndSave();
                window.location.href = '../index.php';
            }, 2000);
        });
    </script>
</body>
</html>