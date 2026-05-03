<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "auth.php";</script>';
    exit();
}

if (isset($_GET['logout'])) {
    session_destroy();
    echo '<script>window.location.href = "../index.php";</script>';
    exit();
}

function validateCsrf() {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Ошибка безопасности. Обновите страницу и попробуйте снова.');
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$conn = mysqli_connect('localhost', 'root', '', 'tomuLvovaAlesya');
$user_id = $_SESSION['user_id'];

$res = mysqli_query($conn, "SELECT name, email, phone, bonuses FROM Users WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);

$addr_res = mysqli_query($conn, "SELECT id, full_addresses, apartment, entrance, floor, doorphone, comment FROM Users_addresses WHERE user_id = $user_id");

if (isset($_POST['add_address'])) {
    validateCsrf();
    $full = $_POST['full_addresses'];
    $apt = $_POST['apartment'];
    $ent = $_POST['entrance'];
    if (!empty($_POST['floor'])) {
        $fl = $_POST['floor'];
    } else {
        $fl = 'NULL';
    }
    $door = $_POST['doorphone'];
    $comment = $_POST['comment'];
    
    mysqli_query($conn, "INSERT INTO Users_addresses (user_id, full_addresses, apartment, entrance, floor, doorphone, comment) VALUES ($user_id, '$full', '$apt', '$ent', $fl, '$door', '$comment')");
    header('Location: profile.php');
    exit();
}

if (isset($_POST['delete_address'])) {
    validateCsrf();
    mysqli_query($conn, "DELETE FROM Users_addresses WHERE id = {$_POST['id']} AND user_id = $user_id");
    header('Location: profile.php');
    exit();
}

if (isset($_POST['update_field'])) {
    validateCsrf();
    $field = $_POST['field'];
    $value = $_POST['value'];
    
    if ($field === 'name') {
        $check_query = "SELECT id FROM Users WHERE name = '$value' AND id != $user_id";
        $check_result = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo '<script>alert("Пользователь с таким логином уже существует"); window.location.href = "profile.php";</script>';
            exit();
        }
    }
    
    mysqli_query($conn, "UPDATE Users SET $field = '$value' WHERE id = $user_id");
    header('Location: profile.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tomy - Профиль</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="icon" href="../src/icons/Tomu_logo.png">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/profile.css">
    
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    
    <script>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo 'window.isLoggedIn = true;';
            echo 'window.userName = "' . addslashes($user['name']) . '";';
        } else {
            echo 'window.isLoggedIn = false;';
            echo 'window.userName = "";';
        }
        ?>
    </script>
</head>
<body>
    <my-header></my-header>
    
    <div class="prof-container">
        <div class="left-container">
            <h2>Профиль</h2>
            <div class="user-info">
                <div class="user-pole">
                    <span class="info-text"><?php echo $user['name']; ?></span>
                    <button class="edit-btn" onclick="openEdit('name', '<?php echo $user['name']; ?>')">
                        <img src="../src/icons/pencil.png" alt="Редактировать">
                    </button>
                </div>
                <div class="user-pole">
                    <span class="info-text"><?php echo $user['email']; ?></span>
                </div>
                <div class="user-pole">
                    <span class="info-text"><?php echo $user['phone']; ?></span>
                </div>
                <div class="bonuses-container">
                    <h2 class="VAMDOSTUPNO">Вам доступно</h2>
                    <span class="bonuses-kolvo-text"><?php echo $user['bonuses']; ?></span>
                    <p class="bonuses-text">бонусов</p>
                </div>
            </div>
        </div>
        
        <div class="right-container">
            <h2>Адреса для доставки</h2>
            <div class="adresses-container">
                <?php while ($addr = mysqli_fetch_assoc($addr_res)): ?>
                    <div class="address-pole">
                        <span class="info-text">
                            <?php 
                                echo $addr['full_addresses'];
                                if ($addr['apartment']) echo ", кв." . $addr['apartment'];
                                if ($addr['entrance']) echo ", под." . $addr['entrance'];
                                if ($addr['floor']) echo ", эт." . $addr['floor'];
                                if ($addr['doorphone']) echo ", дом." . $addr['doorphone'];
                            ?>
                        </span>
                        <form method="POST" style="margin:0;">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            <input type="hidden" name="delete_address" value="1">
                            <input type="hidden" name="id" value="<?php echo $addr['id']; ?>">
                            <button type="submit" onclick="return confirm('Удалить?')" class="delete-btn">
                                <img src="../src/icons/krest.png" alt="Удалить">
                            </button>
                        </form>
                    </div>
                <?php endwhile; ?>
                <button class="add-address-btn" onclick="openAddressModal()">
                    <img src="../src/icons/plus.png" alt="Добавить адрес">
                </button>
            </div>
        </div>
    </div>

    <div class="hist-container">
        <h2>История заказов</h2>
        <div class="hist-item-container">
            <?php
            $orders_query = "SELECT o.*, s.name as status_name 
                            FROM Orders o 
                            LEFT JOIN Status s ON o.status_id = s.id 
                            WHERE o.user_id = $user_id 
                            ORDER BY o.created_date DESC";
            $orders_result = mysqli_query($conn, $orders_query);
            
            if (mysqli_num_rows($orders_result) == 0) {
                echo '<p style="text-align: center; font-family: \'El Messiri\'; color: #9F9F9F;">У вас пока нет заказов</p>';
            } else {
                while ($order = mysqli_fetch_assoc($orders_result)) {
                    $items_query = "SELECT * FROM Order_item WHERE order_id = {$order['id']}";
                    $items_result = mysqli_query($conn, $items_query);
                    $items = array();
                    while ($item = mysqli_fetch_assoc($items_result)) {
                        $items[] = $item;
                    }
                    $total_quantity = 0;
                    for ($i = 0; $i < count($items); $i++) {
                        $total_quantity += $items[$i]['quantity'];
                    }
                    $last_digit = $total_quantity % 10;
                    $last_two = $total_quantity % 100;
                    if ($last_digit == 1 && $last_two != 11) {
                        $word = 'товар';
                    } elseif (($last_digit >= 2 && $last_digit <= 4) && ($last_two < 10 || $last_two >= 20)) {
                        $word = 'товара';
                    } else {
                        $word = 'товаров';
                    }
            ?>
                <div class="hist-pole">
                    <div class="up-row">
                        <p class="date-text"><?php echo date('d.m.Y H:i', strtotime($order['created_date'])); ?></p>
                        <p class="details-title">Детали заказа</p>
                        <p class="total-amount"><?php echo $order['total_amount']; ?> ₽</p>
                    </div>
                    
                    <div class="info-row">
                        <div class="left-col">
                            <p class="delivery-type-text">
                                <?php
                                if ($order['receiving_type'] == 'pickup') {
                                    echo 'Самовывоз';
                                } else {
                                    echo 'Доставка';
                                }
                                ?>
                            </p>
                            <p class="status-text"><?php echo $order['status_name']; ?></p>
                        </div>
                        
                        <div class="mid-col">
                            <p class="adres-text">
                                <?php 
                                if ($order['receiving_type'] == 'delivery' && $order['delivery_address']) {
                                    echo $order['delivery_address'];
                                } elseif ($order['cafe_id']) {
                                    $cafe_query = "SELECT address FROM cafes WHERE id = {$order['cafe_id']}";
                                    $cafe_res = mysqli_query($conn, $cafe_query);
                                    $cafe = mysqli_fetch_assoc($cafe_res);
                                    if ($cafe) {
                                        echo $cafe['address'];
                                    } else {
                                        echo 'Адрес не указан';
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                            <?php if ($order['bonus_earned'] > 0): ?>
                                <p class="bonuses-earned-text">+<?php echo $order['bonus_earned']; ?> бонусов</p>
                            <?php endif; ?>
                            <?php if ($order['bonus_spent'] > 0): ?>
                                <p class="bonuses-spent-text">-<?php echo $order['bonus_spent']; ?> бонусов</p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="items-container">
                            <p class="count-text"><?php echo $total_quantity . ' ' . $word; ?></p>
                            <?php for ($i = 0; $i < count($items); $i++) { $item = $items[$i]; ?>
                                <div class="item-row">
                                    <span class="item-name"><?php echo $item['quantity'] . ' x ' . $item['dish_name']; ?></span>
                                    <span class="item-price"><?php echo $item['quantity'] * $item['price']; ?> ₽</span>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php
                }
            } 
            ?>
        </div>
    </div>

    <button class="logout-btn" onclick="window.location.href='?logout=1'">
        <img src="../src/icons/voiti.png" alt="выход">
        <span class="logout-text">Выйти</span>
    </button>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Редактировать</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="update_field" value="1">
                <input type="hidden" name="field" id="editField">
                <input type="text" name="value" id="editValue" required>
                <button type="submit">Сохранить</button>
            </form>
        </div>
    </div>

    <div id="addressModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeAddressModal()">&times;</span>
            <h3>Добавить адрес</h3>
            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="add_address" value="1">
                <input type="text" name="full_addresses" placeholder="Улица, дом" required>
                <input type="text" name="apartment" placeholder="Квартира">
                <input type="text" name="entrance" placeholder="Подъезд">
                <input type="text" name="floor" placeholder="Этаж">
                <input type="text" name="doorphone" placeholder="Домофон">
                <input type="text" name="comment" placeholder="Комментарий">
                <button type="submit">Сохранить</button>
            </form>
        </div>
    </div>

    <div class="spacer"></div>
    <my-footer></my-footer>

    <script>
        function openEdit(field, value) {
            document.getElementById('editField').value = field;
            document.getElementById('editValue').value = value;
            document.getElementById('editModal').style.display = 'block';
        }
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        function openAddressModal() {
            document.getElementById('addressModal').style.display = 'block';
        }
        function closeAddressModal() {
            document.getElementById('addressModal').style.display = 'none';
        }
        window.onclick = function(e) {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        }
    </script>
    <?php
    mysqli_close($conn);
    ?>
</body>
</html>