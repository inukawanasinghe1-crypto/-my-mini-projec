-- ====================================================================
-- EduHub - University Study Note & Resource Hub
-- Rajarata University of Sri Lanka | ICT 2209 Web Technologies Mini Project
-- Database: eduhub_db
-- ====================================================================

CREATE DATABASE IF NOT EXISTS `eduhub_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `eduhub_db`;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
DROP TABLE IF EXISTS `resources`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `resources`
-- --------------------------------------------------------
CREATE TABLE `resources` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_code` VARCHAR(20) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `category` VARCHAR(50) NOT NULL DEFAULT 'Lecture Notes',
  `description` TEXT NOT NULL,
  `user_id` INT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_resource_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table structure for table `messages`
-- --------------------------------------------------------
CREATE TABLE `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(150) NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Seed Data for Testing & Evaluation
-- Default sample password for users below is: password123
-- --------------------------------------------------------
INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'kamal_ict', 'kamal@rusl.ac.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW()),
(2, 'nimali_tech', 'nimali@rusl.ac.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NOW());
-- Note: 'password' is password for the default hash above. Users can also register their own accounts via register.php

-- Seed resources
INSERT INTO `resources` (`id`, `subject_code`, `title`, `category`, `description`, `user_id`, `created_at`) VALUES
(1, 'ICT 2209', 'PHP PDO Database Connection and Prepared Statements Cheatsheet', 'Lecture Notes', 'Comprehensive walkthrough on connecting PHP to MySQL using PDO, error handling with try-catch blocks, and parameterized queries to mitigate SQL injection vulnerabilities.', 1, NOW()),
(2, 'ICT 2209', 'Bootstrap 5 Responsive Grid & Flexbox Utility Guide', 'Summary', 'Quick reference for responsive layout breakpoints (sm, md, lg, xl, xxl), containers, gutter spacing, and interactive navbar collapse mechanics.', 1, NOW()),
(3, 'ICT 2201', 'Binary Search Tree (BST) Operations & Traversal in C/C++', 'Lab Sheets', 'Lab exercises with code examples demonstrating in-order, pre-order, post-order traversal, tree deletion, and time complexity derivations.', 2, NOW()),
(4, 'ICT 2203', 'Database Normalization from 1NF to 3NF & BCNF with Worked Examples', 'Lecture Notes', 'Step-by-step breakdown of functional dependencies, partial dependencies, transitive dependencies, and schema decomposition into Boyce-Codd Normal Form.', 2, NOW()),
(5, 'ICT 2209', 'JavaScript DOM Manipulation & Event Handling Exercises', 'Lab Sheets', 'Interactive exercises covering addEventListener, event bubbling, form validation, dynamic DOM node creation, and async fetch requests.', 1, NOW()),
(6, 'ICT 2104', 'Computer Networks: Subnetting & IP Addressing Practice Exam 2024', 'Past Papers', 'Worked solutions for Classless Inter-Domain Routing (CIDR) VLSM subnet calculations, broadcast addresses, and routing table analysis.', 2, NOW());

-- Seed messages
INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Kasun Perera', 'kasun@gmail.com', 'Request for ICT 2202 Past Papers', 'Hello EduHub team, could you please upload the 2023 ICT 2202 Operating Systems past paper discussions? Thank you!', NOW()),
(2, 'Thilini Silva', 'thilini@yahoo.com', 'Great Resource Hub!', 'The PHP PDO notes really helped me understand database connectivity for my web technology mini project.', NOW());
