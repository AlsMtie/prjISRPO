<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy</title>

    <link rel="stylesheet" href="style/fonts/fonts.css">

    <link rel="stylesheet" href="style/css/index.css">
    <link rel="stylesheet" href="style/css/header.css">
    <link rel="stylesheet" href="style/css/footer.css">
    <script src="scripts/cart-functions.js"></script>
    <script src="components/header.js"></script>
    <script src="components/footer.js"></script>
</head>

<body>
    <my-header></my-header>
    <div class="scroll-panel">
        <div class="scroll-container">
            <div class="scroll-item">
                <img src="DishesImg/aksia1.png" alt="Акция картинка">
            </div>
            <div class="scroll-item">
                <img src="DishesImg/aksia2.png" alt="Акция картинка">
            </div>
            <div class="scroll-item">
                <img src="DishesImg/aksia3.png" alt="Акция картинка">
            </div>
            <div class="scroll-item">
                <img src="DishesImg/aksia4.png" alt="Акция картинка">
            </div>
        </div>
    </div>

    <p class="menu-text">Меню</p>

    <div class="category-container">
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=1'">
            <img class="category-img" src="DishesImg/Goryachee_1.png" alt="Категория блюда">
            <span class = "category-text">Горячие блюда</span>
        </button>
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=2'">
            <img class="category-img" src="DishesImg/Soup_1.png" alt="Категория блюда">
            <span class = "category-text">Супы</span>
        </button>
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=3'">
            <img class="category-img" src="DishesImg/Salad_1.png" alt="Категория блюда">
            <span class = "category-text">Салаты</span>
        </button>
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=4'">
            <img class="category-img" src="DishesImg/Napitok_1.png" alt="Категория блюда">
            <span class = "category-text">Напитки</span>
        </button>
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=5'">
            <img class="category-img" src="DishesImg/Dobavki_1.png" alt="Категория блюда">
            <span class = "category-text">Добавки</span>
        </button>
        <button class="category-button" onclick="window.location.href='layouts/menu.php?category=6'">
            <img class="category-img" src="DishesImg/Desert_1.png" alt="Категория блюда">
            <span class = "category-text">Десерты</span>
        </button>
</div>

    <div class="spacer"></div>
    <my-footer></my-footer>
</body>
</html>
