-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 13 2024 г., 16:37
-- Версия сервера: 8.0.17
-- Версия PHP: 7.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `atkachev_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `profile_report`
--

CREATE TABLE `profile_report` (
  `id` int(11) NOT NULL,
  `id_from` int(11) NOT NULL,
  `id_to` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `creation` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `profile_report`
--

INSERT INTO `profile_report` (`id`, `id_from`, `id_to`, `text`, `creation`) VALUES
(1, 1, 2, 'Новоиспеченный специалист по Qt. В принципе норм чел 3,5/5', '2024-12-13 00:00:00'),
(2, 1, 2, 'ntcn', '2024-12-13 18:55:33'),
(4, 1, 2, 'Проверка', '2024-12-13 18:57:05'),
(5, 1, 2, 'Проверка', '2024-12-13 18:57:18'),
(6, 1, 2, 'еще проверка', '2024-12-13 18:57:48'),
(7, 1, 2, 'орплжыралпжыар пжлваырп лжварап жвлтп влтп рвлпврпжвар иовмоливаалт щи вэши рвалир вало влжаори в', '2024-12-13 18:57:58'),
(8, 3, 2, 'Достал ныть уже', '2024-12-13 19:13:01');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `profile_report`
--
ALTER TABLE `profile_report`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `profile_report`
--
ALTER TABLE `profile_report`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
