<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Корзина</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Orelega+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/cart.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../components/cart-item.js"></script>
</head>
<body>
    <my-header></my-header>
    
    <div class="cart-page">
        <div class="cart-left">
            <div class="cart-header">
                <div>
                    <h2>Ваша корзина</h2>
                    <span class="cart-count" id="cart-count">0 позиций</span>
                </div>
                <button class="clear-cart" id="clear-cart-btn">
                    <img src="../icons/trash_cart.png" alt="Очистить корзину">
                    <p class="clear-text">Очистить корзину</p>
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
            <div class="method-content" id="method-content">

            </div>
        </div>
    </div>

    <div class="spacer"></div>
    <my-footer></my-footer>

    <div id="modal" class="modal">
        <div class="modal-content">
            Заказ принят!
        </div>
    </div>

    <script src="../scripts/cart.js"></script>
</body>
</html>