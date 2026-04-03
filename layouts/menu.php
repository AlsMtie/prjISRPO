<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tomy - Меню</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&family=Orelega+One&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/menu.css">
    <link rel="stylesheet" href="../css/dish_card.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">

    <script src="../components/header.js" defer></script>
    <script src="../components/footer.js" defer></script>
    <script src="../scripts/cart-functions.js" defer></script>
    <script src="../components/dish_card.js" defer></script>
</head>
<body>
    <?php
    $host = 'localhost';
    $user = 'root';
    $pass = '';
    $base = 'tomuLvovaAlesya';

    $conn = mysqli_connect($host, $user, $pass, $base);
    
    $category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
    
    if ($category > 0) {
        $query = "SELECT * FROM `Dishes` WHERE category_id = $category";
    } else {
        $query = "SELECT * FROM `Dishes`";
    }
    
    $result = mysqli_query($conn, $query);
    ?>

    <my-header></my-header>
    
    <div class="menu-container">
        <div class="left-container">
            <div class="search-wrapper">
                <input type="text" class="search-input" id="search-input" placeholder="Найти блюдо...">
                <img src="../icons/search.png" alt="Поиск" class="search-icon">
            </div>
            <div class="filter-content">
                <ul class="categories-grid">
                    <li class="category-item <?php echo $category == 0 ? 'active' : ''; ?>" data-category="0">
                        <img src="../DishesImg/Goryachee_2.png" alt="Все блюда" class="category-icon">
                        <span class="category-name">Все блюда</span>
                    </li>
                    <li class="category-item <?php echo $category == 1 ? 'active' : ''; ?>" data-category="1">
                        <img src="../DishesImg/Goryachee_1.png" alt="Горячие блюда" class="category-icon">
                        <span class="category-name">Горячие блюда</span>
                    </li>
                    <li class="category-item <?php echo $category == 2 ? 'active' : ''; ?>" data-category="2">
                        <img src="../DishesImg/Soup_1.png" alt="Супы" class="category-icon">
                        <span class="category-name">Супы</span>
                    </li>
                    <li class="category-item <?php echo $category == 3 ? 'active' : ''; ?>" data-category="3">
                        <img src="../DishesImg/Salad_1.png" alt="Салаты" class="category-icon">
                        <span class="category-name">Салаты</span>
                    </li>
                    <li class="category-item <?php echo $category == 4 ? 'active' : ''; ?>" data-category="4">
                        <img src="../DishesImg/Napitok_1.png" alt="Напитки" class="category-icon">
                        <span class="category-name">Напитки</span>
                    </li>
                    <li class="category-item <?php echo $category == 5 ? 'active' : ''; ?>" data-category="5">
                        <img src="../DishesImg/Dobavki_1.png" alt="Добавки" class="category-icon">
                        <span class="category-name">Добавки</span>
                    </li>
                    <li class="category-item <?php echo $category == 6 ? 'active' : ''; ?>" data-category="6">
                        <img src="../DishesImg/Desert_1.png" alt="Десерты" class="category-icon">
                        <span class="category-name">Десерты</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="right-container">
            <div class="dishes-grid" id="dishes-grid">
                <?php while ($row = mysqli_fetch_assoc($result)): 
                    $imagePath = trim($row['image'], '"\'');
                ?>
                    <dish-card
                        id="dish-<?php echo $row['id']; ?>"
                        data-id="<?php echo $row['id']; ?>"
                        image="../<?php echo htmlspecialchars($imagePath); ?>"
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

    <script>
        const categoryItems = document.querySelectorAll('.category-item');
        categoryItems.forEach(item => {
            item.addEventListener('click', function() {
                const categoryId = this.getAttribute('data-category');
                window.location.href = `menu.php?category=${categoryId}`;
            });
        });
    </script>
</body>
</html>