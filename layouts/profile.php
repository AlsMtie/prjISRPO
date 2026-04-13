<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo '<script>window.location.href = "auth.php";</script>';
    exit();
}

$conn = mysqli_connect('localhost', 'root', '', 'tomuLvovaAlesya');
$user_id = $_SESSION['user_id'];

$res = mysqli_query($conn, "SELECT name, email, phone, bonuses FROM Users WHERE id = $user_id");
$user = mysqli_fetch_assoc($res);

$addr_res = mysqli_query($conn, "SELECT id, full_addresses, apartment, entrance, floor, doorphone, comment FROM Users_addresses WHERE user_id = $user_id");

if (isset($_POST['add_address'])) {
    $full = $_POST['full_addresses'];
    $apt = $_POST['apartment'];
    $ent = $_POST['entrance'];
    $fl = !empty($_POST['floor']) ? $_POST['floor'] : 'NULL';
    $door = $_POST['doorphone'];
    $comment = $_POST['comment'];
    
    mysqli_query($conn, "INSERT INTO Users_addresses (user_id, full_addresses, apartment, entrance, floor, doorphone, comment) VALUES ($user_id, '$full', '$apt', '$ent', $fl, '$door', '$comment')");
    header('Location: profile.php');
    exit();
}

if (isset($_POST['delete_address'])) {
    mysqli_query($conn, "DELETE FROM Users_addresses WHERE id = {$_POST['id']} AND user_id = $user_id");
    header('Location: profile.php');
    exit();
}

if (isset($_POST['update_field'])) {
    mysqli_query($conn, "UPDATE Users SET {$_POST['field']} = '{$_POST['value']}' WHERE id = $user_id");
    header('Location: profile.php');
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tomy - Профиль</title>
    <link rel="stylesheet" href="../style/fonts/fonts.css">
    <link rel="stylesheet" href="../style/global css/global.css">
    <link rel="stylesheet" href="../style/css/header.css">
    <link rel="stylesheet" href="../style/css/footer.css">
    <link rel="stylesheet" href="../style/css/profile.css">
    
    <script src="../components/header.js"></script>
    <script src="../components/footer.js"></script>
    <script src="../scripts/cart-functions.js"></script>
    
    <script>
        window.isLoggedIn = true;
        window.userName = '<?php echo $user['name']; ?>';
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
                    <button class="edit-btn" onclick="openEdit('email', '<?php echo $user['email']; ?>')">
                        <img src="../src/icons/pencil.png" alt="Редактировать">
                    </button>
                </div>
                <div class="user-pole">
                    <span class="info-text"><?php echo $user['phone']; ?></span>
                    <button class="edit-btn" onclick="openEdit('phone', '<?php echo $user['phone']; ?>')">
                        <img src="../src/icons/pencil.png" alt="Редактировать">
                    </button>
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

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h3>Редактировать</h3>
            <form method="POST">
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
            if (e.target.classList.contains('modal')) e.target.style.display = 'none';
        }
    </script>
</body>
</html>