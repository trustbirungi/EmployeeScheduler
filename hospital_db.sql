-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 25, 2013 at 09:26 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `scheduler`
--

-- --------------------------------------------------------

--
-- Table structure for table `es_area`
--

CREATE TABLE IF NOT EXISTS `es_area` (
  `a_id` int(11) NOT NULL AUTO_INCREMENT,
  `a_name` varchar(50) NOT NULL,
  `a_description` text,
  PRIMARY KEY (`a_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `es_area`
--

INSERT INTO `es_area` (`a_id`, `a_name`, `a_description`) VALUES
(1, 'General Medicine', 'Doctors treat patients with general illnesses as they arrive in the hospital. \r\nNurses help the doctors with the treatment of the patients.');

-- --------------------------------------------------------

--
-- Table structure for table `es_area_sups`
--

CREATE TABLE IF NOT EXISTS `es_area_sups` (
  `as_id` int(11) NOT NULL AUTO_INCREMENT,
  `as_a_id` int(11) NOT NULL,
  `as_u_id` int(11) NOT NULL,
  PRIMARY KEY (`as_id`),
  KEY `area` (`as_a_id`),
  KEY `user` (`as_u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `es_area_sups`
--

INSERT INTO `es_area_sups` (`as_id`, `as_a_id`, `as_u_id`) VALUES
(2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `es_position`
--

CREATE TABLE IF NOT EXISTS `es_position` (
  `p_id` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(20) NOT NULL,
  `p_description` text,
  `p_a_id` int(11) NOT NULL,
  PRIMARY KEY (`p_id`),
  KEY `area` (`p_a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `es_position`
--


-- --------------------------------------------------------

--
-- Table structure for table `es_position_assignment`
--

CREATE TABLE IF NOT EXISTS `es_position_assignment` (
  `pa_id` int(11) NOT NULL AUTO_INCREMENT,
  `pa_u_id` int(11) NOT NULL,
  `pa_p_id` int(11) NOT NULL,
  `pa_s_id` int(11) NOT NULL,
  `pa_us_id` int(11) NOT NULL,
  `pa_hour` int(11) NOT NULL,
  `pa_note` text,
  PRIMARY KEY (`pa_id`),
  KEY `user` (`pa_u_id`),
  KEY `position` (`pa_p_id`),
  KEY `schedule` (`pa_s_id`),
  KEY `user_schedule` (`pa_us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `es_position_assignment`
--


-- --------------------------------------------------------

--
-- Table structure for table `es_schedule`
--

CREATE TABLE IF NOT EXISTS `es_schedule` (
  `s_id` int(11) NOT NULL AUTO_INCREMENT,
  `s_u_id` int(11) DEFAULT NULL,
  `s_p_id` int(11) DEFAULT NULL,
  `s_group` varchar(30) DEFAULT NULL,
  `s_starttime` int(11) NOT NULL,
  `s_hours` varchar(96) NOT NULL,
  `s_repeat` int(11) NOT NULL,
  `s_exptime` int(11) NOT NULL,
  `s_notes` text,
  `s_lastupdated` bigint(14) DEFAULT NULL,
  PRIMARY KEY (`s_id`),
  KEY `user` (`s_u_id`),
  KEY `position` (`s_p_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `es_schedule`
--

INSERT INTO `es_schedule` (`s_id`, `s_u_id`, `s_p_id`, `s_group`, `s_starttime`, `s_hours`, `s_repeat`, `s_exptime`, `s_notes`, `s_lastupdated`) VALUES
(1, 2, 0, 'Patience Birungi-04/21/2013', 1366495200, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL),
(2, 2, 0, 'Patience Birungi-04/21/2013', 1366581600, '000000000000000000000000333333333333333333333333333333333333333333333333333300000000000000000000', 604800, 1370037600, '', NULL),
(3, 2, 0, 'Patience Birungi-04/21/2013', 1366668000, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL),
(4, 2, 0, 'Patience Birungi-04/21/2013', 1366754400, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL),
(5, 2, 0, 'Patience Birungi-04/21/2013', 1366840800, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL),
(6, 2, 0, 'Patience Birungi-04/21/2013', 1366927200, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL),
(7, 2, 0, 'Patience Birungi-04/21/2013', 1367013600, '000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 604800, 1370037600, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `es_schedule_comment`
--

CREATE TABLE IF NOT EXISTS `es_schedule_comment` (
  `sc_id` int(11) NOT NULL AUTO_INCREMENT,
  `sc_s_id` int(11) NOT NULL,
  `sc_hour` int(11) NOT NULL,
  `sc_course` varchar(30) DEFAULT NULL,
  `sc_building` varchar(30) DEFAULT NULL,
  `sc_comment` text,
  PRIMARY KEY (`sc_id`),
  KEY `schedule` (`sc_s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `es_schedule_comment`
--


-- --------------------------------------------------------

--
-- Table structure for table `es_settings`
--

CREATE TABLE IF NOT EXISTS `es_settings` (
  `se_version` varchar(30) DEFAULT NULL,
  `se_SITE_URL` varchar(255) DEFAULT NULL,
  `se_SESSION_COOKIE_TIMEOUT` int(11) DEFAULT NULL,
  `se_CHARACTER_SET` varchar(30) DEFAULT NULL,
  `se_SITE_ADMIN_EMAIL` varchar(255) DEFAULT NULL,
  `se_COMPANY_URL` varchar(255) DEFAULT NULL,
  `se_COMPANY_NAME` varchar(255) DEFAULT NULL,
  `se_START_HOUR` int(11) DEFAULT NULL,
  `se_END_HOUR` int(11) DEFAULT NULL,
  `se_DEFAULT_TIME_BLOCKS` int(11) DEFAULT NULL,
  `se_ES_SHOW_STATS` char(1) DEFAULT NULL,
  `se_ES_FULL_MAIL_TO` char(1) DEFAULT NULL,
  `se_PRIORITY_0` varchar(10) DEFAULT NULL,
  `se_PRIORITY_1` varchar(10) DEFAULT NULL,
  `se_PRIORITY_2` varchar(10) DEFAULT NULL,
  `se_PRIORITY_3` varchar(10) DEFAULT NULL,
  `se_PRIORITY_4` varchar(10) DEFAULT NULL,
  `se_PRIORITY_5` varchar(10) DEFAULT NULL,
  `se_PRIORITY_6` varchar(10) DEFAULT NULL,
  `se_PRIORITY_7` varchar(10) DEFAULT NULL,
  `se_PRIORITY_8` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `es_settings`
--

INSERT INTO `es_settings` (`se_version`, `se_SITE_URL`, `se_SESSION_COOKIE_TIMEOUT`, `se_CHARACTER_SET`, `se_SITE_ADMIN_EMAIL`, `se_COMPANY_URL`, `se_COMPANY_NAME`, `se_START_HOUR`, `se_END_HOUR`, `se_DEFAULT_TIME_BLOCKS`, `se_ES_SHOW_STATS`, `se_ES_FULL_MAIL_TO`, `se_PRIORITY_0`, `se_PRIORITY_1`, `se_PRIORITY_2`, `se_PRIORITY_3`, `se_PRIORITY_4`, `se_PRIORITY_5`, `se_PRIORITY_6`, `se_PRIORITY_7`, `se_PRIORITY_8`) VALUES
('2.1', 'http://www.yourdomain.com/', 1800, 'UTF-8', 'you@yourdomain.com', 'http://empscheduler.sourceforge.net', 'Employee Scheduler Home Page', 0, 24, 24, '0', '1', '#BBBBBB', '#AAAAff', '#CCCCFF', '#EEEEFF', '#DDDDFF', '#EEEEFF', '#999999', '#FFFFFF', '#CC9988'),
('2.1', 'http://www.yourdomain.com/', 1800, 'UTF-8', 'you@yourdomain.com', 'http://empscheduler.sourceforge.net', 'Employee Scheduler Home Page', 0, 24, 24, '0', '1', '#BBBBBB', '#AAAAff', '#CCCCFF', '#EEEEFF', '#DDDDFF', '#EEEEFF', '#999999', '#FFFFFF', '#CC9988');

-- --------------------------------------------------------

--
-- Table structure for table `es_user`
--

CREATE TABLE IF NOT EXISTS `es_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_netid` varchar(30) NOT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_type` enum('LDAP Admin','Admin','LDAP Supervisor','Supervisor','LDAP Employee','Employee') DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_major` varchar(50) DEFAULT NULL,
  `user_workphone` varchar(15) DEFAULT NULL,
  `user_homephone` varchar(15) DEFAULT NULL,
  `user_location` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_min` int(11) DEFAULT NULL,
  `user_max` int(11) DEFAULT NULL,
  `user_hours` int(11) DEFAULT NULL,
  `user_picture` text,
  `user_notes` text,
  `user_supnotes` text,
  `user_color` varchar(10) DEFAULT NULL,
  `user_language` varchar(30) DEFAULT NULL,
  `shift_type` varchar(10) NOT NULL,
  `days_off` varchar(10) NOT NULL,
  `temporary_shift_type` varchar(10) NOT NULL,
  `temporary_days_off` varchar(10) NOT NULL,
  `approved` varchar(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `es_user`
--

INSERT INTO `es_user` (`user_id`, `user_netid`, `user_password`, `user_type`, `user_name`, `user_major`, `user_workphone`, `user_homephone`, `user_location`, `user_email`, `user_min`, `user_max`, `user_hours`, `user_picture`, `user_notes`, `user_supnotes`, `user_color`, `user_language`, `shift_type`, `days_off`, `temporary_shift_type`, `temporary_days_off`, `approved`) VALUES
(1, 'racheal', '$1$zb2.AD4.$Burk3YKqnW5UBeITY6hFp.', 'Admin', 'Racheal Karungi', 'Doctor', '0723123456', '0789123654', 'Bukoto', 'rkarungi@gmail.com', 12, 12, 12, NULL, NULL, NULL, '#ee1928', 'english', 'day', '256', '', '', ''),
(2, 'patience', '$1$6/0.Vv3.$212XjIyyaUerdSEy/fhcC0', 'Employee', 'Patience Birungi', 'Doctor', '078900000', '078399999', 'Kampala', 'princesacent@gmail.com', 12, 12, 12, NULL, '', '', '#ee1928', 'english', 'day', '235', 'night', '012', 'false'),
(6, 'allan', '$1$Np2.So1.$C41uByIr6E15pKZWw9Zdz1', 'Employee', 'Allan Muhabwe', 'Nurse', '0743213321', '0765909808', 'Wandegeya', 'allan@gmail.com', 12, 12, 12, NULL, '', '', '#2c2b09', 'english', 'night', '012', 'night', '012', 'true'),
(14, 'ken', '$1$av5.bk2.$UqxVxz47vKCLDsVtC2koo.', 'Employee', 'Kenneth Mukasa', 'Doctor', '0789234567', '0772345678', 'Makerere', 'ken@gmail.com', 12, 12, 12, NULL, '', '', '#FFFFFF', 'English', 'day', '104', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `es_user_sups`
--

CREATE TABLE IF NOT EXISTS `es_user_sups` (
  `us_id` int(11) NOT NULL AUTO_INCREMENT,
  `us_sup_id` int(11) NOT NULL,
  `us_emp_id` int(11) NOT NULL,
  PRIMARY KEY (`us_id`),
  KEY `sup` (`us_sup_id`),
  KEY `emp` (`us_emp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `es_user_sups`
--

INSERT INTO `es_user_sups` (`us_id`, `us_sup_id`, `us_emp_id`) VALUES
(1, 1, 2),
(2, 1, 6),
(21, 1, 14);

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
  `doctor` mediumtext,
  `nurse` mediumtext,
  `allergies` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`patient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`patient_id`, `firstname`, `lastname`, `email`, `phone`, `sex`, `birthday`, `register_date`, `register_time`, `address`, `symptoms`, `lab_results`, `diagnosis`, `doctor`, `nurse`, `allergies`, `notes`) VALUES
(1, 'Jack', 'Jack', 'j@j.com', '08783939399', 'Male', '19\r\nOctober1998', '', '', 'Najjera 1', 'High fever', '', 'Malaria', 'James Peters', 'Sandra McKinney', 'Allergic to morphine.', ''),
(2, 'test', 'test', 'test@test.com', '08934568930', 'Male', '18\r\nJuly\r\n1995', '2013-Jun-Mon', '09-01-20', 'Ntinda', 'Acute fever, nose bleeding, severe sweating.', 'Streptococcus detected', 'Bovine fever.', 'Patience Birungi', 'Allan Muhabwe', 'Allergic to morphine.', 'Patient is disoriented.'),
(3, 'test', 'test', 'test@test.com', '08934568930', 'Male', '18\r\nJuly\r\n1995', '2013-Jun-Mon', '09-03-24', 'Ntinda', 'Acute fever, nose bleeding, severe sweating.', 'Streptococcus detected', 'Bovine fever.', 'Patience Birungi', 'Allan Muhabwe', 'Allergic to morphine.', 'Patient is disoriented.'),
(4, 'Jason', 'Mason', 'jason@gmail.com', '0893839494', 'Male', '16\r\nNovember\r\n1970', '2013-Jun-3', '10-10-18', 'Bukoto', 'Acute fever, headache, joint pain.', 'Trypanasoma detected.', 'Sleeping sickness.', 'Patience Birungi', 'Penny Musoke', 'Allergic to epinephrine.', 'Patient is disoriented.');

-- --------------------------------------------------------

--
-- Table structure for table `vacation_table`
--

CREATE TABLE IF NOT EXISTS `vacation_table` (
  `user_id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `start_date` varchar(20) NOT NULL,
  `end_date` varchar(20) NOT NULL,
  `supervisor` varchar(100) NOT NULL,
  `approved` varchar(7) NOT NULL,
  `declined` varchar(7) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `vacation_table`
--

