<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Меню</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Orelega+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/dish_card.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../components/dish_card.js"></script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
</head>
<body>
    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $base = 'tomuLvovaAlesya';

    $conn = mysqli_connect($host, $user, $pass, $base);

    $query = 'SELECT * FROM `Dishes`';
    $result = mysqli_query($conn, $query);
    ?>

    <my-header></my-header>
    
    <div class="menu-container">
        <div class="left-container">
        <div class="search-wrapper">
            <input type="text" class="search-input" placeholder="Найти">
            <img src="../icons/search.png" alt="Поиск" class="search-icon">
        </div>
            <div class="filter-content">
                 <ul class="categories-grid">
                    <li class="category-item">
                        <img src="../DishesImg/Goryachee_1.png" alt="Горячие блюда" class="category-icon">
                        <span class="category-name">Горячие блюда</span>
                    </li>
                    <li class="category-item">
                        <img src="../DishesImg/Soup_1.png" alt="Супы" class="category-icon">
                        <span class="category-name">Супы</span>
                    </li>
                    <li class="category-item">
                        <img src="../DishesImg/Salad_1.png" alt="Салаты" class="category-icon">
                        <span class="category-name">Салаты</span>
                    </li>
                    <li class="category-item">
                        <img src="../DishesImg/Napitok_1.png" alt="Напитки" class="category-icon">
                        <span class="category-name">Напитки</span>
                    </li>
                    <li class="category-item">
                        <img src="../DishesImg/Dobavki_1.png" alt="Добавки" class="category-icon">
                        <span class="category-name">Добавки</span>
                    </li>
                    <li class="category-item">
                        <img src="../DishesImg/Desert_1.png" alt="Десерты" class="category-icon">
                        <span class="category-name">Десерты</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="right-container">
            <div class="dishes-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <dish-card
                        image="../<?php echo htmlspecialchars(trim($row['image'], '"')); ?>"
                        name="<?php echo htmlspecialchars($row['name']); ?>"
                        gram="<?php echo htmlspecialchars($row['gram']); ?>"
                        gr="гр."
                        price="<?php echo htmlspecialchars($row['price']); ?>"
                        sostav="<?php echo htmlspecialchars($row['ingredients']); ?>"
                    ></dish-card>
                <?php endwhile; ?>
                <?php
                mysqli_close($conn);
                ?>
            </div>
        </div>
    </div>
    <div class="spacer"></div>
    <my-footer></my-footer>
</body>
</html>