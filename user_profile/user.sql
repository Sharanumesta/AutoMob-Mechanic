-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2023 at 10:15 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

--
-- Database: `user`
CREATE DATABASE `user`;

-- Table structure for table `user`

USE `user`;

CREATE TABLE `user` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` bigint(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(70) NOT NULL,
  `role` text NOT NULL DEFAULT 'user',
  `status` tinyint(1) NOT NULL,
  `otp` mediumint(9) NOT NULL,
  `date_time` datetime DEFAULT NULL,
  `otp_status` tinyint(1) NOT NULL,
  `otp_attempts` int(11) NOT NULL,
  `login_attempts` int(10) DEFAULT NULL,
  `mail_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

