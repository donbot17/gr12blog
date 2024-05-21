-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2024 at 01:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `category` varchar(33) NOT NULL,
  `descr` varchar(100) NOT NULL,
  `viewed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`cat_id`, `category`, `descr`, `viewed`) VALUES
(4, 'music', 'general topic for all your tunes', 1),
(6, 'rock', 'rock n roll hard with this topic all about rock!', 1),
(7, 'classical', 'be transported back to the old days when discussing those old guys who wrote music', 1),
(8, 'indie', 'new music around that sounds kind of funky ahah!', 1),
(9, 'country', 'sweet home alabama', 1),
(10, 'pop', 'all those pop songs', 1),
(11, 'operah', 'yknow like the phantom', 0),
(12, 'metal', 'hardcore', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(9) NOT NULL,
  `content` varchar(500) NOT NULL,
  `viewed` tinyint(1) NOT NULL,
  `auth_id` int(9) NOT NULL,
  `post_id` int(9) NOT NULL,
  `datepost` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `content`, `viewed`, `auth_id`, `post_id`, `datepost`) VALUES
(1, '<p>BOLLOX</p>', 1, 8, 5, '2024-04-03'),
(2, '<p>www</p>', 1, 8, 5, '2024-04-03'),
(3, '<p>errrrmmmm actually…..</p>', 1, 8, 3, '2024-04-03'),
(4, '<p>kys loser</p><p>&nbsp;</p>', 1, 8, 5, '2024-04-03'),
(5, '<p>epic</p>', 1, 8, 7, '2024-04-03'),
(6, '<p>Sud does not know this song&nbsp;</p><p>&nbsp;</p>', 1, 8, 7, '2024-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(55) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `datepost` date NOT NULL,
  `author` int(55) NOT NULL,
  `category` int(33) NOT NULL,
  `viewed` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `content`, `datepost`, `author`, `category`, `viewed`) VALUES
(1, 'The Beatles', '<p>the beatles are just some british guys who got really popular in the 60s bc people yearn for music</p>', '0000-00-00', 8, 4, 1),
(2, 'Mook', 'Mook is an indie / kidna idk weird band that surfaced in the late 2000s and early 2010s, lead singer being actor Paul Dano who is known for his role as the \'riddler\' in the 2022 film the Batman.', '0000-00-00', 7, 8, 1),
(3, 'queen', '                            jwkjvbdkHCBSDCBSDBCDSHCBHJCBDJCBNDSJKBCKDSJ            1                                ', '0000-00-00', 6, 6, 1),
(4, 'radiohead', '                    killing myself instantly                ', '0000-00-00', 8, 6, 0),
(5, 'weezer', 'weeeeeeeeeeezer            ', '0000-00-00', 9, 7, 1),
(6, 'NOAH KAHAN', '                                                            <p>I dont really knwo the guys <strong>sOrry </strong>lol needed a country guy to promote here</p>                                                ', '2024-04-01', 10, 9, 0),
(7, 'Part of Your World', '                                        <p>From the Little Mermaid by Syd Kal</p>                                ', '2024-04-03', 8, 7, 1),
(8, 'Help', '<p>Song by beetles</p>', '2024-04-04', 8, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(33) NOT NULL,
  `pwd` varchar(155) NOT NULL,
  `email` varchar(55) NOT NULL,
  `role` varchar(11) NOT NULL,
  `regisdate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `pwd`, `email`, `role`, `regisdate`) VALUES
(6, 'r', '$2y$10$nKlOgWCaY2eqorTVIR6OZOAko0hdr/Y7sjASijWmhtmmPVEzCDN02', 'r@r.r', 'admin', '2024-03-16'),
(7, 't', '$2y$10$KlFIpGpDUUn6lsNvl4pCw.9ptLqK07OnSHtA0wyN2Nd0dBO/g.uzm', 't@t.com', 'basic', '2024-03-17'),
(8, 'w', '$2y$10$ycXXql6DSj.ft986fL/VfuqsO0Jb9RhJ99pPLFbi5U8tlohxZxn6G', 'w@w.com', 'admin', '2024-03-25'),
(9, 'm', '$2y$10$kAHmuldygrTsqQ4d7hJwXOaQ4K99zJdyoJQKLpTNeG7a2H7d6NIu.', 'm@m.m', 'basic', '2024-04-01'),
(10, 'y', '$2y$10$tNJLuCFgjtJQJ3s5XZV68.tq8G3H4nQ01rZoH6/orIBHU6V.gbDMi', 'y@y.y', 'basic', '2024-04-01'),
(11, 'z', '$2y$10$g7qtN8qFn2ukdzkehUUV9OhoHjZxYxOAjpWrUSsgvDKcYMRi.5KVy', 'z@z.z', 'basic', '2024-04-05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auth_id` (`auth_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `author` (`author`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `role_2` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`auth_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`cat_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`author`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
