-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 26, 2024 at 12:50 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinetech_db`
--
CREATE DATABASE IF NOT EXISTS `cinetech_db`;
USE `cinetech_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'Adama', 'adama.diomande@laplateforme.io', '$2y$10$JtX4ituBGQ4JABQY7iLJtu8iaNynMUmRuEw2XxRt/VCJOy7W7k.ja', '2024-11-21 08:57:04'),
(2, 'Mr B', 'mrb@gmail.com', '$2y$10$3zyjyAJiFd/aVkVFBL//WOd6KwT2JK90OpyLxvmfxjAVAL7bsNa8W', '2024-11-21 10:39:58');


-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    item_type VARCHAR(10) NOT NULL,  -- 'movie' or 'tv'
    content TEXT NOT NULL,
    parent_id INT DEFAULT NULL, -- For nested comments/replies
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE -- Self-reference for replies
);


-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `item_id` int NOT NULL,
  `media_type` enum('movie','tv') NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `release_date` date DEFAULT NULL,
  `poster_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `backdrop_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `series`
--

CREATE TABLE `series` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `first_air_date` date DEFAULT NULL,
  `poster_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `backdrop_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD KEY `parent_id` (`parent_id`); -- Add index for the self-referencing key

-- Note: PRIMARY KEY and user_id FK index are already implicitly created or defined in CREATE TABLE

--
-- Indexes for table `favorites`
--
-- Note: PRIMARY KEY and user_id FK index are already implicitly created or defined in CREATE TABLE

--
-- Indexes for table `movies`
--
-- Note: PRIMARY KEY index is already implicitly created or defined in CREATE TABLE

--
-- Indexes for table `series`
--
-- Note: PRIMARY KEY index is already implicitly created or defined in CREATE TABLE

--
-- Indexes for table `users`
--
-- Note: PRIMARY KEY and UNIQUE indexes are already implicitly created or defined in CREATE TABLE


-- --------------------------------------------------------

--
-- AUTO_INCREMENT for dumped tables
--

-- Note: AUTO_INCREMENT is already handled in the CREATE TABLE statements

--
-- AUTO_INCREMENT for table `users` (Set starting value after inserts)
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Constraints for dumped tables
--

-- Note: Constraints are now defined within the CREATE TABLE statements.
-- The separate ALTER TABLE statements for constraints are removed to avoid potential issues
-- if tables didn't exist yet during the ALTER phase in some SQL clients.

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;