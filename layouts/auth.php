<?php
session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$base = 'tomuLvovaAlesya';

$conn = mysqli_connect($host, $user, $pass, $base);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    
    if (empty($login) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $query = "SELECT * FROM Users WHERE name = '$login' OR email = '$login'";
        $result = mysqli_query($conn, $query);
        $user_data = mysqli_fetch_assoc($result);
        
        if ($user_data && password_verify($password, $user_data['password'])) {
            $_SESSION['user_id'] = $user_data['id'];
            $_SESSION['user_name'] = $user_data['name'];
            $_SESSION['user_bonuses'] = $user_data['bonuses'];
            
            echo '<script>window.location.href = "profile.php";</script>';
            exit();
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Аутентификация</title>

    <link rel="stylesheet" href="../style/fonts/fonts.css">

    <link rel="stylesheet" href="../style/css/auth.css">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <script src="../scripts/func.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
</head>
<body>
    <my-header></my-header>
    <div class="auth-container">
        <p class="auth-text">АВТОРИЗАЦИЯ</p>

        <?php if ($error): ?>
            <script>console.log("<?php echo $error; ?>");</script>
        <?php endif; ?>

         <form id="loginForm" method="POST" action="" onsubmit="return sanitizeForm('loginForm')">
            <p class="input-text">ЛОГИН</p>
            <div class="input-container">
                <input type="text" name="login" class="input" placeholder="Введите логин или email" required>
            </div>
            <p class="input-text">ПАРОЛЬ</p>
            <div class="input-container">
                <input type="password" name="password" class="input" placeholder="Введите пароль" required>
            </div>
            <div class="buttons">
                <p class="button-text">ЗАБЫЛИ ПАРОЛЬ?</p>
                <p class="button-text" onclick="window.location.href='signup.php'">У меня нет аккаунта</p>
            </div>
    </div>
    <button type="submit" class="sigin-button">ВОЙТИ</button>
    </form>
    <div class="spacer"></div>
    <my-footer></my-footer>
</body>
</html>
</html>