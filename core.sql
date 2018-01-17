-- phpMyAdmin SQL Dump
-- version 4.4.15.7
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 17 2018 г., 13:59
-- Версия сервера: 5.5.50-MariaDB
-- Версия PHP: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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

CREATE TABLE IF NOT EXISTS `admin_page` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '3',
  `name` varchar(255) NOT NULL,
  `text` longtext NOT NULL,
  `url` varchar(255) NOT NULL,
  `icon` varchar(15) NOT NULL,
  `meta_title` varchar(50) NOT NULL,
  `meta_keywords` varchar(250) NOT NULL,
  `meta_description` varchar(200) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT 'clear',
  `controller` varchar(250) NOT NULL DEFAULT 'basic',
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `basic` tinyint(1) NOT NULL DEFAULT '0',
  `order_in_menu` int(11) NOT NULL DEFAULT '10',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `admin_page`
--

INSERT INTO `admin_page` (`id`, `parent_id`, `status`, `name`, `text`, `url`, `icon`, `meta_title`, `meta_keywords`, `meta_description`, `template`, `controller`, `error`, `basic`, `order_in_menu`, `date_update`, `date_insert`) VALUES
(1, 0, 1, 'Панель приборов', '', '/', '', '', '', '', 'basic', 'system\\common\\front', 0, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 0, 1, 'Ошибка', '', 'error', '', '', '', '', 'basic', 'system\\common\\error', 1, 0, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 11, 1, 'Пользователи', '', 'user', 'user', '', '', '', 'clear', 'system\\rules\\user', 0, 0, 10010, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 11, 1, 'Группы', '', 'group', 'users', '', '', '', 'clear', 'system\\rules\\group', 0, 0, 10020, '2017-06-11 21:49:38', '2017-06-11 21:00:00'),
(9, 11, 1, 'Объекты правил', '', 'rules-objects', 'info', '', '', '', 'clear', 'system\\rules\\rulesObjects', 0, 0, 10030, '2017-06-12 16:22:08', '2017-06-11 21:00:00'),
(10, 11, 1, 'Правила', '', 'rules', 'question', '', '', '', 'clear', 'system\\rules\\rules', 0, 0, 10040, '2017-06-12 22:21:10', '2017-06-12 21:00:00'),
(11, 0, 1, 'Пользователи и Роли', '', 'users-and-roles', 'users', '', '', '', 'clear', 'system\\rules\\usersRoles', 0, 0, 10000, '2017-06-13 00:48:37', '2017-06-12 21:00:00'),
(12, 0, 1, 'Вход', '', 'enter', '', '', '', '', 'enter', 'system\\common\\enter', 0, 0, 0, '2017-06-13 02:23:35', '2017-06-12 21:00:00'),
(13, 0, 1, 'Выход', '', 'logout', '', '', '', '', 'basic', 'system\\common\\logout', 0, 0, 0, '2017-06-13 02:23:35', '2017-06-12 21:00:00'),
(14, 0, 1, 'Страницы', '', 'page', 'file', '', '', '', 'clear', 'page', 0, 0, 10, '2017-06-13 05:49:36', '2017-06-12 21:00:00'),
(20, 0, 1, 'Настройки', '', 'settings', 'settings', '', '', '', 'clear', 'system\\common\\settings', 0, 0, 9000, '2017-12-18 09:10:53', '0000-00-00 00:00:00'),
(21, 0, 1, 'Тесты', '', 'test', 'push', '', '', '', 'clear', 'system\\test\\test', 0, 0, 20000, '2017-12-18 09:24:06', '0000-00-00 00:00:00'),
(22, 21, 1, 'Поля', '', 'field', 'file-edit', '', '', '', 'clear', 'system\\test\\field', 0, 0, 20010, '2017-12-19 11:56:26', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `client_page`
--

CREATE TABLE IF NOT EXISTS `client_page` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '3',
  `name` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `url` varchar(255) NOT NULL,
  `meta_title` varchar(50) NOT NULL,
  `meta_keywords` varchar(250) NOT NULL,
  `meta_description` varchar(200) NOT NULL,
  `template` varchar(50) NOT NULL DEFAULT 'basic',
  `controller` varchar(50) NOT NULL DEFAULT 'basic',
  `error` tinyint(1) NOT NULL DEFAULT '0',
  `order_in_menu` int(11) NOT NULL DEFAULT '10',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `client_page`
--

INSERT INTO `client_page` (`id`, `parent_id`, `status`, `name`, `content`, `url`, `meta_title`, `meta_keywords`, `meta_description`, `template`, `controller`, `error`, `order_in_menu`, `date_update`, `date_insert`) VALUES
(2, 0, 1, 'Главная', '', '/', 'ytrf', '', '', 'front', 'basic', 0, 0, '2017-06-13 14:57:11', '0000-00-00 00:00:00'),
(8, 0, 1, '404', '', '404', '', '', '', 'error', 'basic', 1, 0, '2017-06-14 16:34:06', '0000-00-00 00:00:00'),
(15, 0, 2, 'тест', '<h2>Текст-&laquo;рыба&raquo; имеет функцию заполнения места или сравнения воздействия рисунков шрифта</h2>\r\n\r\n<p>Рыбным текстом называется текст, служащий для временного наполнения макета в публикациях или производстве веб-сайтов, пока финальный текст еще не создан. Рыбный текст также известен как текст-заполнитель или же текст-наполнитель. Иногда текст-&laquo;рыба&raquo; также используется композиторами при написании музыки. Они напевают его перед тем, как сочинены соответствующие слова. Уже в 16-том веке рыбные тексты имели широкое распространение у печатников.</p>\r\n\r\n<h2>Целенаправленность при одновременной бессмысленности содержания</h2>\r\n\r\n<p>Рыбные тексты также применяются для демонстрации различных видов шрифта и в разработке макетов. Как правило их содержание бессмысленно. По причине своей функции текста-заполнителя для макетов нечитабельность рыбных текстов имеет особое значение, так как человеческое восприятие имеет особенность, распознавать определенные образцы и повторения. В случае произвольного набора букв и длины слов ничто не отвлекает от оценки воздействия и читаемости различных шрифтов, а также от распределения текста на странице (макет или площадь набора). Поэтому большинство рыбных текстов состоят из более или менее произвольного набора слов и слогов. Таким образом образцы повторения не отвлекают от общей картины, а шрифты имеют лучшую базу сравнения. Преимущественно конечно, если рыбный текст кажется в некоторой степени реалистичным, не искажая тем самым воздействие макета финальной публикации.</p>\r\n\r\n<h2>Неразборчивость или удобочитаемость? Вот в чем вопрос</h2>\r\n\r\n<p>Самый известный текст-&laquo;рыба&raquo;, называемый Lorem ipsum, имеет свои корни в 16-том веке. Lorem ipsum был создан на псевдо-латыни, которая приблизительно соответствует настоящей латыни. Этот текст содержит ряд настоящих латинских слов. Как и большинство других текстов, Lorem ipsum не имеет смысла, а лишь имитирует ритм большинства европейских языков использующих латиницу. Латинское происхождение и относительная бессмысленность текста Lorem ipsum хороши тем, что не привлекают к себе внимания и не отвлекают от дизайна.</p>\r\n\r\n<p>Недостатком является то обстоятельство, что в латыни некоторые буквы используются чаще или реже других, что создает другое изображение печати. Кроме того в латинском языке заглавная буква используется только в начале предложений. Тем самым Lorem ipsum не отображает написание, к примеру, имен собственных. Вследствии этого использование этого текста в качестве визуального заполнителя макета в испанском языке ограничено. Если вы хотите использовать рыбный текст для сравнения особенностей шрифтов, то имеет смысл выбрать текст содержащий, по возможности все буквы и типичные для языка специальные знаки.</p>\r\n\r\n<p>В настоящее время существует множество читаемых рыбных текстов. В большинстве случаев они используются только в целях заполнения места. Часто эти альтернативы классического Lorem ipsum занимательны и рассказывают небольшие, веселые или бессмысленные истории.</p>\r\n\r\n<h2>Происхождение и значение текста Lorem ipsum</h2>\r\n\r\n<p>Согласно большинству источников Lorem ipsum представляет собой отрывок одного из трактатов Цицерона и его корни уходят в 45 век до н. э. Взявшись за поиски происхождения одного из самых странных слов в латыни &laquo;consectetur&raquo; учитель латинского языка нашел соответствия с текстом Цицерона &laquo;De finibus bonorum et malorum&raquo; (&laquo;О пределах добра и зла&raquo;) достаточно известного в средневековье: &laquo;Neque porro quisquam est, qui dolorem ipsum, quia dolor sit, amet, consectetur, adipisci velit [...]&raquo; (Перевод: &laquo;нет никого, кто возлюбил бы, предпочел и возжаждал бы само страдание только за то, что это страдание...&raquo;). Типичный текст Lorem ipsum звучит приблизительно так: &quot;Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim [...]&quot;.</p>\r\n\r\n<p>Сегодня употребляются лишь фрагменты оригинального текста Lorem ipsum. Предполагается, что с течением времени некоторые буквы были удалены и некоторые добавлены в слова изначального текста. Именно поэтому на сегодняшний день существуют несколько вариантов текста, немного отличающихся друг от друга. По причине своего древнего происхождения Lorem ipsum не имеет авторских прав.</p>\r\n\r\n<p>В 60-е годы при публикации листов Letraset текст Lorem ipsum получил известность за пределами печатников и дизайнеров (буквы-наклейки на прозрачной пленке были популярны и распространены до 80-х годов). Позже версии этого текста поставлялись с настольными издательскими системами (DTP = Desktop-Publishing), такими как PageMaker и др.</p>\r\n\r\n<h2>Автоматическое распознавание Lorem ipsum при подготовке к печати</h2>\r\n\r\n<p>С распространением компьютеров и программ для верстки и дизайна рыбные тексты находили все большую известность. Если раньше в &laquo;рыбах&raquo; использовалось лишь постояное повторение некоторых строк текста Lorem ipsum, то сегодня текст Цицерона служит основой многих рыбных текстов или их генераторов. Основываясь на оригинальном тексте происходит автоматическая генерация более длинных абзацев Lorem ipsum или же создание абсолютно новых рыбных текстов.</p>\r\n\r\n<p>На сегодняшний день последовательность слов текста Lorem ipsum настолько распространена и общепринята, что многие DTP-программы в состоянии создать рыбные тексты с начальной секвенцией &laquo;Lorem ipsum&raquo;. Большое преимущество имеет тот факт, что эта последовательность также распознается при редактировании данных в подготовке к печати. При помощи предупреждающего сигнала можно избежать нечаяного попадания оставшегося рыбного текста в печать.</p>\r\n\r\n<p>Некоторые интернет-провайдеры используют с выгодой для себя то обстоятельство, что поисковые системы не распознают рыбные тексты, тем самым не различая осмысленную информацию от бессмысленной: таким образом целенаправленно созданная &laquo;рыба&raquo; со специальной комбинацией поисковых слов может привести к повышенной посещаемости. Увеличивая тем самым доходы от рекламы, которые зависят от количества результатов поиска того или иного веб-сайта.</p>\r\n', 'test', '', '', '', 'basic', 'basic', 0, 0, '2017-12-15 16:21:05', '2017-12-15 16:21:05');

-- --------------------------------------------------------

--
-- Структура таблицы `client_settings`
--

CREATE TABLE IF NOT EXISTS `client_settings` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '3',
  `meta_title` varchar(50) NOT NULL,
  `meta_keywords` varchar(250) NOT NULL,
  `meta_description` varchar(200) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `client_settings`
--

INSERT INTO `client_settings` (`id`, `parent_id`, `status`, `meta_title`, `meta_keywords`, `meta_description`, `date_update`, `date_insert`) VALUES
(1, 0, 1, '', '', '', '2017-12-18 10:25:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_application`
--

CREATE TABLE IF NOT EXISTS `core_application` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(50) NOT NULL,
  `site` varchar(50) NOT NULL,
  `basicController` varchar(200) NOT NULL,
  `url` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `priority` int(11) NOT NULL DEFAULT '0',
  `theme` varchar(50) NOT NULL DEFAULT 'basic'
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_application`
--

INSERT INTO `core_application` (`id`, `parent_id`, `status`, `name`, `site`, `basicController`, `url`, `path`, `priority`, `theme`) VALUES
(1, 0, 1, 'Административная панель', 'core.develop', 'system\\common\\basic', 'admin', 'admin', 0, 'basic'),
(5, 0, 1, 'Административная панель', 'dev.varloc.pw', 'system\\common\\basic', 'admin', 'admin', 0, 'basic');

-- --------------------------------------------------------

--
-- Структура таблицы `core_group`
--

CREATE TABLE IF NOT EXISTS `core_group` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `name` varchar(250) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_group`
--

INSERT INTO `core_group` (`id`, `parent_id`, `status`, `name`, `date_update`, `date_insert`) VALUES
(1, 0, 1, 'Администраторы', '2017-06-11 22:42:50', '0000-00-00 00:00:00'),
(2, 0, 1, 'Поддержка', '2017-06-12 18:43:06', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_rules`
--

CREATE TABLE IF NOT EXISTS `core_rules` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '3',
  `object_id` int(11) NOT NULL DEFAULT '0',
  `action` int(1) NOT NULL DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0',
  `priority` int(11) DEFAULT '0',
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_rules`
--

INSERT INTO `core_rules` (`id`, `parent_id`, `status`, `object_id`, `action`, `user_id`, `group_id`, `priority`, `date_update`, `date_insert`) VALUES
(4, 0, 1, 13, 1, 0, 1, 0, '2017-12-29 04:04:32', '2017-12-29 04:04:32'),
(3, 0, 1, 13, 0, 0, 0, 999, '2017-12-29 03:27:59', '2017-12-29 03:27:59');

-- --------------------------------------------------------

--
-- Структура таблицы `core_rules_objects`
--

CREATE TABLE IF NOT EXISTS `core_rules_objects` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `key` varchar(250) NOT NULL,
  `name` varchar(255) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_rules_objects`
--

INSERT INTO `core_rules_objects` (`id`, `parent_id`, `key`, `name`, `date_update`, `date_insert`) VALUES
(21, 0, 'application_1_page_21', 'Отображать пункт меню Тесты Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(20, 0, 'application_1_page_10', 'Отображать пункт меню Правила Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(19, 0, 'application_1_page_9', 'Отображать пункт меню Объекты правил Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(18, 0, 'application_1_page_8', 'Отображать пункт меню Группы Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(17, 0, 'application_1_page_7', 'Отображать пункт меню Пользователи Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(16, 0, 'application_1_page_11', 'Отображать пункт меню Пользователи и Роли Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(15, 0, 'application_1_page_20', 'Отображать пункт меню Настройки Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(13, 0, 'application_1', 'Вход в приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(14, 0, 'application_1_page_14', 'Отображать пункт меню Страницы Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00'),
(22, 0, 'application_1_page_22', 'Отображать пункт меню Поля Приложение: Административная панель', '2017-12-29 03:26:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Структура таблицы `core_user`
--

CREATE TABLE IF NOT EXISTS `core_user` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
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
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_user`
--

INSERT INTO `core_user` (`id`, `parent_id`, `status`, `name`, `surname`, `patronymic`, `email`, `phone`, `login`, `password`, `group_id`, `date_update`, `date_insert`) VALUES
(1, 0, 1, 'Г', 'Р', 'С', '', '', 'admin', '83118464172d90694a57159304d9f59560306f3901ed3b8508480fbdd9a0124ffe613d1806a27a4331cfc08f59c7f4603b9a4e14cd34825aee13530e81dd0d55', '1,2', '2017-05-18 10:36:18', '0000-00-00 00:00:00'),
(55, 0, 1, '', '', '', '', '', 'тест', '', '', '2017-12-16 15:43:45', '2017-12-16 15:43:45');

-- --------------------------------------------------------

--
-- Структура таблицы `core_user_group`
--

CREATE TABLE IF NOT EXISTS `core_user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `group_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=173 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `core_user_group`
--

INSERT INTO `core_user_group` (`id`, `user_id`, `group_id`) VALUES
(172, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `test_field`
--

CREATE TABLE IF NOT EXISTS `test_field` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) DEFAULT '3',
  `CKEditor` longtext NOT NULL,
  `select2` int(11) NOT NULL DEFAULT '0',
  `select2_multiple` varchar(255) NOT NULL,
  `UKImageUpload` varchar(255) NOT NULL,
  `UKInput` varchar(255) NOT NULL,
  `UKNumber` int(11) NOT NULL DEFAULT '0',
  `UKPassword` varchar(512) NOT NULL,
  `UKSelect` int(11) NOT NULL DEFAULT '0',
  `UKSelect_multiple` varchar(255) NOT NULL,
  `UKTextarea` text NOT NULL,
  `UKURIName` varchar(255) NOT NULL,
  `JSColor` varchar(255) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_insert` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test_field`
--

INSERT INTO `test_field` (`id`, `parent_id`, `status`, `CKEditor`, `select2`, `select2_multiple`, `UKImageUpload`, `UKInput`, `UKNumber`, `UKPassword`, `UKSelect`, `UKSelect_multiple`, `UKTextarea`, `UKURIName`, `JSColor`, `date_update`, `date_insert`) VALUES
(1, 0, 3, '<p>тест</p>\r\n', 1, '1,2', '', 'тест', 10, '', 1, '1,2', 'тест', 'test', '', '2017-12-19 12:33:20', '2017-12-19 12:33:20');

-- --------------------------------------------------------

--
-- Структура таблицы `test_field_photo`
--

CREATE TABLE IF NOT EXISTS `test_field_photo` (
  `id` int(11) NOT NULL,
  `img` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order_in_img` int(11) NOT NULL,
  `date_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `admin_page`
--
ALTER TABLE `admin_page`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `client_page`
--
ALTER TABLE `client_page`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `client_settings`
--
ALTER TABLE `client_settings`
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
-- Индексы таблицы `core_user_group`
--
ALTER TABLE `core_user_group`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test_field`
--
ALTER TABLE `test_field`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `test_field_photo`
--
ALTER TABLE `test_field_photo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `admin_page`
--
ALTER TABLE `admin_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `client_page`
--
ALTER TABLE `client_page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `client_settings`
--
ALTER TABLE `client_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `core_application`
--
ALTER TABLE `core_application`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `core_group`
--
ALTER TABLE `core_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `core_rules`
--
ALTER TABLE `core_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `core_rules_objects`
--
ALTER TABLE `core_rules_objects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT для таблицы `core_user`
--
ALTER TABLE `core_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=56;
--
-- AUTO_INCREMENT для таблицы `core_user_group`
--
ALTER TABLE `core_user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=173;
--
-- AUTO_INCREMENT для таблицы `test_field`
--
ALTER TABLE `test_field`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `test_field_photo`
--
ALTER TABLE `test_field_photo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
