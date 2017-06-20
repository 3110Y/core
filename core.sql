-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июн 13 2017 г., 03:31
-- Версия сервера: 10.1.24-MariaDB-1~jessie
-- Версия PHP: 7.0.19-1~dotdeb+8.1

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
  `status` tinyint(1) DEFAULT '3',
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
(7, 0, 1, 'Пользователи', 'user', '', '', '', '', 'clear', 'user', 0, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 0, 1, 'Группы', 'group', '', '', '', '', 'clear', 'group', 0, 20, '2017-06-11 21:49:38', '2017-06-11 21:00:00'),
(9, 0, 1, 'Объекты правил', 'rules-objects', '', '', '', '', 'clear', 'rulesObjects', 0, 30, '2017-06-12 16:22:08', '2017-06-11 21:00:00'),
(10, 0, 1, 'Правила', 'rules', '', '', '', '', 'clear', 'rules', 0, 40, '2017-06-12 22:21:10', '2017-06-12 21:00:00'),
(11, 0, 2, 'Пользователи и Роли', 'users-and-roles', '', '', '', '', 'basic', 'basic', 0, 10, '2017-06-13 00:48:37', '2017-06-12 21:00:00'),
(12, 0, 1, 'Вход', 'enter', '', '', '', '', 'enter', 'enter', 0, 0, '2017-06-13 02:23:35', '2017-06-12 21:00:00'),
(13, 0, 1, 'Выход', 'logout', '', '', '', '', 'basic', 'logout', 0, 0, '2017-06-13 02:23:35', '2017-06-12 21:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_application`
--

CREATE TABLE `core_application` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3',
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
(1, 1, 'Административная панель', 'admin', 'admin', 0, 'basic', 'Web');

-- --------------------------------------------------------

--
-- Структура таблицы `core_group`
--

CREATE TABLE `core_group` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(250) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_group`
--

INSERT INTO `core_group` (`id`, `status`, `name`, `date_update`, `date_insert`) VALUES
(1, 1, 'Администраторы', '2017-06-11 22:42:50', '0000-00-00 00:00:00'),
(2, 1, 'Поддержка', '2017-06-12 18:43:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_rules`
--

CREATE TABLE `core_rules` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `action` int(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) DEFAULT '0',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_rules`
--

INSERT INTO `core_rules` (`id`, `status`, `object_id`, `action`, `user_id`, `group_id`, `priority`, `date_update`, `date_insert`) VALUES
(1, 1, 1, 1, 0, 0, 10, '2017-06-12 23:15:46', '0000-00-00 00:00:00'),
(2, 1, 1, 0, 0, 1, 0, '2017-06-13 00:43:15', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_rules_objects`
--

CREATE TABLE `core_rules_objects` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_rules_objects`
--

INSERT INTO `core_rules_objects` (`id`, `name`, `date_update`, `date_insert`) VALUES
(1, 'Административная панель', '2017-06-12 17:16:01', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_user`
--

CREATE TABLE `core_user` (
  `id` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `patronymic` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `group_id` varchar(250) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_user`
--

INSERT INTO `core_user` (`id`, `status`, `name`, `surname`, `patronymic`, `email`, `phone`, `login`, `password`, `group_id`, `date_update`, `date_insert`) VALUES
(1, 1, 'Г', 'Р', 'С', '', '', 'admin', '83118464172d90694a57159304d9f59560306f3901ed3b8508480fbdd9a0124ffe613d1806a27a4331cfc08f59c7f4603b9a4e14cd34825aee13530e81dd0d55', '1,2', '2017-05-18 10:36:18', '0000-00-00 00:00:00'),
(32, 3, '', '', '', '', '', '', '', '', '2017-06-13 00:31:34', '0000-00-00 00:00:00');

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
-- Индексы таблицы `core_group`
--
ALTER TABLE `core_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `core_rules`
--
ALTER TABLE `core_rules`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `core_rules_objects`
--
ALTER TABLE `core_rules_objects`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT для таблицы `core_application`
--
ALTER TABLE `core_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `core_group`
--
ALTER TABLE `core_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `core_rules`
--
ALTER TABLE `core_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `core_rules_objects`
--
ALTER TABLE `core_rules_objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
