-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 29, 2013 at 07:32 PM
-- Server version: 5.0.51
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `dep_id` int(11) NOT NULL auto_increment,
  `dep_name` varchar(35) NOT NULL,
  `dep_description` text,
  `dep_sups` varchar(35) default NULL,
  PRIMARY KEY  (`dep_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `department`
--


-- --------------------------------------------------------

--
-- Table structure for table `es_settings`
--

CREATE TABLE `es_settings` (
  `se_version` varchar(30) default NULL,
  `se_SITE_URL` varchar(255) default NULL,
  `se_SESSION_COOKIE_TIMEOUT` int(11) default NULL,
  `se_CHARACTER_SET` varchar(30) default NULL,
  `se_SITE_ADMIN_EMAIL` varchar(255) default NULL,
  `se_COMPANY_URL` varchar(255) default NULL,
  `se_COMPANY_NAME` varchar(255) default NULL,
  `se_START_HOUR` int(11) default NULL,
  `se_END_HOUR` int(11) default NULL,
  `se_DEFAULT_TIME_BLOCKS` int(11) default NULL,
  `se_ES_SHOW_STATS` char(1) default NULL,
  `se_ES_FULL_MAIL_TO` char(1) default NULL,
  `se_PRIORITY_0` varchar(10) default NULL,
  `se_PRIORITY_1` varchar(10) default NULL,
  `se_PRIORITY_2` varchar(10) default NULL,
  `se_PRIORITY_3` varchar(10) default NULL,
  `se_PRIORITY_4` varchar(10) default NULL,
  `se_PRIORITY_5` varchar(10) default NULL,
  `se_PRIORITY_6` varchar(10) default NULL,
  `se_PRIORITY_7` varchar(10) default NULL,
  `se_PRIORITY_8` varchar(10) default NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `es_settings`
--


-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `p_id` int(11) NOT NULL auto_increment,
  `fname` varchar(15) NOT NULL,
  `lname` varchar(15) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `age` varchar(3) NOT NULL,
  `plocation` varchar(15) NOT NULL,
  `email` varchar(50) default NULL,
  `contact_no` int(10) default NULL,
  `doctor_name` varchar(35) NOT NULL,
  `nurse_name` varchar(35) default NULL,
  `lab_results` varchar(1000) default NULL,
  `diagnosis` varchar(1000) NOT NULL,
  `prescription` varchar(1000) default NULL,
  `notes` text,
  PRIMARY KEY  (`p_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `patients`
--


-- --------------------------------------------------------

--
-- Table structure for table `position`
--

CREATE TABLE `position` (
  `pos_id` int(11) NOT NULL auto_increment,
  `pos_name` varchar(20) NOT NULL,
  `pos_description` text,
  `pos_a_id` int(11) NOT NULL,
  PRIMARY KEY  (`pos_id`),
  KEY `area` (`pos_a_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `position`
--


-- --------------------------------------------------------

--
-- Table structure for table `position_assignment`
--

CREATE TABLE `position_assignment` (
  `posa_id` int(11) NOT NULL auto_increment,
  `posa_u_id` int(11) NOT NULL,
  `posa_p_id` int(11) NOT NULL,
  `posa_s_id` int(11) NOT NULL,
  `posa_us_id` int(11) NOT NULL,
  `pa_hour` int(11) NOT NULL,
  `posa_note` text,
  PRIMARY KEY  (`posa_id`),
  KEY `user` (`posa_u_id`),
  KEY `position` (`posa_p_id`),
  KEY `schedule` (`posa_s_id`),
  KEY `user_schedule` (`posa_us_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `position_assignment`
--


-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE `schedule` (
  `s_id` int(11) NOT NULL,
  `s_emp_id` int(11) default NULL,
  `s_pos_id` int(11) default NULL,
  `s_group` varchar(30) default NULL,
  `s_starttime` int(11) NOT NULL,
  `s_hours` varchar(24) NOT NULL,
  `s_repeat` int(11) NOT NULL,
  `s_exptime` int(11) NOT NULL,
  `s_notes` text NOT NULL,
  `s_lastupdated` bigint(14) default NULL,
  `s_stafftask` varchar(35) NOT NULL,
  PRIMARY KEY  (`s_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `schedule`
--


-- --------------------------------------------------------

--
-- Table structure for table `supervisors`
--

CREATE TABLE `supervisors` (
  `sup_id` int(11) NOT NULL auto_increment,
  `sup_username` varchar(35) NOT NULL,
  `sup_pswd` varchar(15) NOT NULL,
  `sup_email` varchar(50) default NULL,
  `sup_dep` varchar(35) NOT NULL,
  `sup_location` varchar(15) NOT NULL,
  `sup_name` varchar(35) NOT NULL,
  `sup_workphoneno` int(10) default NULL,
  `sup_homephoneno` int(10) default NULL,
  `sup_gender` varchar(6) NOT NULL,
  `sup_picture` text,
  PRIMARY KEY  (`sup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `supervisors`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_netid` varchar(30) NOT NULL,
  `user_username` varchar(35) NOT NULL,
  `user_pswd` varchar(15) NOT NULL,
  `user_type` enum('Doctor','Nurse') NOT NULL default 'Doctor',
  `user_gender` varchar(6) NOT NULL,
  `username` varchar(35) NOT NULL,
  `user_dep` varchar(35) NOT NULL,
  `user_workphone` int(10) default NULL,
  `user_homephone` int(10) default NULL,
  `user_location` varchar(15) NOT NULL,
  `user_email` varchar(50) default NULL,
  `user_maxhrs` int(11) default NULL,
  `user_minhrs` int(11) default NULL,
  `user_hours` int(11) default NULL,
  `user_notes` text,
  `user_sup_notes` text,
  `user_picture` text,
  PRIMARY KEY  (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

