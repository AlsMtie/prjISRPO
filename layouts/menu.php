<?php
session_start();

$isLoggedIn = false;
if(isset($_SESSION['user_id'])) {
    $isLoggedIn = true;
} else {
    $userName = '';
}

$conn = mysqli_connect('localhost', 'root', '', 'tomuLvovaAlesya');

$cat = 0;
if(isset($_GET['category'])) {
    $cat = (int)$_GET['category'];
}

if($cat > 0) {
    $sql = "SELECT * FROM Dishes WHERE category_id = $cat";
} else {
    $sql = "SELECT * FROM Dishes";
}

$res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tomy - Меню</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="icon" href="../src/icons/Tomu_logo.png">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/menu.css">
    <link rel="stylesheet" href="../style/css/dish_card.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <script src="../scripts/func.js"></script>
    <script>
        if(<?php echo $isLoggedIn ? 'true' : 'false'; ?>) {
            window.isLoggedIn = true;
        } else {
            window.isLoggedIn = false;
        }
    </script>
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    <script src="../components/dish_card.js"></script>
</head>
<body>

<my-header></my-header>

<div class="menu-container">
    <div class="left-container">
        <div class="search-wrapper">
            <input type="text" id="searchInput" class="search-input" placeholder="Найти блюдо...">
            <img src="../src/icons/search.png" class="search-icon">
        </div>
        <div class="filter-content">
            <ul class="categories-grid">
                <li class="category-item <?php if($cat == 0) { echo 'active'; } ?>" data-cat="0">
                    <img src="../src/DishesImg/Goryachee_2.png" class="category-icon">
                    <span class="category-name">Все блюда</span>
                </li>
                <li class="category-item <?php if($cat == 1) { echo 'active'; } ?>" data-cat="1">
                    <img src="../src/DishesImg/Goryachee_1.png" class="category-icon">
                    <span class="category-name">Горячие блюда</span>
                </li>
                <li class="category-item <?php if($cat == 2) { echo 'active'; } ?>" data-cat="2">
                    <img src="../src/DishesImg/Soup_1.png" class="category-icon">
                    <span class="category-name">Супы</span>
                </li>
                <li class="category-item <?php if($cat == 3) { echo 'active'; } ?>" data-cat="3">
                    <img src="../src/DishesImg/Salad_1.png" class="category-icon">
                    <span class="category-name">Салаты</span>
                </li>
                <li class="category-item <?php if($cat == 4) { echo 'active'; } ?>" data-cat="4">
                    <img src="../src/DishesImg/Napitok_1.png" class="category-icon">
                    <span class="category-name">Напитки</span>
                </li>
                <li class="category-item <?php if($cat == 5) { echo 'active'; } ?>" data-cat="5">
                    <img src="../src/DishesImg/Dobavki_1.png" class="category-icon">
                    <span class="category-name">Добавки</span>
                </li>
                <li class="category-item <?php if($cat == 6) { echo 'active'; } ?>" data-cat="6">
                    <img src="../src/DishesImg/Desert_1.png" class="category-icon">
                    <span class="category-name">Десерты</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="right-container">
        <div class="dishes-wrapper">
            <div class="dishes-grid" id="dishes-grid">
                <?php while($row = mysqli_fetch_assoc($res)): 
                    $img = trim($row['image'], '"\'');
                ?>
                    <dish-card 
                        data-id="<?php echo $row['id']; ?>" 
                        image="../src/<?php echo $img; ?>" 
                        name="<?php echo $row['name']; ?>" 
                        gram="<?php echo $row['gram']; ?>" 
                        gr="гр." 
                        price="<?php echo $row['price']; ?>" 
                        sostav="<?php echo $row['ingredients']; ?>">
                    </dish-card>
                <?php endwhile; ?>
                <?php mysqli_close($conn); ?>
            </div>
        </div>
    </div>
</div>

<div class="spacer"></div>
<my-footer></my-footer>

<script>
    var searchInput = document.getElementById('searchInput');
    var cards = document.querySelectorAll('dish-card');
    
    searchInput.oninput = function() {
        var rawValue = this.value;
        var searchValue = sanitize(rawValue).toLowerCase();
        for(var j = 0; j < cards.length; j++) {
            var cardName = cards[j].getAttribute('name').toLowerCase();
            if(cardName.indexOf(searchValue) !== -1) {
                cards[j].style.display = '';
            } else {
                cards[j].style.display = 'none';
            }
        }
    };

    var categoryItems = document.querySelectorAll('.category-item');
    for(var i = 0; i < categoryItems.length; i++) {
        categoryItems[i].onclick = function() {
            var catId = this.getAttribute('data-cat');
            window.location.href = 'menu.php?category=' + catId;
        };
    }
</script>

</body>
</html>