-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 14, 2024 at 07:42 PM
-- Server version: 8.0.31
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor_appointment_management_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `patient_id` int DEFAULT NULL,
  `doctor_id` int DEFAULT NULL,
  `appointment_date_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patient_id` (`patient_id`),
  KEY `fk_doctor_id` (`doctor_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `appointment_date_time`) VALUES
(6, 10, 1, '2024-10-18 01:03:00');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `specialty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`(250))
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `specialty`) VALUES
(1, 'Dr. John Smith', 'Cardiologist'),
(2, 'Dr. Emily Johnson', 'Dermatologist'),
(3, 'Dr. Michael Williams', 'Neurologist'),
(4, 'Dr. Sarah Davis', 'Pediatrician'),
(5, 'Dr. David Brown', 'Orthopedic Surgeon'),
(6, 'Dr. Jessica Taylor', 'Obstetrician'),
(7, 'Dr. Robert Miller', 'General Practitioner'),
(8, 'Dr. Mary Wilson', 'Endocrinologist'),
(9, 'Dr. Thomas Moore', 'Gastroenterologist'),
(10, 'Dr. Linda Anderson', 'Psychiatrist');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

DROP TABLE IF EXISTS `patients`;
CREATE TABLE IF NOT EXISTS `patients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `email_2` (`email`),
  KEY `name` (`name`(250))
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `name`, `email`, `phone`) VALUES
(1, 'John Doe', 'john.doe@gmail.com', '5551234567'),
(2, 'Jane Smith', 'jane.smith@gmail.com', '5552345678'),
(3, 'Michael Johnson', 'michael.johnson@yahoo.com', '5553456789'),
(4, 'Emily Davis', 'emily.davis@gmail.com', '5554567890'),
(5, 'Robert Brown', 'robert.brown@yahoo.com', '5555678901'),
(6, 'Linda Miller', 'linda.miller@yahoo.com', '5556789012'),
(7, 'James Wilson', 'james.wilson@gmail.com', '5557890123'),
(8, 'Patricia Taylor', 'patricia.taylor@gmail.com', '5558901234'),
(9, 'Charles Anderson', 'charles.anderson@gmail.com', '5559012345'),
(10, 'Jessica Thomas', 'jessica.thomas@gmail.com', '5550123456');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
