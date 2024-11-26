-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Ноя 05 2024 г., 13:49
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
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `F` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `I` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `O` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `birth` date NOT NULL,
  `login` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `registration` datetime NOT NULL,
  `id_city` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `role` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `F`, `I`, `O`, `email`, `birth`, `login`, `password`, `registration`, `id_city`, `status`, `role`) VALUES
(1, 'Ткачев', 'Андрей', 'Дмитриевич', 'tad@mail.ru', '2003-01-01', 'tad', '3e391ab759390d3f2fb834431a494f58', '2024-10-01 12:52:00', 3, 1, 1),
(2, 'Шубин', 'Владимир', 'Евгеньевич', 'sve@mail.ru', '2003-02-01', 'sve', '6cd801edda713f7926d55a6ce0fa50f6', '2024-10-01 12:52:00', 7, 1, 1),
(3, 'Оськина', 'Ксения', 'Юрьевна', 'okj@mail.ru', '2003-03-01', 'okj', 'c98182a7998a5c6016cc66e43fac5a87', '2024-10-01 12:52:00', 3, 1, 1),
(4, 'Апенышева', 'Александра', 'Игоревна', 'aai@mail.ru', '2003-04-01', 'aai', 'ac9724b765a0176776c0d626b5eaf306', '2024-10-01 12:52:00', 5, 1, 1),
(5, 'Нежурко', 'Игорь', 'Андреевич', 'nia@mail.ru', '2003-05-01', 'nia', '04a481486dd84d7c8bfdfc89d38136a6', '2024-10-01 12:52:00', 4, 1, 1),
(6, 'Бакусов', 'Павел', 'Анатольевич', 'bpa@mail.ru', '2003-06-01', 'bpa', 'bf5a3e6f4cd73cc97e3862d4369bb723', '2024-10-01 12:52:00', 1, 1, 1),
(7, 'Тимохин', 'Михаил', 'Юрьевич', 'tmj@mail.ru', '2003-07-01', 'tmj', 'd4e5a09599ab8915a4f8b27ad6518936', '2024-10-01 12:52:00', 1, 1, 1),
(8, 'Бахарев', 'Данила', 'Дмитриевич', 'bdd@mail.ru', '2003-08-01', 'bdd', '61de962f19b684dc9ce24c0fdcdbd0de', '2024-10-01 12:52:00', 3, 1, 1),
(9, 'Тимофеев', 'Илья', 'Сергеевич', 'tis@mail.ru', '2003-09-01', 'tis', '12662f0b3e3b7454907bff5ac8b985fa', '2024-10-01 12:52:00', 3, 1, 1),
(10, 'Стрельников', 'Максим', 'Дмитриевич', 'smd@mail.ru', '2003-09-01', 'smd', '3a096b9fe19fedd4636f217ef0c6e33f', '2024-10-01 12:52:00', 6, 1, 1),
(15, 'Wiggov', 'Wigga', 'Wiggovich', 'wigga@mail.ru', '2021-09-03', 'wigga', 'e6acfc0c1213cfb63bc74944c1ee9203', '2024-11-05 16:46:49', 4, 1, 1);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
