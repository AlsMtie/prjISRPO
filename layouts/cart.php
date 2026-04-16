<?php
session_start();
$conn = mysqli_connect('localhost','root','','tomuLvovaAlesya');

if(!isset($_SESSION['user_id'])){
    echo '<div style="text-align:center;padding:50px"><h2>Доступ ограничен</h2><p>Войдите в аккаунт</p><button onclick="location.href=\'auth.php\'" style="background:#B13720;color:#fff;border:none;padding:10px 30px;border-radius:30px;cursor:pointer">Войти</button></div>';
    exit();
}

$user_id = $_SESSION['user_id'];

// Обработка сохранения заказа (без изменений)
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['save_order'])){
    $data = json_decode($_POST['order_data'], true);
    $num = date('Ymd').rand(10000,99999);
    $type = $data['receiving_type'];
    $addr = isset($data['delivery_address']) ? $data['delivery_address'] : null;
    $cafe = isset($data['cafe_id']) ? $data['cafe_id'] : null;
    $spent = isset($data['bonus_spent']) ? $data['bonus_spent'] : 0;
    $total = $data['total_amount'];
    $comment = isset($data['comment']) ? $data['comment'] : '';
    $date = date('Y-m-d H:i:s');
    $sub = $total + $spent;
    $earn = floor($sub * 0.01);
    
    $q = "INSERT INTO Orders SET order_number='$num', user_id=$user_id, receiving_type='$type', delivery_address=".($addr ? "'$addr'" : "NULL").", cafe_id=".($cafe ? $cafe : "NULL").", status_id=5, bonus_spent=$spent, bonus_earned=$earn, total_amount=$total, comment='$comment', created_date='$date'";
    
    if(mysqli_query($conn, $q)){
        $oid = mysqli_insert_id($conn);
        foreach($data['items'] as $it){
            mysqli_query($conn, "INSERT INTO Order_item (order_id, dish_id, quantity, dish_name, price) VALUES ($oid, {$it['id']}, {$it['quantity']}, '{$it['name']}', {$it['price']})");
        }
        echo json_encode(['success'=>true, 'order_number'=>$num]);
    } else {
        echo json_encode(['success'=>false, 'error'=>mysqli_error($conn)]);
    }
    exit();
}

$bonus = mysqli_fetch_assoc(mysqli_query($conn, "SELECT bonuses FROM Users WHERE id=$user_id"))['bonuses'];
$addresses = [];
$res = mysqli_query($conn, "SELECT id, full_addresses FROM Users_addresses WHERE user_id=$user_id");
while($row = mysqli_fetch_assoc($res)) $addresses[] = $row;

$cafes = [];
$res = mysqli_query($conn, "SELECT id, address FROM cafes WHERE is_active=1");
while($row = mysqli_fetch_assoc($res)) $cafes[] = $row;

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_address'])){
    $full = $_POST['full_address'];
    $apt = $_POST['apartment'];
    $ent = $_POST['entrance'];
    $fl = !empty($_POST['floor']) ? $_POST['floor'] : 'NULL';
    $door = $_POST['doorphone'];
    $comm = $_POST['comment'];
    mysqli_query($conn, "INSERT INTO Users_addresses (user_id, full_addresses, apartment, entrance, floor, doorphone, comment) VALUES ($user_id, '$full', '$apt', '$ent', $fl, '$door', '$comm')");
    header('Location: cart.php');
    exit();
}

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_bonuses'])){
    $used = (int)$_POST['used_bonuses'];
    $earn = (int)$_POST['earn_bonuses'];
    mysqli_query($conn, "UPDATE Users SET bonuses = bonuses - $used + $earn WHERE id=$user_id");
    echo json_encode(['success'=>true]);
    exit();
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tomy - Корзина</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="icon" href="../src/icons/Tomu_logo.png">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/cart.css">
    <link rel="stylesheet" href="../style/css/cart-item.css">
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../components/cart-item.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    <script src="../scripts/func.js"></script> 
    <script>window.isLoggedIn=true;window.userBonuses=<?=$bonus?>;</script>
</head>
<body>
<my-header></my-header>
<div class="cart-page">
    <div class="cart-left">
        <div class="cart-header">
            <h2>Ваша корзина <span id="cart-count">0</span></h2>
            <button id="clear-cart-btn" class="clear-cart">
                <img src="../src/icons/trash_cart.png">
                <span class="clear-text">Очистить корзину</span>
            </button>
        </div>
        <div id="cart-items-list" class="cart-items-list">
            <p style="text-align:center;font-family:'El Messiri';color:#9F9F9F;">Корзина пуста</p>
        </div>
    </div>
    <div class="cart-right">
        <div class="delivery-methods">
            <button class="method-btn active" data-method="pickup">Самовывоз</button>
            <button class="method-btn" data-method="delivery">Доставка</button>
        </div>
        <div id="method-content" class="method-content"></div>
        <div class="checkout-footer">
            <div class="checkout-info">
                <span id="checkout-items" class="checkout-items">0 товаров</span>
                <span id="checkout-total" class="checkout-total">0 ₽</span>
            </div>
            <button id="pay-btn" class="pay-btn">Оплатить</button>
        </div>
    </div>
</div>
<my-footer></my-footer>
<div id="modal" class="modal"><div class="modal-content"><p>Заказ принят!</p></div></div>
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
// ------------------------------------------------------------------
// 1. Данные
// ------------------------------------------------------------------
var cafes = <?=json_encode($cafes)?>;
var addresses = <?=json_encode($addresses)?>;
var cart = JSON.parse(localStorage.getItem('tomy_cart') || '[]');
var selectedPickupId = (cafes.length > 0) ? cafes[0].id : null;
var selectedDeliveryId = (addresses.length > 0) ? addresses[0].id : null;
var usedBonuses = 0;
var userBonuses = window.userBonuses;

// Флаг для предотвращения повторной отправки заказа
var isSubmitting = false;

// ------------------------------------------------------------------
// 2. Отрисовка товаров и сводки
// ------------------------------------------------------------------
function renderCartItems() {
    var container = document.getElementById('cart-items-list');
    var cartCountSpan = document.getElementById('cart-count');
    
    if (cart.length === 0) {
        container.innerHTML = '<p style="text-align:center;font-family:\'El Messiri\';color:#9F9F9F;">Корзина пуста</p>';
        cartCountSpan.textContent = '0';
        updateSummary();
        return;
    }
    
    container.innerHTML = '';
    for (var i = 0; i < cart.length; i++) {
        var item = cart[i];
        var cartItem = document.createElement('cart-item');
        cartItem.setAttribute('data-id', item.id);
        cartItem.setAttribute('image', item.image);
        cartItem.setAttribute('name', item.name);
        cartItem.setAttribute('price', item.price);
        cartItem.setAttribute('quantity', item.quantity);
        container.appendChild(cartItem);
    }
    
    var totalQuantity = 0;
    for (var i = 0; i < cart.length; i++) totalQuantity += cart[i].quantity;
    cartCountSpan.textContent = totalQuantity;
    updateSummary();
}

function updateSummary() {
    var subtotal = 0;
    for (var i = 0; i < cart.length; i++) subtotal += cart[i].price * cart[i].quantity;
    var itemsCount = 0;
    for (var i = 0; i < cart.length; i++) itemsCount += cart[i].quantity;
    var total = subtotal - usedBonuses;
    if (total < 0) total = 0;
    var bonusesEarn = Math.floor(subtotal * 0.01);
    
    var el = function(id) { return document.getElementById(id); };
    if (el('subtotal-items')) el('subtotal-items').textContent = itemsCount;
    if (el('subtotal-amount')) el('subtotal-amount').textContent = subtotal;
    if (el('discount-amount')) el('discount-amount').textContent = usedBonuses;
    if (el('total-amount')) el('total-amount').textContent = total;
    if (el('checkout-items')) el('checkout-items').textContent = itemsCount + ' товаров';
    if (el('checkout-total')) el('checkout-total').textContent = total + ' ₽';
    if (el('bonuses-earn')) el('bonuses-earn').textContent = '+' + bonusesEarn;
}

function saveCart() {
    localStorage.setItem('tomy_cart', JSON.stringify(cart));
    renderCartItems();
}

document.getElementById('clear-cart-btn').onclick = function() {
    cart = [];
    usedBonuses = 0;
    saveCart();
};

// ------------------------------------------------------------------
// 3. Рендер адресов
// ------------------------------------------------------------------
function renderPickupAddresses() {
    if (cafes.length === 0) return '<div>Нет адресов</div>';
    var html = '<div class="pickup-addresses">';
    for (var i = 0; i < cafes.length; i++) {
        var c = cafes[i];
        var activeClass = (selectedPickupId == c.id) ? 'active' : '';
        var checkedAttr = (selectedPickupId == c.id) ? 'checked' : '';
        html += '<label class="pickup-address-item ' + activeClass + '">' +
                '<input type="radio" name="pickup_address" value="' + c.id + '" ' + checkedAttr + '>' +
                '<span>' + c.address + '</span>' +
                '</label>';
    }
    html += '</div>';
    return html;
}

function renderDeliveryAddresses() {
    if (addresses.length === 0) return '<div>Нет адресов</div>';
    var html = '<div class="pickup-addresses">';
    for (var i = 0; i < addresses.length; i++) {
        var a = addresses[i];
        var activeClass = (selectedDeliveryId == a.id) ? 'active' : '';
        var checkedAttr = (selectedDeliveryId == a.id) ? 'checked' : '';
        html += '<label class="pickup-address-item ' + activeClass + '">' +
                '<input type="radio" name="delivery_address" value="' + a.id + '" ' + checkedAttr + '>' +
                '<span>' + a.full_addresses + '</span>' +
                '</label>';
    }
    html += '</div>';
    return html;
}

// ------------------------------------------------------------------
// 4. Построение правого блока (единый шаблон)
// ------------------------------------------------------------------
function buildRightBlock(method) {
    var isPickup = (method === 'pickup');
    var title = isPickup ? 'Место выдачи' : 'Адрес доставки';
    var addressesHtml = isPickup ? renderPickupAddresses() : renderDeliveryAddresses();
    var extraButton = '';
    if (!isPickup) {
        extraButton = '<button class="add-address-btn" onclick="openAddressModal()"><img src="../src/icons/plus.png"></button>';
    }
    var timeLabel = isPickup ? 'Время' : 'Время доставки';
    
    return `
        <div class="delivery-time">
            <span>${timeLabel}</span>
            <span>Как можно скорее</span>
        </div>
        <div class="pickup-section">
            <div class="pickup-label">${title}</div>
            ${addressesHtml}
            ${extraButton}
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
            <input type="number" id="bonus-input" class="bonus-input" placeholder="Бонусов (до ${userBonuses})" min="0" max="${userBonuses}">
            <button id="apply-bonus-btn" class="apply-bonus-btn">Применить</button>
        </div>
        <div class="promo-text">Промокод</div>
        <div class="promo-divider"></div>
        <div class="promo-input-wrapper">
            <input type="text" id="promo-input" class="promo-input" placeholder="Промокод">
            <button id="apply-promo-btn" class="apply-promo-btn">Применить</button>
        </div>
        <div class="summary-text">Подробности</div>
        <div class="divider"></div>
        <div class="summary-row">
            <span>Подытог (<span id="subtotal-items">0</span>)</span>
            <span id="subtotal-amount">0</span>
        </div>
        <div class="summary-row">
            <span>Скидка</span>
            <span id="discount-amount">0</span>
        </div>
        <div class="summary-row summary-total">
            <span>Итого</span>
            <span id="total-amount">0</span>
        </div>
        <div class="divider"></div>
        <div class="summary-row bonuses-earn-row">
            <span>Начислим бонусы</span>
            <span id="bonuses-earn">+0</span>
        </div>
    `;
}

// ------------------------------------------------------------------
// 5. Привязка обработчиков после отрисовки блока
// ------------------------------------------------------------------
function attachHandlers(method) {
    // Радио-кнопки
    var radioName = (method === 'pickup') ? 'pickup_address' : 'delivery_address';
    var radios = document.querySelectorAll('input[name="' + radioName + '"]');
    for (var i = 0; i < radios.length; i++) {
        radios[i].onchange = function(e) {
            var target = e.target;
            var value = parseInt(target.value);
            if (method === 'pickup') {
                selectedPickupId = value;
                var items = document.querySelectorAll('.pickup-address-item');
                for (var j = 0; j < items.length; j++) items[j].classList.remove('active');
                target.closest('.pickup-address-item').classList.add('active');
            } else {
                selectedDeliveryId = value;
                var items = document.querySelectorAll('.pickup-address-item');
                for (var j = 0; j < items.length; j++) items[j].classList.remove('active');
                target.closest('.pickup-address-item').classList.add('active');
            }
        };
    }
    
    // Кнопка применения бонусов
    var bonusBtn = document.getElementById('apply-bonus-btn');
    if (bonusBtn) {
        // Убираем старый обработчик, если был
        bonusBtn.onclick = null;
        bonusBtn.onclick = function() {
            var bonusInput = document.getElementById('bonus-input');
            var rawValue = bonusInput.value;
            var val = parseInt(rawValue);
            if (isNaN(val)) val = 0;
            // Считаем текущую сумму корзины
            var subtotal = 0;
            for (var i = 0; i < cart.length; i++) subtotal += cart[i].price * cart[i].quantity;
            if (val > userBonuses) val = userBonuses;
            if (val > subtotal) val = subtotal;
            usedBonuses = val;
            updateSummary();
            bonusInput.value = usedBonuses;
            var remSpan = document.getElementById('user-bonuses');
            if (remSpan) remSpan.textContent = userBonuses - usedBonuses;
        };
    }
    
    // Кнопка промокода
    var promoBtn = document.getElementById('apply-promo-btn');
    if (promoBtn) {
        promoBtn.onclick = null;
        promoBtn.onclick = function() {
            var promoInput = document.getElementById('promo-input');
            var promoValue = promoInput.value;
            alert('Промокод "' + promoValue + '" применен');
        };
    }
}

// ------------------------------------------------------------------
// 6. Переключение методов (самовывоз / доставка)
// ------------------------------------------------------------------
function showMethod(method) {
    var container = document.getElementById('method-content');
    container.innerHTML = buildRightBlock(method);
    attachHandlers(method); // обязательно после вставки HTML
    updateSummary();
}

var methodBtns = document.querySelectorAll('.method-btn');
for (var i = 0; i < methodBtns.length; i++) {
    methodBtns[i].onclick = function() {
        var allBtns = document.querySelectorAll('.method-btn');
        for (var j = 0; j < allBtns.length; j++) allBtns[j].classList.remove('active');
        this.classList.add('active');
        var method = this.getAttribute('data-method');
        showMethod(method);
    };
}

// ------------------------------------------------------------------
// 7. Модальные окна
// ------------------------------------------------------------------
function openAddressModal() {
    document.getElementById('addressModal').style.display = 'block';
}
function closeAddressModal() {
    document.getElementById('addressModal').style.display = 'none';
}

// ------------------------------------------------------------------
// 8. Инициализация
// ------------------------------------------------------------------
showMethod('pickup');
renderCartItems();

window.updateCartItemQuantity = function(id, newQ) {
    if (window.updateQuantity) window.updateQuantity(id, newQ);
    cart = window.getCart();
    renderCartItems();
    // После изменения корзины обновляем доступные бонусы (если нужно)
    var bonusInput = document.getElementById('bonus-input');
    if (bonusInput) {
        var subtotal = 0;
        for (var i = 0; i < cart.length; i++) subtotal += cart[i].price * cart[i].quantity;
        var maxBonus = Math.min(userBonuses, subtotal);
        bonusInput.max = maxBonus;
        bonusInput.placeholder = 'Бонусов (до ' + maxBonus + ')';
    }
};

// ------------------------------------------------------------------
// 9. Обработчик оплаты с защитой от повторных нажатий
// ------------------------------------------------------------------
document.getElementById('pay-btn').onclick = function() {
    if (isSubmitting) {
        alert('Заказ уже оформляется, подождите...');
        return;
    }
    if (cart.length === 0) {
        alert('Корзина пуста');
        return;
    }
    
    isSubmitting = true;
    var payBtn = document.getElementById('pay-btn');
    payBtn.disabled = true;
    payBtn.style.opacity = '0.6';
    
    var method = document.querySelector('.method-btn.active').getAttribute('data-method');
    var subtotal = 0;
    for (var i = 0; i < cart.length; i++) subtotal += cart[i].price * cart[i].quantity;
    var earn = Math.floor(subtotal * 0.01);
    var addr = null;
    var cafe = null;
    
    if (method === 'pickup') {
        cafe = selectedPickupId;
    } else {
        for (var i = 0; i < addresses.length; i++) {
            if (addresses[i].id == selectedDeliveryId) {
                addr = addresses[i].full_addresses;
                break;
            }
        }
    }
    
    var data = {
        receiving_type: method,
        delivery_address: addr,
        cafe_id: cafe,
        bonus_spent: usedBonuses,
        total_amount: subtotal - usedBonuses,
        comment: '',
        items: []
    };
    for (var i = 0; i < cart.length; i++) {
        data.items.push({
            id: cart[i].id,
            name: cart[i].name,
            price: cart[i].price,
            quantity: cart[i].quantity
        });
    }
    
    // Обновление бонусов
    fetch(location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'update_bonuses=1&used_bonuses=' + usedBonuses + '&earn_bonuses=' + earn
    });
    
    // Сохранение заказа
    fetch(location.href, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'save_order=1&order_data=' + JSON.stringify(data)
    })
    .then(function(response) { return response.json(); })
    .then(function(result) {
        if (result.success) {
            var modal = document.getElementById('modal');
            modal.innerHTML = '<div class="modal-content"><p>Заказ принят!</p><p style="font-size:16px">Номер: ' + result.order_number + '</p></div>';
            modal.style.display = 'block';
            setTimeout(function() {
                modal.style.display = 'none';
                cart = [];
                usedBonuses = 0;
                saveCart();
                window.location.href = '../index.php';
            }, 3000);
        } else {
            alert('Ошибка: ' + result.error);
            isSubmitting = false;
            payBtn.disabled = false;
            payBtn.style.opacity = '1';
        }
    })
    .catch(function(error) {
        alert('Ошибка соединения: ' + error);
        isSubmitting = false;
        payBtn.disabled = false;
        payBtn.style.opacity = '1';
    });
};
</script>
</body>
</html>