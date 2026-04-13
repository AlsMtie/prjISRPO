<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$base = 'tomuLvovaAlesya';

$conn = mysqli_connect($host, $user, $pass, $base);

$error = '';

$name = trim($_POST['name']);
$password = $_POST['password'];
$phone = trim($_POST['phone']);
$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
    
if (empty($name) || empty($password) || empty($phone)) {
    $error = 'Заполните все обязательные поля';
} elseif (strlen($password) < 6) {
    $error = 'Пароль должен быть не менее 6 символов';
} else {
    $check_query = "SELECT id FROM users WHERE name = '$name' OR phone = '$phone'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = 'Пользователь с таким логином или телефоном уже существует';
    } else {
        $hash_pass = password_hash($password, PASSWORD_DEFAULT);
        $regestration_date = date('Y-m-d');
            
        $insert_query = "INSERT INTO Users(name, password, email, phone, bonuses, regestration_date) 
        VALUES ('$name', '$hash_pass', '$email', '$phone', 0, '$regestration_date')";
            
        if (mysqli_query($conn, $insert_query)) {
            echo '<script>window.location.href = "auth.php";</script>';
            exit();
        } else {
            $error = 'Ошибка регистрации';
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
    <title>Tomy - Регистрация</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">

    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/signup.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <script src="../scripts/func.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    <script>
        window.isLoggedIn = false;
        window.userName = '';
    </script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
</head>
<body>
    <my-header></my-header>
    <div class="auth-container">
        <p class="auth-text">РЕГИСТРАЦИЯ</p>

        <?php if ($error): ?>
            <script>console.log("<?php echo $error; ?>");</script>
        <?php endif; ?>

        <?php if ($success): ?>
            <script>console.log("<?php echo $success; ?>");</script>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="" onsubmit="return sanitizeForm('registerForm')">
            <p class="input-text">ЛОГИН</p>
            <div class="input-container">
                <input type="text" name="name" class="input" placeholder="Введите логин">
            </div>
            <p class="input-text">ПАРОЛЬ</p>
            <div class="input-container">
                <input type="password" name="password" class="input" placeholder="Введите пароль">
            </div>
            <p class="input-text">ПОВТОРИТЕ ПАРОЛЬ</p>
            <div class="input-container">
                <input type="password" name="confirm_password" class="input" placeholder="Повторите пароль">
            </div>
            <p class="input-text">НОМЕР ТЕЛЕФОНА</p>
            <div class="input-container">
                <input type="tel" name="phone" class="input" placeholder="Введите номер телефона">
            </div>
            <p class="input-text">АДРЕС ЭЛ. ПОЧТЫ</p>
            <div class="input-container">
                <input type="email" name="email" class="input" placeholder="Введите адрес эл. почты">
            </div>
            <div class="buttons-single">
                <p class="button-text" onclick="window.location.href='auth.php'">У меня уже есть аккаунт</p>
            </div>
    </div>
    <button class="sigin-button">ЗАРЕГИСТРИРОВАТЬСЯ</button>
    </form>
    <div class="spacer"></div>
    <my-footer></my-footer>
</body>
</html>