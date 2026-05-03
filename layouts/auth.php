<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = mysqli_connect('localhost', 'root', '', 'tomuLvovaAlesya');
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Ошибка безопасности.');
    }

    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (!$login || !$password) {
        $error = 'Заполните все поля';
    } else {
        $res = mysqli_query($conn, "SELECT * FROM Users WHERE name = '$login' OR email = '$login'");
        $user = mysqli_fetch_assoc($res);
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_bonuses'] = $user['bonuses'];
            echo '<script>location.href="profile.php"</script>';
            exit();
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tomy - Аутентификация</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="icon" href="../src/icons/Tomu_logo.png">
    <link rel="stylesheet" href="../style/css/auth.css">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <script src="../scripts/func.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    <script>window.isLoggedIn=false;window.userName='';</script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
</head>
<body>
<my-header></my-header>
<div class="auth-container">
    <p class="auth-text">АВТОРИЗАЦИЯ</p>
    <form id="loginForm" method="POST" onsubmit="return sanitizeForm('loginForm')">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <p class="input-text">ЛОГИН</p>
        <div class="input-container"><input type="text" name="login" class="input" placeholder="Введите логин или email" required></div>
        <p class="input-text">ПАРОЛЬ</p>
        <div class="input-container"><input type="password" name="password" class="input" placeholder="Введите пароль" required></div>
        <div class="buttons">
            <p class="button-text">ЗАБЫЛИ ПАРОЛЬ?</p>
            <p class="button-text" onclick="location.href='signup.php'">У меня нет аккаунта</p>
        </div>
        <?php if ($error) echo '<div style="color:red;text-align:center;margin-top:15px;">'.$error.'</div>'; ?>
</div>
<button type="submit" class="sigin-button">ВОЙТИ</button>
</form>
<div class="spacer"></div>
<my-footer></my-footer>
</body>
</html>