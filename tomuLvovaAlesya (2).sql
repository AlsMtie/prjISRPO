-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 03 2026 г., 19:01
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `tomuLvovaAlesya`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Cafes`
--

CREATE TABLE `Cafes` (
  `id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `work_time` varchar(100) NOT NULL,
  `is_active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Cafes`
--

INSERT INTO `Cafes` (`id`, `address`, `phone`, `work_time`, `is_active`) VALUES
(1, 'Петра Алексеева, 27 ', '89145968457', '10', 0),
(2, 'Улица Каландаришвили, 7 ', '89242945730', '10', 1),
(3, 'Улица Лермонтова, 63 ', '89246374812', '10', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `Categories`
--

CREATE TABLE `Categories` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Categories`
--

INSERT INTO `Categories` (`id`, `name`) VALUES
(1, 'Горячие блюда'),
(2, 'Супы'),
(3, 'Салаты'),
(4, 'Напитки'),
(5, 'Добавки'),
(6, 'Десерты');

-- --------------------------------------------------------

--
-- Структура таблицы `Dishes`
--

CREATE TABLE `Dishes` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int NOT NULL,
  `price` int NOT NULL,
  `image` varchar(255) NOT NULL,
  `ingredients` text NOT NULL,
  `is_available` tinyint(1) NOT NULL,
  `gram` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Dishes`
--

INSERT INTO `Dishes` (`id`, `name`, `category_id`, `price`, `image`, `ingredients`, `is_available`, `gram`) VALUES
(1, 'Куринные треугольники', 1, 490, 'DishesImg/Goryachee_1.png', 'Куриное филе, яйцо куриное, мука пшеничная, сухари панировочные, масло растительное, соль, перец черный молотый. Соус: майонез, кетчуп, зелень (укроп, петрушка), чеснок.', 1, 340),
(2, 'Хрустящие креветки с картофелем', 1, 780, 'DishesImg/Goryachee_2.png', 'Креветки (очищенные, в панировке), картофель, паприка, зелень, чесночный соус', 1, 700),
(3, 'Шаурма говяжья', 1, 340, 'DishesImg/Goryachee_3.png', 'Лаваш, говядина, огурец, помидор, капуста, лук, соус чесночный, соль, перец черный, кинза, кетчуп, долька лимона\r\n\r\n', 1, 500),
(4, 'Шарики с креветкой', 1, 799, 'DishesImg/Goryachee_4.png', 'Креветки, картофель, мука пшеничная, яйцо куриное, сухари панировочные, масло растительное, соль, перец черный молотый, зелень укропа. Соус: сливочно-чесночный', 1, 490),
(5, 'Королевские креветки в панировке', 1, 1230, 'DishesImg/Goryachee_5.png', 'Креветки королевские очищенные, мука пшеничная, яйцо куриное, сухари панировочные, масло растительное, соль, перец черный молотый, паприка, цветы вишни', 1, 560),
(6, 'Запечённый краб в сырном соусе ', 1, 1490, 'DishesImg/Goryachee_6.png', 'Краб, сыр твердый, сливки жирные, масло сливочное, мука пшеничная, чеснок, соль, перец белый, мускатный орех, зелень петрушки.', 1, 920),
(7, 'Кристальные баоцзы', 1, 699, 'DishesImg/Goryachee_7.png', 'Свинина, креветки, лук репчатый, имбирь свежий, соевый соус, устричный соус, кунжутное масло, сахар, соль, перец, мука пшеничная, крахмал кукурузный, вода', 1, 450),
(8, 'Острые рыбно-мясные котлетки с рисом', 1, 920, 'DishesImg/Goryachee_8.png', 'Рыба белая, мясо, водоросли нори, рис отварной, лук репчатый, яйцо куриное, чеснок, имбирь, перец чили, соевый соус, масло растительное, крахмал кукурузный, соль.', 1, 540),
(9, 'Том Ям', 2, 799, 'DishesImg/Soup_1.png', 'бульон куриный, креветки, грибы, лемонграсс, галангал, листья каффир-лайма, перец чили, сок лайма, рыбный соус, сахар, кокосовое молоко, кинза, помидоры черри.', 1, 600),
(10, 'Суп свиной с бамбуком', 2, 1699, 'DishesImg/Soup_2.png', 'Свинина нежирная, ветчина сыровяленая, побеги бамбука, перец чили, лук репчатый, лук порей, кинза, вода, соевый соус, соль.', 1, 890),
(11, 'Мясная солянка', 2, 560, 'DishesImg/Soup_3.png', 'Ветчина, бекон, сосиски, мята свежая, лук репчатый, огурцы соленые, томатная паста, оливки, каперсы, бульон мясной, лавровый лист, перец горошком, соль, сметана, лимон.', 1, 670),
(12, 'Крабовый шелк', 2, 1100, 'DishesImg/Soup_4.png', 'бульон рыбный, краб, тофу мягкий, имбирь свежий, лук зеленый, водоросли вакаме, соевый соус, кунжутное масло, соль, перец белый.', 1, 870),
(13, 'Рамен свиной', 2, 760, 'DishesImg/Soup_5.png', 'Бульон свиной, лапша пшеничная, свинина, яйцо куриное, лук зеленый, водоросли нори, соевый соус, мисо-паста, имбирь, чеснок, кунжутное масло, семена кунжута, соль.', 1, 780),
(14, 'Грушевый фундук', 2, 420, 'DishesImg/Soup_6.png', 'Груша очищенная, лепестки лотуса, фундук, имбирь, сахар, соль, уксус яблочный', 1, 410),
(15, 'Сырный суп', 2, 399, 'DishesImg/Soup_7.png', 'Сыр, яйца куриные, арахис, мята, соль, сахар, молоко', 1, 370),
(16, 'Мисо суп', 2, 450, 'DishesImg/Soup_8.png', 'Водоросли, тофу, соевый соус, соль, сахар, лук зеленый', 1, 400),
(17, 'Голубая лагуна', 4, 380, 'DishesImg/Napitok_1.png', 'сироп, мята, лимон, газированная вода', 1, 250),
(18, 'Райское наслаждение', 4, 500, 'DishesImg/Napitok_2.png', 'сироп, мята, лимон, газированная вода', 1, 250),
(19, 'Красная граната', 4, 340, 'DishesImg/Napitok_3.png', 'сироп, мята, лимон, газированная вода', 1, 410),
(20, 'Картофельный салат', 3, 430, 'DishesImg/Salad_1.png', 'Картофель очищенный, капуста, фунчоза, перец красный, перец, соль', 1, 549),
(21, 'Креветка горошек', 3, 420, 'DishesImg/Salad_2.png', 'очищенные креветки, горошек зеленый, масло оливковое, уксусное', 1, 340),
(22, 'Лепешка с мясом', 5, 410, 'DishesImg/Dobavki_1.png', 'мука, соль, яйца куриные, свинина, чеснок, лук, перец, соус', 1, 250),
(23, 'Яблочный мохито', 4, 460, 'DishesImg/Napitok_4.png', 'сироп, мята, лимон, газированная вода', 1, 340),
(24, 'Апельсиновый фреш', 4, 320, 'DishesImg/Napitok_5.png', 'сироп, мята, апельсин, вода', 1, 350),
(25, 'Виноградный бум', 4, 320, 'DishesImg/Napitok_6.png', 'сироп, мята, виноград, газированная вода', 1, 250),
(26, 'Мятное облако', 4, 430, 'DishesImg/Napitok_7.png', 'сироп, мята, сливки, молоко', 1, 450),
(27, 'Молочная ваниль', 4, 380, 'DishesImg/Napitok_8.png', 'сироп, мята, ваниль, молоко', 1, 340),
(28, 'Лосось под базиликом', 3, 450, 'DishesImg/Salad_3.png', 'Лосось, базилик, гранат, соль, масло подсолнечное, творожный сыр', 1, 410),
(29, 'Карусель лососи', 3, 560, 'DishesImg/Salad_4.png', 'Лосось, брокколи, сыр, соль, масло', 1, 300),
(30, 'Сырный дождь', 3, 410, 'DishesImg/Salad_5.png', 'Моцарелла, сулугуни, кукуруза, ветчина', 1, 400),
(31, 'Баклажановые колокольчики', 3, 320, 'DishesImg/Salad_6.png', 'Баклажаны, крахмал, масло подсолнечное, колокольчики молочноцветковый, зеленый горошек, соль', 1, 500),
(32, 'Салат бекона', 3, 360, 'DishesImg/Salad_7.png', 'Бекон, перец красный, соевый соус, кетчуп, соль, сахар, зелень', 1, 250),
(33, 'Яичный салат', 3, 350, 'DishesImg/Salad_8.png', 'Яйцо отварное, яблоки, картофель, салат, соль, перец, петрушка', 1, 300),
(34, 'Клубничный рулет', 6, 450, 'DishesImg/Desert_1.png', 'Мука пшеничная, сахар, молоко, яйцо, клубника, масло сливочное, соль', 1, 150),
(35, 'Воздушные макарони', 6, 550, 'DishesImg/Desert_2.png', 'Мука пшеничная, молоко, сливки, яйца, сахар, мята, сыр творожный', 1, 100),
(36, 'Персиковые лотосы', 6, 410, 'DishesImg/Desert_3.png', 'Мука пшеничная, масло сливочное, молоко, яйца, персик, сахар, соль', 1, 120),
(37, 'Шоколадное пирожное', 6, 400, 'DishesImg/Desert_4.png', 'Мука пшеничная, молоко, яйца, сыр творожный, какао, соль, сахар', 1, 250),
(38, 'Ассорти макарони', 6, 500, 'DishesImg/Desert_5.png', 'Мука миндальная, яйца, сахар, орехи, молоко, сыр творожный, соль', 1, 300),
(39, 'Тайяки вишневые', 6, 450, 'DishesImg/Desert_6.png', 'Мука пшеничная, вода, сахар, яйцо, вишня, соль', 1, 300),
(40, 'Чизкейк шоколадный', 6, 320, 'DishesImg/Desert_7.png', 'Сыр твороженный, сахар, ореховая паста, какао, пломбир', 1, 200),
(41, 'Данго', 6, 250, 'DishesImg/Desert_8.png', 'Мука рисовая, вода, сахар, сироп', 1, 100),
(42, 'Нарезка мясная', 5, 520, 'DishesImg/Dobavki_2.png', 'Колбаса, бекон, сосиски, помидоры черри, салат, соль, масло подсолнечное', 1, 340),
(43, 'Набор снеков', 5, 400, 'DishesImg/Dobavki_3.png', 'Лук, крахмал. мука, вода, масло подсолнечное, курица', 1, 300),
(44, 'Рыбный шашлык', 5, 400, 'DishesImg/Dobavki_4.png', 'Селедь, лосось, карась, соль', 1, 300),
(45, 'Шницель', 5, 450, 'DishesImg/Dobavki_5.png', 'Свинина, мука, масло подсолнечное, чесночный соус, брокколи', 1, 300),
(46, 'Чесночный хлеб', 5, 400, 'DishesImg/Dobavki_6.png', 'Мука, вода, соль, дрожжи, петрушка, яйцо, масло подсолнечное', 1, 360),
(47, 'Онигири с тунцом', 5, 400, 'DishesImg/Dobavki_7.png', 'Рис, уксус, тунец, нори, соль', 1, 250),
(48, 'Хрустящий батат', 5, 450, 'DishesImg/Dobavki_8.png', 'Батат, мука, масло подсолнечное, яйцо, чесночный соус, лайм, соль', 1, 300);

-- --------------------------------------------------------

--
-- Структура таблицы `Orders`
--

CREATE TABLE `Orders` (
  `id` int NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `user_id` int NOT NULL,
  `receiving_type` enum('delivery','pickup') NOT NULL,
  `delivery_address` text,
  `cafe_id` int DEFAULT NULL,
  `status_id` int NOT NULL,
  `bonus_spent` int NOT NULL,
  `bonus_earned` int NOT NULL,
  `total_amount` int NOT NULL,
  `comment` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Orders`
--

INSERT INTO `Orders` (`id`, `order_number`, `user_id`, `receiving_type`, `delivery_address`, `cafe_id`, `status_id`, `bonus_spent`, `bonus_earned`, `total_amount`, `comment`, `created_date`) VALUES
(13, '2026041625650', 1, 'pickup', NULL, 2, 5, 33, 32, 3167, '', '2026-04-16 14:16:14'),
(14, '2026041670858', 6, 'delivery', 'ул. Сунтарская 7', NULL, 5, 0, 27, 2799, '', '2026-04-16 14:50:09'),
(15, '2026042015888', 8, 'delivery', 'ул. Петра Алексеева 27', NULL, 5, 0, 18, 1880, '', '2026-04-20 17:39:59'),
(16, '2026042468696', 10, 'delivery', 'ул. Петра Алексеева 27', NULL, 5, 0, 10, 1000, '', '2026-04-24 15:45:49');

-- --------------------------------------------------------

--
-- Структура таблицы `Order_item`
--

CREATE TABLE `Order_item` (
  `id` int NOT NULL,
  `order_id` int NOT NULL,
  `dish_id` int NOT NULL,
  `quantity` int NOT NULL,
  `dish_name` varchar(100) NOT NULL,
  `price` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Order_item`
--

INSERT INTO `Order_item` (`id`, `order_id`, `dish_id`, `quantity`, `dish_name`, `price`) VALUES
(29, 13, 1, 2, 'Куринные треугольники', 490),
(30, 13, 11, 2, 'Мясная солянка', 560),
(31, 13, 12, 1, 'Крабовый шелк', 1100),
(32, 14, 4, 1, 'Шарики с креветкой', 799),
(33, 14, 28, 1, 'Лосось под базиликом', 450),
(34, 14, 18, 2, 'Райское наслаждение', 500),
(35, 14, 35, 1, 'Воздушные макарони', 550),
(36, 15, 1, 3, 'Куринные треугольники', 490),
(37, 15, 30, 1, 'Сырный дождь', 410),
(38, 16, 18, 2, 'Райское наслаждение', 500);

-- --------------------------------------------------------

--
-- Структура таблицы `Status`
--

CREATE TABLE `Status` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Status`
--

INSERT INTO `Status` (`id`, `name`) VALUES
(1, 'Принят'),
(2, 'Готовится'),
(3, 'Передан курьеру'),
(4, 'Курьер в пути'),
(5, 'Закрыт');

-- --------------------------------------------------------

--
-- Структура таблицы `Users`
--

CREATE TABLE `Users` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `bonuses` int NOT NULL,
  `regestration_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users`
--

INSERT INTO `Users` (`id`, `name`, `password`, `email`, `phone`, `bonuses`, `regestration_date`) VALUES
(1, 'alesya', '$2y$10$bGGl9V4F4ohMhV2ZFtRRDu4YAEc8XMK6meSaEhSi96CkLpVihG7VC', 'alesyalvova07@gmail.com', '89243618336', 64, '2026-04-07'),
(6, 'Kun', '$2y$10$gGB3fcGj/sa2KPEhhtmR2eoHrHH8BML7wul93vgIjdegvYDDFxu7m', 'kun@gmail.com', '89242323232', 27, '2026-04-16'),
(7, 'aldawdw', '$2y$10$ix/UfGBmMBy8Rxvw7c7da.WcQh.sWpipLNh2g2EwnqAMzEFGixQje', 'a@gmail.com', '89243333333', 0, '2026-04-20'),
(8, 'gfd', '$2y$10$aPsmSewA3gXlSdxI9UEE.uNjJq1DRBjJ2wvo9BiYConkbu05IJVNm', 'tee@gmail.com', '89243333339', 18, '2026-04-20'),
(9, 'teabreak', '$2y$10$hZlcCPh0NctJqp82vTp89.R1wwX7matFVJF7nQ0CGF8UQrPOqhu9W', 'tea@gmail.com', '89243512382', 0, '2026-04-24'),
(10, 'klient', '$2y$10$3G02SX36vAkU9zhR27pT5uQ92gnu2lnVDXd0G6p40xWD0QzZDclM6', 'klient@gmail.com', '89243512282', 10, '2026-04-24');

-- --------------------------------------------------------

--
-- Структура таблицы `Users_addresses`
--

CREATE TABLE `Users_addresses` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `full_addresses` varchar(255) NOT NULL,
  `apartment` varchar(10) DEFAULT NULL,
  `entrance` varchar(5) DEFAULT NULL,
  `floor` int DEFAULT NULL,
  `doorphone` varchar(20) DEFAULT NULL,
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `Users_addresses`
--

INSERT INTO `Users_addresses` (`id`, `user_id`, `full_addresses`, `apartment`, `entrance`, `floor`, `doorphone`, `comment`) VALUES
(5, 1, 'ул. Петра Алексеева 25', '1', '1', 2, '1', ''),
(6, 1, 'Дзержинского 57', '56', '3', 6, '56', 'Позвонить'),
(7, 6, 'ул. Сунтарская 7', '', '', NULL, '', 'Позвонить'),
(8, 8, 'ул. Петра Алексеева 27', '1', '1', 1, '1', ''),
(9, 10, 'ул. Петра Алексеева 27', '12', '1', 1, '12', '');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `Cafes`
--
ALTER TABLE `Cafes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Dishes`
--
ALTER TABLE `Dishes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cafe_id` (`cafe_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `Order_item`
--
ALTER TABLE `Order_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `dish_id` (`dish_id`);

--
-- Индексы таблицы `Status`
--
ALTER TABLE `Status`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `Users_addresses`
--
ALTER TABLE `Users_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `Cafes`
--
ALTER TABLE `Cafes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `Categories`
--
ALTER TABLE `Categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `Dishes`
--
ALTER TABLE `Dishes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT для таблицы `Orders`
--
ALTER TABLE `Orders`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `Order_item`
--
ALTER TABLE `Order_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT для таблицы `Status`
--
ALTER TABLE `Status`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `Users`
--
ALTER TABLE `Users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `Users_addresses`
--
ALTER TABLE `Users_addresses`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Dishes`
--
ALTER TABLE `Dishes`
  ADD CONSTRAINT `dishes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `Orders`
--
ALTER TABLE `Orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`cafe_id`) REFERENCES `Cafes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `Status` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `Order_item`
--
ALTER TABLE `Order_item`
  ADD CONSTRAINT `order_item_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `order_item_ibfk_2` FOREIGN KEY (`dish_id`) REFERENCES `Dishes` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ограничения внешнего ключа таблицы `Users_addresses`
--
ALTER TABLE `Users_addresses`
  ADD CONSTRAINT `users_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
