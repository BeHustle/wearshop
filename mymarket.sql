-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 05 2019 г., 20:36
-- Версия сервера: 5.7.23
-- Версия PHP: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `mymarket`
--

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Женщины'),
(2, 'Мужчины'),
(3, 'Дети'),
(4, 'Аксессуары');

-- --------------------------------------------------------

--
-- Структура таблицы `category_product`
--

CREATE TABLE `category_product` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `category_product`
--

INSERT INTO `category_product` (`category_id`, `product_id`) VALUES
(1, 1),
(1, 2),
(2, 2),
(3, 2),
(2, 3),
(4, 3),
(1, 4),
(2, 4),
(3, 4),
(1, 5),
(1, 6),
(1, 7),
(1, 8),
(3, 8),
(1, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `thirdname` varchar(255) DEFAULT NULL,
  `phone` decimal(12,0) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`id`, `name`, `surname`, `thirdname`, `phone`, `email`) VALUES
(1, 'Михаил', 'Зубенко', 'Петрович', '79776724767', 'zubenko@gmail.com'),
(2, 'Стас', 'Пьеха', NULL, '79632587412', 'pyeha@poteha.com'),
(3, 'Корней', 'Чуковский', NULL, '79855556254', 'korney@gmail.com'),
(4, 'Василий', 'Печень', NULL, '79999999999', 'nikto@ne.vechen'),
(5, 'Михаил', 'Кулагин', '', '79745789558', 'i.mihail@gmail.com'),
(11, 'das', 'das', '', '9999999', 'i.vlad.kleva@gmail.com'),
(12, 'Алексей', 'Алексеев', 'Алексеевич', '79055204078', 'kva@catc.coffee');

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'Администраторы'),
(2, 'Операторы');

-- --------------------------------------------------------

--
-- Структура таблицы `group_user`
--

CREATE TABLE `group_user` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `group_user`
--

INSERT INTO `group_user` (`group_id`, `user_id`) VALUES
(2, 1),
(1, 2),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `path`) VALUES
(1, 'Главная', '/'),
(2, 'Новинки', '/?new=on'),
(3, 'Sale', '/?sale=on'),
(4, 'Доставка', '/delivery');

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `delivery_type` enum('dev-yes','dev-no') NOT NULL,
  `payment_type` enum('cash','card') NOT NULL,
  `comment` text NOT NULL,
  `status` enum('Не обработан','Обработан') NOT NULL DEFAULT 'Не обработан',
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `delivery_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`id`, `client_id`, `product_id`, `address`, `delivery_type`, `payment_type`, `comment`, `status`, `date_create`, `delivery_date`) VALUES
(1, 1, 1, 'Шумиловский городок', 'dev-yes', 'cash', 'Белое платье для мафиозника в шумиловский городок', 'Обработан', '2019-04-20 15:59:37', '2019-04-23 10:00:00'),
(2, 2, 3, 'Олимпийский', 'dev-yes', 'card', 'Доставьте моей бабушке в Олимпийский', 'Обработан', '2019-04-20 16:00:46', '2019-05-13 19:00:00'),
(3, 3, 6, 'Павелецкий вокзал', 'dev-no', 'cash', '', 'Не обработан', '2019-04-20 16:01:53', '2019-04-22 00:00:00'),
(4, 4, 2, 'Бутырская тюрьма', 'dev-yes', 'card', 'Рубаха корефану в бутырку', 'Не обработан', '2019-04-14 00:00:00', '2019-04-17 00:00:00'),
(5, 12, 3, '', 'dev-no', 'card', '', 'Не обработан', '2019-05-28 14:13:15', '2019-05-28 14:13:15');

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(32) NOT NULL,
  `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `new` enum('yes','no') NOT NULL DEFAULT 'no',
  `sale` enum('yes','no') NOT NULL DEFAULT 'no',
  `img_path` varchar(255) DEFAULT NULL,
  `is_active` enum('yes','no') NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `date_create`, `new`, `sale`, `img_path`, `is_active`) VALUES
(1, 'Белое платье', 6800, '2019-04-11 22:34:01', 'yes', 'no', '/img/products/product-1.jpg', 'yes'),
(2, 'Клетчатая рубашка', 1990, '2019-04-10 22:40:41', 'yes', 'yes', '/img/products/product-2.jpg', 'yes'),
(3, 'Стильные часы', 12000, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-3.jpg', 'yes'),
(4, 'Полосатые штаны', 2350, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-4.jpg', 'yes'),
(5, 'Женская сумка', 5650, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-5.jpg', 'yes'),
(6, 'Красное платье', 8990, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-6.jpg', 'yes'),
(7, 'Розовое пальто', 18850, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-7.jpg', 'yes'),
(8, 'Зауженные джинсы', 4650, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-8.jpg', 'yes'),
(9, 'Ботинки на каблуках', 5990, '2019-04-10 22:40:41', 'yes', 'no', '/img/products/product-9.jpg', 'yes');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `patronymic` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `name`, `surname`, `patronymic`) VALUES
(1, 'i.vlad.kleva@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Владислав', 'Клева', NULL),
(2, 'admin@localhost', '202cb962ac59075b964b07152d234b70', 'Администратор', 'Администратор', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD PRIMARY KEY (`category_id`,`product_id`),
  ADD KEY `category_product_product_id` (`product_id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Индексы таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`group_id`,`user_id`),
  ADD KEY `group_user_user_id` (`user_id`);

--
-- Индексы таблицы `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_client_id` (`client_id`),
  ADD KEY `order_product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `category_product_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `group_user`
--
ALTER TABLE `group_user`
  ADD CONSTRAINT `group_user_group_id` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_user_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `order_client_id` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
