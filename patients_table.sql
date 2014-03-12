-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2013 at 09:46 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital_schedule`
--

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `patient_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `phone` varchar(20) DEFAULT NULL,
  `sex` varchar(15) DEFAULT NULL,
  `birthday` varchar(30) DEFAULT NULL,
  `register_date` varchar(20) DEFAULT NULL,
  `register_time` varchar(20) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `symptoms` varchar(10000) DEFAULT NULL,
  `lab_results` varchar(10000) DEFAULT NULL,
  `diagnosis` varchar(10000) DEFAULT NULL,
  `doctor` varchar(100) DEFAULT NULL,
  `nurse` varchar(100) DEFAULT NULL,
  `allergies` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `firstname`, `lastname`, `email`, `phone`, `sex`, `birthday`, `register_date`, `register_time`, `address`, `symptoms`, `lab_results`, `diagnosis`, `doctor`, `nurse`, `allergies`, `notes`) VALUES
(1, 'Jack', 'Jack', 'j@j.com', '08783939399', 'Male', '19 October\r\n 1998', '2013-Apr-Tue', '09-25-13', 'Najjera 1', 'High fever', '', 'Malaria', 'James Peters', 'Sandra McKinney', '', '');
