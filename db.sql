-- phpMyAdmin SQL Dump
-- version 3.3.2deb1ubuntu1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 08, 2017 at 03:32 PM
-- Server version: 5.1.73
-- PHP Version: 5.2.17-0ubuntu0ppa3~lucid

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `data_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(35) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `c` decimal(15,5) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`),
  KEY `password` (`password`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `name`, `c`) VALUES
(1, 'user1', '24c9e15e52afc47c225b757e7bee1f9d', 'User One ', '29999.99000'),
(2, 'user2', '7e58d63b60197ceb55a1c487989a3720', 'User Two', '599999.90000'),
(3, 'user3', '92877af70a45fd6a2ed7fe81e1236b78', 'User Three', '1599999.95000');
