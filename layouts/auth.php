<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Аутентификация</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Orelega+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/auth.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../scripts/cart-functions.js" defer></script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
</head>
<body>
    <my-header></my-header>
    <div class="auth-container">
        <p class="auth-text">АВТОРИЗАЦИЯ</p>
        <p class="input-text">ЛОГИН</p>
        <div class="input-container">
            <input type="text" class="input" placeholder="Введите логин">
        </div>
        <p class="input-text">ПАРОЛЬ</p>
        <div class="input-container">
            <input type="password" class="input" placeholder="Введите пароль">
        </div>
        <div class="buttons">
            <p class="button-text">ЗАБЫЛИ ПАРОЛЬ?</p>
            <p class="button-text" onclick="window.location.href='signup.php'">У меня нет аккаунта</p>
        </div>
    </div>
    <button class="sigin-button">ВОЙТИ</button>
    <div class="spacer"></div>


    <my-footer></my-footer>
</body>
</html>