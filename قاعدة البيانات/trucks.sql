-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 20, 2023 at 02:39 AM
-- Server version: 5.7.17-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trucks`
--

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL COMMENT 'المفتاح الاساسي',
  `name` varchar(100) NOT NULL COMMENT 'الاسم',
  `extra` varchar(100) NOT NULL COMMENT 'التفاصيل'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `extra`) VALUES
(1, 'untake 1', 'untake 1'),
(2, 'untake 2', 'untake 2');

-- --------------------------------------------------------

--
-- Table structure for table `trace`
--

CREATE TABLE `trace` (
  `id` int(11) NOT NULL COMMENT 'المعرف الاساسي',
  `vehicle` int(11) NOT NULL COMMENT 'المركبة',
  `cur_loc` int(11) NOT NULL COMMENT 'الموقع الحالي',
  `user` int(11) NOT NULL COMMENT 'المشرف',
  `state` enum('دخول للشحن','الشحن','الدخول للتفريق','المغادرة') NOT NULL COMMENT 'حالة العربة',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ الإضافة'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trace`
--

INSERT INTO `trace` (`id`, `vehicle`, `cur_loc`, `user`, `state`, `created`) VALUES
(1, 3, 1, 5, 'الدخول للتفريق', '2023-06-14 19:11:22'),
(2, 3, 2, 5, 'المغادرة', '2023-06-14 19:32:03'),
(3, 2, 1, 5, 'الدخول للتفريق', '2023-06-14 19:47:19'),
(4, 2, 0, 5, 'الدخول للتفريق', '2023-06-14 19:48:53'),
(5, 2, 1, 3, 'المغادرة', '2023-06-14 19:48:56'),
(6, 2, 0, 4, 'دخول للشحن', '2023-06-15 11:03:17'),
(7, 2, 0, 4, 'الشحن', '2023-06-15 11:03:20'),
(8, 2, 0, 4, 'الشحن', '2023-06-15 11:03:20'),
(9, 2, 1, 5, 'الدخول للتفريق', '2023-06-14 19:47:19'),
(10, 3, 1, 5, 'الدخول للتفريق', '2023-06-19 11:11:22');

--
-- Triggers `trace`
--
DELIMITER $$
CREATE TRIGGER `update_v` AFTER INSERT ON `trace` FOR EACH ROW UPDATE
    `vehicles`
SET
    `state` = new.state
WHERE
    `vehicles`.`id` = new.vehicle
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `tracev`
-- (See below for the actual view)
--
CREATE TABLE `tracev` (
`id` int(11)
,`v_id` int(11)
,`vehicle` varchar(100)
,`l_id` int(11)
,`cur_loc` varchar(100)
,`u_id` int(11)
,`user` varchar(100)
,`state` enum('دخول للشحن','الشحن','الدخول للتفريق','المغادرة')
,`created` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL COMMENT 'معرف أو المفتاح الرئيسي',
  `name` varchar(100) NOT NULL COMMENT 'الاسم رباعي',
  `password` varchar(20) NOT NULL COMMENT 'كلمة المرور',
  `phone` varchar(100) NOT NULL COMMENT 'الهاتف',
  `address` varchar(200) NOT NULL COMMENT 'العنوان أو السكن',
  `location` int(11) NOT NULL COMMENT 'موقع العمل',
  `permit` enum('admin','inner','outter','driver') NOT NULL COMMENT 'نوع الحساب - مدير نظام / سائق / مشرف ادخال / مشرف اخراج',
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'تاريخ إنشاء الحساب'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `phone`, `address`, `location`, `permit`, `created`) VALUES
(2, 'مشرف', '199', '0199199199', 'البحر الأحمر', 1, 'admin', '2023-06-08 19:26:37'),
(3, 'خالد أحمد', '444', '091877775', 'بورتسودان', 2, 'driver', '2023-06-09 19:55:09'),
(4, 'أمين', '133', '0133133133', 'البحر الأحمر', 0, 'inner', '2023-06-10 21:59:18'),
(5, 'احمد', '122', '0122122122', 'الخرطوم', 0, 'outter', '2023-06-10 22:23:17'),
(6, 'طاهر ابراهيم', '123', '0911128700', '123', 0, 'driver', '2023-06-14 17:52:54');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL COMMENT 'مفتاح أساسي',
  `name` varchar(100) NOT NULL COMMENT 'الاسم',
  `driver` int(11) NOT NULL COMMENT 'السائق  \\ المستخدم',
  `license` varchar(100) NOT NULL COMMENT 'الرخصة',
  `state` varchar(100) NOT NULL COMMENT 'حالة المركبة / محملة أو فارغة'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `name`, `driver`, `license`, `state`) VALUES
(1, 'شاحنة', 3, 'ي ح 102', 'unloaded'),
(2, 'شاحنة 2', 3, '142 ب ح', 'الدخول للتفريق'),
(3, 'شاحنه 3', 6, 'ب ح 120', 'الدخول للتفريق');

-- --------------------------------------------------------

--
-- Stand-in structure for view `vehiclev`
-- (See below for the actual view)
--
CREATE TABLE `vehiclev` (
`id` int(11)
,`name` varchar(100)
,`d_id` int(11)
,`driver` varchar(100)
,`license` varchar(100)
,`state` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `tracev`
--
DROP TABLE IF EXISTS `tracev`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `tracev`  AS  select `trace`.`id` AS `id`,`trace`.`vehicle` AS `v_id`,(select `vehicles`.`name` from `vehicles` where (`vehicles`.`id` = `trace`.`vehicle`)) AS `vehicle`,`trace`.`cur_loc` AS `l_id`,(select `locations`.`name` from `locations` where (`locations`.`id` = `trace`.`cur_loc`)) AS `cur_loc`,`trace`.`user` AS `u_id`,(select `users`.`name` from `users` where (`users`.`id` = `trace`.`user`)) AS `user`,`trace`.`state` AS `state`,`trace`.`created` AS `created` from `trace` where 1 ;

-- --------------------------------------------------------

--
-- Structure for view `vehiclev`
--
DROP TABLE IF EXISTS `vehiclev`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vehiclev`  AS  select `vehicles`.`id` AS `id`,`vehicles`.`name` AS `name`,`vehicles`.`driver` AS `d_id`,(select `users`.`name` from `users` where (`users`.`id` = `vehicles`.`driver`)) AS `driver`,`vehicles`.`license` AS `license`,`vehicles`.`state` AS `state` from `vehicles` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trace`
--
ALTER TABLE `trace`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle` (`vehicle`),
  ADD KEY `user` (`user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `location` (`location`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'المفتاح الاساسي', AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `trace`
--
ALTER TABLE `trace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'المعرف الاساسي', AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'معرف أو المفتاح الرئيسي', AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'مفتاح أساسي', AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
