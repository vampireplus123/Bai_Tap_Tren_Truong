-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 08:29 PM
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
-- Database: `questionfield`
--

-- --------------------------------------------------------

--
-- Table structure for table `questionfield`
--

CREATE TABLE `questionfield` (
  `ID` int(11) NOT NULL,
  `QuestionName` text NOT NULL,
  `Tag` text NOT NULL,
  `Publisher` text NOT NULL,
  `QuestionDetail` text NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questionfield`
--

INSERT INTO `questionfield` (`ID`, `QuestionName`, `Tag`, `Publisher`, `QuestionDetail`, `UserID`) VALUES
(15, 'Hi', 'All', 'anh', 'a', 1),
(16, 'How can I print in Python', 'All', 'anh', 'a', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `questionfield`
--
ALTER TABLE `questionfield`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserQuestion` (`UserID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `questionfield`
--
ALTER TABLE `questionfield`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `questionfield`
--
ALTER TABLE `questionfield`
  ADD CONSTRAINT `UserQuestion` FOREIGN KEY (`UserID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
