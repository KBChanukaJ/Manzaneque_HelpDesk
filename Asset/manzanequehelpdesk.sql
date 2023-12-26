-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 24, 2023 at 03:09 PM
-- Server version: 8.0.32
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `manzanequehelpdesk`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `UpdateProblemStatus`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateProblemStatus` (IN `p_problemNumber` INT, IN `p_resolutionDetails` TEXT)  BEGIN
    
    UPDATE Problems
    SET ResolutionDetails = p_resolutionDetails, TimeResolved = CURRENT_TIMESTAMP, Status = 'Closed'
    WHERE ProblemNumber = p_problemNumber;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `equipment`
--

DROP TABLE IF EXISTS `equipment`;
CREATE TABLE IF NOT EXISTS `equipment` (
  `EquipmentID` int NOT NULL,
  `EquipmentType` varchar(255) DEFAULT NULL,
  `EquipmentMake` varchar(255) DEFAULT NULL,
  `SerialNumber` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`EquipmentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `equipment`
--

INSERT INTO `equipment` (`EquipmentID`, `EquipmentType`, `EquipmentMake`, `SerialNumber`) VALUES
(1001, 'Hard Disk', 'SunDisk', 'SN-1001123456789'),
(1002, 'Desktop', 'Dell', 'SN-1002334465413'),
(1003, 'Desktop', 'ViewSonic', 'SN-1003654328765'),
(1004, 'RAM', 'Rayzon', 'SN-1004654387651');

-- --------------------------------------------------------

--
-- Table structure for table `helpdeskoperators`
--

DROP TABLE IF EXISTS `helpdeskoperators`;
CREATE TABLE IF NOT EXISTS `helpdeskoperators` (
  `OperatorID` int NOT NULL,
  `OperatorName` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Role` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`OperatorID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `helpdeskoperators`
--

INSERT INTO `helpdeskoperators` (`OperatorID`, `OperatorName`, `Password`, `Role`) VALUES
(12700, 'K.B.Chanuka', '$2y$10$ENdsdPE.ujepd8TKEnelD.YFCV5IHXJ2p0GmXarBSESof9wiHtgHm', 'HelpDeskOperator'),
(12733, 'Gunawardhana', '$2y$10$xaZ.BbtdiYxvyeWeH4c1TO92E.o42C8b2QPPtXulGWXphoiCQKlaK', 'HelpDeskOperator'),
(20001, 'Dr Subash Jayashuriya', '$2y$10$HeRUQc/Al1CZ4f.Z3vpDhuk0bUlGV/4yK/97HGG.BIJ1nS0dY9p..', 'Specialist'),
(20002, 'Dr Nuwan Laksiri', '$2y$10$VtH9Rpx7wNinTvk8Z3c0pu31bYjV3UHV9mgMss28YiwCv9aDfS7cm', 'Specialist');

-- --------------------------------------------------------

--
-- Table structure for table `personnel`
--

DROP TABLE IF EXISTS `personnel`;
CREATE TABLE IF NOT EXISTS `personnel` (
  `PersonnelID` int NOT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `JobTitle` varchar(255) DEFAULT NULL,
  `Department` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`PersonnelID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `personnel`
--

INSERT INTO `personnel` (`PersonnelID`, `Name`, `JobTitle`, `Department`) VALUES
(10001, 'Buddhima Manahari', 'Account Assistent', 'Acconting Department'),
(10002, 'Madubashana Mahindarathna', 'Software Engineer', 'IT');

-- --------------------------------------------------------

--
-- Table structure for table `problems`
--

DROP TABLE IF EXISTS `problems`;
CREATE TABLE IF NOT EXISTS `problems` (
  `ProblemNumber` int NOT NULL,
  `CallerID` int DEFAULT NULL,
  `OperatorID` int DEFAULT NULL,
  `ProblemTypeID` int DEFAULT NULL,
  `ProblemDescription` text,
  `TimeReported` timestamp NULL DEFAULT NULL,
  `TimeResolved` timestamp NULL DEFAULT NULL,
  `ResolutionDetails` text,
  `SpecialistID` int DEFAULT NULL,
  `EquipmentID` int DEFAULT NULL,
  `SoftwareID` int DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`ProblemNumber`),
  KEY `CallerID` (`CallerID`),
  KEY `OperatorID` (`OperatorID`),
  KEY `ProblemTypeID` (`ProblemTypeID`),
  KEY `SpecialistID` (`SpecialistID`),
  KEY `EquipmentID` (`EquipmentID`),
  KEY `SoftwareID` (`SoftwareID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `problems`
--

INSERT INTO `problems` (`ProblemNumber`, `CallerID`, `OperatorID`, `ProblemTypeID`, `ProblemDescription`, `TimeReported`, `TimeResolved`, `ResolutionDetails`, `SpecialistID`, `EquipmentID`, `SoftwareID`, `Status`) VALUES
(123456789, 10002, 12733, 101, 'Slow that Disk', '2023-12-24 04:24:41', '2023-12-24 04:42:06', 'Solve that Replace Hard disk', 20002, 1001, NULL, 'Closed'),
(712233445, 10001, 12700, 101, 'Disk not working', '2023-12-24 05:35:02', '2023-12-24 05:36:37', 'Solved!!', 20001, 1001, NULL, 'Closed'),
(779134741, 10001, 12733, 101, 'Dis is not working ', '2023-12-24 02:26:56', '2023-12-24 04:13:00', 'Solved!', 20002, 1001, NULL, 'Closed');

-- --------------------------------------------------------

--
-- Table structure for table `problemtypes`
--

DROP TABLE IF EXISTS `problemtypes`;
CREATE TABLE IF NOT EXISTS `problemtypes` (
  `ProblemTypeID` int NOT NULL,
  `ProblemTypeName` varchar(255) DEFAULT NULL,
  `ProblemTypeDescription` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ProblemTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `problemtypes`
--

INSERT INTO `problemtypes` (`ProblemTypeID`, `ProblemTypeName`, `ProblemTypeDescription`) VALUES
(101, 'Disk Failure', 'Hard Disk is not working properly');

-- --------------------------------------------------------

--
-- Table structure for table `software`
--

DROP TABLE IF EXISTS `software`;
CREATE TABLE IF NOT EXISTS `software` (
  `SoftwareID` int NOT NULL,
  `SoftwareName` varchar(255) DEFAULT NULL,
  `LicenseStatus` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`SoftwareID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `software`
--

INSERT INTO `software` (`SoftwareID`, `SoftwareName`, `LicenseStatus`) VALUES
(2001, 'Inventory Management System', 1);

-- --------------------------------------------------------

--
-- Table structure for table `specialists`
--

DROP TABLE IF EXISTS `specialists`;
CREATE TABLE IF NOT EXISTS `specialists` (
  `SpecialistID` int NOT NULL,
  `SpecialistName` varchar(255) DEFAULT NULL,
  `Specialty` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`SpecialistID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `specialists`
--

INSERT INTO `specialists` (`SpecialistID`, `SpecialistName`, `Specialty`) VALUES
(20001, 'Dr Subash Jayashuriya', 'Computer Software'),
(20002, 'Dr Nuwan Laksiri', 'Database');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `problems`
--
ALTER TABLE `problems`
  ADD CONSTRAINT `problems_ibfk_1` FOREIGN KEY (`CallerID`) REFERENCES `personnel` (`PersonnelID`),
  ADD CONSTRAINT `problems_ibfk_2` FOREIGN KEY (`OperatorID`) REFERENCES `helpdeskoperators` (`OperatorID`),
  ADD CONSTRAINT `problems_ibfk_3` FOREIGN KEY (`ProblemTypeID`) REFERENCES `problemtypes` (`ProblemTypeID`),
  ADD CONSTRAINT `problems_ibfk_4` FOREIGN KEY (`SpecialistID`) REFERENCES `specialists` (`SpecialistID`),
  ADD CONSTRAINT `problems_ibfk_5` FOREIGN KEY (`EquipmentID`) REFERENCES `equipment` (`EquipmentID`),
  ADD CONSTRAINT `problems_ibfk_6` FOREIGN KEY (`SoftwareID`) REFERENCES `software` (`SoftwareID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
