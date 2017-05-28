-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Май 28 2017 г., 20:16
-- Версия сервера: 10.1.23-MariaDB-1~jessie
-- Версия PHP: 7.0.18-1~dotdeb+8.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `core`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admin_page`
--

CREATE TABLE `admin_page` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(10) NOT NULL,
  `meta_title` varchar(50) NOT NULL,
  `meta_keywords` varchar(250) NOT NULL,
  `meta_description` varchar(200) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT 'basic',
  `controller` varchar(50) NOT NULL DEFAULT 'basic',
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `order_in_menu` int(11) NOT NULL DEFAULT '10',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_page`
--

INSERT INTO `admin_page` (`id`, `parent_id`, `status`, `name`, `url`, `icon`, `meta_title`, `meta_keywords`, `meta_description`, `template`, `controller`, `error`, `order_in_menu`, `date_update`, `date_insert`) VALUES
(1, 0, 1, 'Панель приборов', '/', '', '', '', '', 'basic', 'front', 0, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 0, 1, 'Ошибка', 'error', '', '', '', '', 'basic', 'error', 1, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 0, 1, 'Пользователи', 'user', '', '', '', '', 'clear', 'user', 0, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_application`
--

CREATE TABLE `core_application` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `url` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(50) NOT NULL DEFAULT 'basic',
  `handler` varchar(50) NOT NULL DEFAULT 'Web'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_application`
--

INSERT INTO `core_application` (`id`, `status`, `name`, `url`, `path`, `priority`, `theme`, `handler`) VALUES
(1, 1, 'Административная панель', '/', 'admin', 0, 'basic', 'Web');

-- --------------------------------------------------------

--
-- Структура таблицы `core_user`
--

CREATE TABLE `core_user` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `patronymic` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_user`
--

INSERT INTO `core_user` (`id`, `status`, `name`, `surname`, `patronymic`, `email`, `phone`, `login`, `password`, `date_update`, `date_insert`) VALUES
(1, 1, '', '', '', '', '', 'Admin', '', '2017-05-18 10:36:18', '0000-00-00 00:00:00'),
(2, 1, '', '', '', '', '', 'user', '', '2017-05-18 15:20:27', '0000-00-00 00:00:00'),
(3, 1, 'тест', '', '', '', '', '', '', '2017-05-18 17:14:34', '0000-00-00 00:00:00'),
(4, 3, '', '', '', '', '', '', '', '2017-05-28 19:52:35', '0000-00-00 00:00:00'),
(5, 3, '', '', '', '', '', '', '', '2017-05-28 19:53:09', '0000-00-00 00:00:00'),
(6, 3, '', '', '', '', '', '', '', '2017-05-28 19:55:13', '0000-00-00 00:00:00'),
(7, 3, '', '', '', '', '', '', '', '2017-05-28 19:55:23', '0000-00-00 00:00:00'),
(8, 3, '', '', '', '', '', '', '', '2017-05-28 19:55:49', '0000-00-00 00:00:00'),
(9, 3, '', '', '', '', '', '', '', '2017-05-28 19:57:16', '0000-00-00 00:00:00'),
(10, 3, '', '', '', '', '', '', '', '2017-05-28 19:59:04', '0000-00-00 00:00:00'),
(11, 3, '', '', '', '', '', '', '', '2017-05-28 20:10:17', '0000-00-00 00:00:00');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_page`
--
ALTER TABLE `admin_page`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `core_application`
--
ALTER TABLE `core_application`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `core_user`
--
ALTER TABLE `core_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_page`
--
ALTER TABLE `admin_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT для таблицы `core_application`
--
ALTER TABLE `core_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
