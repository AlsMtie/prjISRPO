
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Профиль</title>

    <link rel="stylesheet" href="../style/fonts/fonts.css">

    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/profile.css">
    
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../scripts/cart-functions.js"></script>
</head>
<body>
    <my-header></my-header>
    <div class="prof-container">
        <div class="left-container">
            <h2>Профиль</h2>
            <div class="user-info">
                <div class="user-pole">
                    <span class="info-text">Alesya</span>
                    <img src="../icons/pencil.png">
                </div>
                <div class="user-pole">
                    <span class="info-text">alesyalvova07@gmail.com</span>
                    <img src="../icons/pencil.png">
                </div>
                <div class="user-pole">
                    <span class="info-text">89243618336</span>
                </div>
                <div class="bonuses-container">
                    <h2 class ="VAMDOSTUPNO">Вам доступно</h2>
                    <span class="bonuses-kolvo-text">155</span>
                    <p class="bonuses-text">бонусов</p>
                     
                </div>
            </div>
            
        </div>
        <div class="right-container">
            <h2>Адреса для доставки</h2>
            <div class="adresses-container">
                <div class="address-pole">
                    <span class="info-text">ул. Петра Алексеева 27, 1 подъезд, 2 этаж, 43 кв.</span>
                    <img src="../icons/krest.png">
                </div>
                <div class="address-pole">
                    <span class="info-text">ул. Петра Алексеева 27, 1 подъезд, 2 этаж, 43 кв.</span>
                    <img src="../icons/krest.png">
                </div>
                <button class="add-address-btn">
                    <img src="../icons/plus.png" alt="Добавить адрес">
                </button>
            </div>
        </div>

    </div>



    <div class="spacer"></div>
    
    <my-footer></my-footer>
</body>
</html>