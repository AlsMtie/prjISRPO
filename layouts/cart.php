<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<div style="text-align: center; padding: 50px; font-family: \'El Messiri\';">
            <h2>Доступ ограничен</h2>
            <p>Чтобы посмотреть корзину, войдите в аккаунт</p>
            <button onclick="window.location.href=\'auth.php\'" style="background: #B13720; color: white; border: none; padding: 10px 30px; border-radius: 30px; cursor: pointer;">Войти</button>
          </div>';
    exit();
}

$host = 'localhost';
$user = 'root';
$pass = '';
$base = 'tomuLvovaAlesya';

$conn = mysqli_connect($host, $user, $pass, $base);
$user_id = $_SESSION['user_id'];

$bonus_res = mysqli_query($conn, "SELECT bonuses FROM Users WHERE id = $user_id");
$user_bonus = mysqli_fetch_assoc($bonus_res);
$user_bonuses = $user_bonus['bonuses'];

$addresses_query = "SELECT id, full_addresses FROM Users_addresses WHERE user_id = $user_id";
$addresses_result = mysqli_query($conn, $addresses_query);
$addresses = [];
while ($row = mysqli_fetch_assoc($addresses_result)) {
    $addresses[] = $row;
}

$cafes_query = "SELECT id, address FROM cafes WHERE is_active = 1";
$cafes_result = mysqli_query($conn, $cafes_query);
$cafes = [];
while ($row = mysqli_fetch_assoc($cafes_result)) {
    $cafes[] = $row;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_address'])) {
    $full = $_POST['full_address'];
    $apt = $_POST['apartment'];
    $ent = $_POST['entrance'];
    $fl = !empty($_POST['floor']) ? $_POST['floor'] : 'NULL';
    $door = $_POST['doorphone'];
    $comment = $_POST['comment'];
    mysqli_query($conn, "INSERT INTO Users_addresses (user_id, full_addresses, apartment, entrance, floor, doorphone, comment) VALUES ($user_id, '$full', '$apt', '$ent', $fl, '$door', '$comment')");
    header('Location: cart.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_bonuses'])) {
    $usedBonuses = (int)$_POST['used_bonuses'];
    $earnBonuses = (int)$_POST['earn_bonuses'];
    mysqli_query($conn, "UPDATE Users SET bonuses = bonuses - $usedBonuses + $earnBonuses WHERE id = $user_id");
    echo json_encode(['success' => true]);
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Корзина</title>

    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/cart.css">
    <link rel="stylesheet" href="../style/css/cart-item.css">
    
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../components/cart-item.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    
    <script>
        window.isLoggedIn = true;
        window.userName = '<?php echo $_SESSION['user_name']; ?>';
        window.userBonuses = <?php echo $user_bonuses; ?>;
    </script>
</head>
<body>
    <my-header></my-header>
    
    <div class="cart-page">
        <div class="cart-left">
            <div class="cart-header">
                <h2>Ваша корзина <span class="cart-count" id="cart-count">0</span></h2>
                <button class="clear-cart" id="clear-cart-btn">
                    <img src="../src/icons/trash_cart.png" alt="Очистить корзину">
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
    
    <div id="addressModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddressModal()">&times;</span>
            <h3>Добавить адрес</h3>
            <form method="POST">
                <input type="hidden" name="add_address" value="1">
                <input type="text" name="full_address" placeholder="Улица, дом" required>
                <input type="text" name="apartment" placeholder="Квартира">
                <input type="text" name="entrance" placeholder="Подъезд">
                <input type="text" name="floor" placeholder="Этаж">
                <input type="text" name="doorphone" placeholder="Домофон">
                <input type="text" name="comment" placeholder="Комментарий">
                <button type="submit">Сохранить</button>
            </form>
        </div>
    </div>

    <script>
        const cafes = <?php echo json_encode($cafes); ?>;
        const addresses = <?php echo json_encode($addresses); ?>;
        let cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
        let selectedPickupId = cafes.length > 0 ? cafes[0].id : null;
        let selectedDeliveryId = addresses.length > 0 ? addresses[0].id : null;
        let usedBonuses = 0;
        let userBonuses = window.userBonuses;
        
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
            const discount = usedBonuses;
            const total = subtotal - discount;
            const itemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const bonusesEarn = Math.floor(subtotal * 0.01);
            
            const subtotalItemsSpan = document.getElementById('subtotal-items');
            const subtotalAmountSpan = document.getElementById('subtotal-amount');
            const discountAmountSpan = document.getElementById('discount-amount');
            const totalAmountSpan = document.getElementById('total-amount');
            const checkoutItemsSpan = document.getElementById('checkout-items');
            const checkoutTotalSpan = document.getElementById('checkout-total');
            const bonusesEarnSpan = document.getElementById('bonuses-earn');
            
            if (subtotalItemsSpan) subtotalItemsSpan.textContent = itemsCount;
            if (subtotalAmountSpan) subtotalAmountSpan.textContent = subtotal;
            if (discountAmountSpan) discountAmountSpan.textContent = discount;
            if (totalAmountSpan) totalAmountSpan.textContent = total < 0 ? 0 : total;
            if (checkoutItemsSpan) checkoutItemsSpan.textContent = itemsCount + ' товаров';
            if (checkoutTotalSpan) checkoutTotalSpan.textContent = (total < 0 ? 0 : total) + ' ₽';
            if (bonusesEarnSpan) bonusesEarnSpan.textContent = '+' + bonusesEarn;
        }
        
        function updateCartAndSave() {
            localStorage.setItem('tomy_cart', JSON.stringify(cart));
            renderCartItems();
        }
        
        document.getElementById('clear-cart-btn').addEventListener('click', () => {
            cart = [];
            usedBonuses = 0;
            updateCartAndSave();
        });
        
        function renderPickupAddresses() {
            if (cafes.length === 0) return '<div>Нет доступных адресов</div>';
            
            let html = '<div class="pickup-addresses">';
            cafes.forEach(cafe => {
                html += `
                    <label class="pickup-address-item ${selectedPickupId == cafe.id ? 'active' : ''}">
                        <input type="radio" name="pickup_address" value="${cafe.id}" ${selectedPickupId == cafe.id ? 'checked' : ''}>
                        <span>${cafe.address}</span>
                    </label>
                `;
            });
            html += '</div>';
            return html;
        }

        function renderDeliveryAddresses() {
            if (addresses.length === 0) return '<div>Нет доступных адресов</div>';
            
            let html = '<div class="pickup-addresses">';
            addresses.forEach(addr => {
                html += `
                    <label class="pickup-address-item ${selectedDeliveryId == addr.id ? 'active' : ''}">
                        <input type="radio" name="delivery_address" value="${addr.id}" ${selectedDeliveryId == addr.id ? 'checked' : ''}>
                        <span>${addr.full_addresses}</span>
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
                        <span id="user-bonuses">${userBonuses}</span>
                    </div>
                    <div class="bonus-input-wrapper">
                        <input type="number" class="bonus-input" id="bonus-input" placeholder="Введите количество бонусов (до ${userBonuses})" min="0" max="${userBonuses}">
                        <button class="apply-bonus-btn" id="apply-bonus-btn">Применить</button>
                    </div>
                    <div class="promo-text">Промокод</div>
                    <div class="promo-divider"></div>
                    <div class="promo-input-wrapper">
                        <input type="text" class="promo-input" id="promo-input" placeholder="Введите промокод">
                        <button class="apply-promo-btn" id="apply-promo-btn">Применить</button>
                    </div>
                    <div class="summary-text">Подробности о стоимости</div>
                    <div class="divider"></div>
                    <div class="summary-row">
                        <span>Подытог (<span id="subtotal-items">0</span>)</span>
                        <span><span id="subtotal-amount">0</span> ₽</span>
                    </div>
                    <div class="summary-row">
                        <span>Скидка (бонусы)</span>
                        <span><span id="discount-amount">0</span> ₽</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Итого</span>
                        <span><span id="total-amount">0</span> ₽</span>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-row bonuses-earn-row">
                        <span>Начислим бонусы</span>
                        <span id="bonuses-earn">+0</span>
                    </div>
                `;
                
                const radioButtons = container.querySelectorAll('input[name="pickup_address"]');
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        selectedPickupId = parseInt(e.target.value);
                        document.querySelectorAll('.pickup-address-item').forEach(item => {
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
                    <div class="pickup-section">
                        <div class="pickup-label">Адрес доставки</div>
                        ${renderDeliveryAddresses()}
                        <button class="add-address-btn" onclick="openAddressModal()">
                            <img src="../src/icons/plus.png" alt="Добавить адрес">
                        </button>
                    </div>
                    <div class="payment-type">
                        <span>Тип оплаты</span>
                        <span>Оплата по карте</span>
                    </div>
                    <div class="bonus-text">Бонусы</div>
                    <div class="divider"></div>
                    <div class="summary-row">
                        <span>Ваши бонусы</span>
                        <span id="user-bonuses">${userBonuses}</span>
                    </div>
                    <div class="bonus-input-wrapper">
                        <input type="number" class="bonus-input" id="bonus-input" placeholder="Введите количество бонусов (до ${userBonuses})" min="0" max="${userBonuses}">
                        <button class="apply-bonus-btn" id="apply-bonus-btn">Применить</button>
                    </div>
                    <div class="promo-text">Промокод</div>
                    <div class="promo-divider"></div>
                    <div class="promo-input-wrapper">
                        <input type="text" class="promo-input" id="promo-input" placeholder="Введите промокод">
                        <button class="apply-promo-btn" id="apply-promo-btn">Применить</button>
                    </div>
                    <div class="summary-text">Подробности о стоимости</div>
                    <div class="divider"></div>
                    <div class="summary-row">
                        <span>Подытог (<span id="subtotal-items">0</span>)</span>
                        <span><span id="subtotal-amount">0</span> ₽</span>
                    </div>
                    <div class="summary-row">
                        <span>Скидка (бонусы)</span>
                        <span><span id="discount-amount">0</span> ₽</span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Итого</span>
                        <span><span id="total-amount">0</span> ₽</span>
                    </div>
                    <div class="divider"></div>
                    <div class="summary-row bonuses-earn-row">
                        <span>Начислим бонусы</span>
                        <span id="bonuses-earn">+0</span>
                    </div>
                `;
                
                const radioButtons = container.querySelectorAll('input[name="delivery_address"]');
                radioButtons.forEach(radio => {
                    radio.addEventListener('change', (e) => {
                        selectedDeliveryId = parseInt(e.target.value);
                        document.querySelectorAll('.pickup-address-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        e.target.closest('.pickup-address-item').classList.add('active');
                    });
                });
            }
            
            const applyBonusBtn = document.getElementById('apply-bonus-btn');
            if (applyBonusBtn) {
                applyBonusBtn.addEventListener('click', () => {
                    const bonusInput = document.getElementById('bonus-input');
                    let bonusValue = parseInt(bonusInput.value);
                    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    
                    if (isNaN(bonusValue)) bonusValue = 0;
                    if (bonusValue > userBonuses) bonusValue = userBonuses;
                    if (bonusValue > subtotal) bonusValue = subtotal;
                    
                    usedBonuses = bonusValue;
                    updateSummary();
                    bonusInput.value = usedBonuses;
                    
                    const remainingBonuses = document.getElementById('user-bonuses');
                    if (remainingBonuses) remainingBonuses.textContent = userBonuses - usedBonuses;
                });
            }
            
            const applyPromoBtn = document.getElementById('apply-promo-btn');
            if (applyPromoBtn) {
                applyPromoBtn.addEventListener('click', () => {
                    alert('Промокод применен!');
                });
            }
            
            updateSummary();
        }
        
        function openAddressModal() {
            document.getElementById('addressModal').style.display = 'block';
        }
        
        function closeAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
        }
        
        document.querySelectorAll('.method-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.method-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                renderMethodContent(btn.dataset.method);
            });
        });
        
        window.updateCartItemQuantity = function(itemId, newQuantity) {
            if (typeof window.updateQuantity === 'function') {
                window.updateQuantity(itemId, newQuantity);
                cart = window.getCart();
                renderCartItems();
            }
        };
        
        renderMethodContent('pickup');
        renderCartItems();
        
        document.getElementById('pay-btn').addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Корзина пуста!');
                return;
            }
            
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const bonusesEarn = Math.floor(subtotal * 0.01);
            
            fetch(window.location.href, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'update_bonuses=1&used_bonuses=' + usedBonuses + '&earn_bonuses=' + bonusesEarn
            });
            
            const modal = document.getElementById('modal');
            modal.style.display = 'block';
            setTimeout(() => {
                modal.style.display = 'none';
                cart = [];
                usedBonuses = 0;
                updateCartAndSave();
                window.location.href = '../index.php';
            }, 2000);
        });
    </script>
</body>
</html>