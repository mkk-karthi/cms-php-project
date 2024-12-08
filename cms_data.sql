-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 08, 2024 at 06:16 PM
-- Server version: 8.0.40-0ubuntu0.20.04.1
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cms_resil_force`
--

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `created_at`, `created_by`, `updated_at`, `updated_by`) VALUES
(1, 'What is Lorem Ipsum?', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.', '/uploads/posts/1733661358-wallpaper.jpg', '2024-12-08 12:35:58', 1, '2024-12-08 12:36:26', 1),
(2, 'Why do we use it?', 'It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', '/uploads/posts/1733661420-wallpaper.jpg', '2024-12-08 12:37:00', 1, NULL, NULL);

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `subscribe`, `message`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$VVJp8xtPKGy.ljLQTle3NetoCUhcwf1xHMXsmit0qFuHC7JvrlCdq', 1, 'test', 1, 1, '2024-12-06 12:06:15', '2024-12-08 12:07:22'),
(2, 'test', 'test@gmail.com', '$2y$10$rLGsWOjjf/tnGjIbsL1C5OKcI5oSm/MINUrMLJ8JpJQ8u24qE1s0S', 1, 'test', 2, 1, '2024-12-08 12:37:31', '2024-12-08 12:39:59'),
(3, 'test1', 'test1@gmail.com', '$2y$10$rpDVFFBEAXpPps9Arba0CeR0u.lG.CeXfdIUC4P8b3Zu.trRCRhB.', 1, 'test1', 2, 3, '2024-12-08 12:38:02', NULL);

--
-- Dumping data for table `widget`
--

INSERT INTO `widget` (`id`, `widget_type`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-08 12:27:28', NULL),
(2, 1, '2024-12-08 12:27:34', NULL),
(3, 1, '2024-12-08 12:27:38', NULL),
(4, 2, '2024-12-08 12:30:53', NULL),
(5, 2, '2024-12-08 12:31:19', NULL),
(6, 2, '2024-12-08 12:31:41', NULL),
(7, 3, '2024-12-08 12:33:28', NULL);

--
-- Dumping data for table `widget_details`
--

INSERT INTO `widget_details` (`widget_details_id`, `widget_key`, `widget_value`, `widget_id`) VALUES
(1, 'image', '/uploads/widgets/1733660848-2400.jpg', 1),
(2, 'image', '/uploads/widgets/1733660854-blue-technology.jpg', 2),
(3, 'image', '/uploads/widgets/1733660858-technology-6701504_1280.jpg', 3),
(4, 'name', 'John', 4),
(5, 'description', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quod eos id officiis hic tenetur quae quaerat ad velit ab hic tenetur. ', 4),
(6, 'image', '/uploads/widgets/1733661053-avatar.png', 4),
(7, 'name', 'Lisa Cudrow', 5),
(8, 'description', 'Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid commodi. ', 5),
(9, 'image', '/uploads/widgets/1733661079-avatar.png', 5),
(10, 'name', 'karthik', 6),
(11, 'description', 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti. ', 6),
(12, 'image', '/uploads/widgets/1733661101-avatar.png', 6),
(13, 'image', '/uploads/widgets/1733661208-about-img.jpg', 7),
(14, 'content', 'We are a fast-growing company, but we have never lost sight of our core values. We believe in collaboration, innovation, and customer satisfaction. We are always looking for new ways to improve our products and services.', 7);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
