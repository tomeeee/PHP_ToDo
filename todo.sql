-- phpMyAdmin SQL Dump
-- version 4.5.0.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 09, 2016 at 08:28 PM
-- Server version: 10.0.17-MariaDB
-- PHP Version: 5.6.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todo`
--

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id` int(11) NOT NULL,
  `email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `lozinka` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `ime` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `prezime` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `datum_registracije` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `zadnji_login` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('neaktivan','aktivan') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id`, `email`, `lozinka`, `ime`, `prezime`, `datum_registracije`, `zadnji_login`, `status`) VALUES
(9, 'aaa@aaa', '$2y$10$/SvFZDtx0GU83whrY9/w8u1.GS3bn4mdR8u2GCTWcPVFfODfR8lyS', 'aaa', 'aaa', '2016-08-02 19:24:08', '2016-08-09 18:24:30', 'aktivan'),
(10, 'bbb@bbb', '$2y$10$Y71TwABLGoTSxoil/5ihD.XUODxxMwBew5helvrQuz4SpcrP8S0nC', 'bbb', 'bbb', '2016-08-06 00:37:13', '2016-08-07 16:41:33', 'aktivan'),
(19, 'ccc@ccc', '$2y$10$yayYUYYq4s/5KklOI1IrDO0H5cq.8FFGcadobCJaNF6Oj5sXtFqOG', 'ccc', 'ccc', '2016-08-07 16:32:15', '0000-00-00 00:00:00', 'neaktivan');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik_to-do`
--

CREATE TABLE `korisnik_to-do` (
  `id_korisnik` int(11) NOT NULL,
  `id_to-do` int(11) NOT NULL,
  `datum_kreiranja` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `korisnik_to-do`
--

INSERT INTO `korisnik_to-do` (`id_korisnik`, `id_to-do`, `datum_kreiranja`) VALUES
(9, 20, '2016-08-07 14:08:18'),
(10, 22, '2016-08-07 14:49:08'),
(9, 23, '2016-08-07 15:12:19'),
(10, 24, '2016-08-07 16:39:14'),
(10, 25, '2016-08-07 16:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `naziv` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `prioritet` enum('low','normal','high') COLLATE utf8_unicode_ci NOT NULL,
  `rok` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `naziv`, `prioritet`, `rok`) VALUES
(92, 'a', 'low', '2016-08-11 22:00:00'),
(93, 'a', 'low', '2016-08-13 22:00:00'),
(94, 'a', 'normal', '2016-08-12 22:00:00'),
(95, 'a', 'low', '2016-08-11 22:00:00'),
(96, 'a', 'normal', '2016-08-11 22:00:00'),
(97, 'a', 'normal', '2016-08-16 22:00:00'),
(104, 'a', 'low', '2016-08-11 22:00:00'),
(105, 'a', 'low', '2016-08-11 22:00:00'),
(106, 'a', 'normal', '2016-08-13 22:00:00'),
(109, '1', 'high', '2016-07-31 22:00:00'),
(110, 'a', 'low', '2016-08-11 22:00:00'),
(111, 'a', 'low', '2016-08-11 22:00:00'),
(112, 'a', 'normal', '2016-08-11 22:00:00'),
(113, 'a', 'high', '2016-08-11 22:00:00'),
(114, 'a', 'low', '2016-08-11 22:00:00'),
(115, 'fda', 'high', '2016-08-11 22:00:00'),
(116, 'fda', 'normal', '2016-08-15 22:00:00'),
(117, 'dafafd', 'high', '2016-08-13 22:00:00'),
(118, 'f', 'low', '2016-08-11 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `to-do`
--

CREATE TABLE `to-do` (
  `id` int(11) NOT NULL,
  `naziv` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `to-do`
--

INSERT INTO `to-do` (`id`, `naziv`) VALUES
(20, 'dsa'),
(22, 'a'),
(23, 'da'),
(24, 'da'),
(25, '12');

-- --------------------------------------------------------

--
-- Table structure for table `to-do_task`
--

CREATE TABLE `to-do_task` (
  `id_to-do` int(11) NOT NULL,
  `id_task` int(11) NOT NULL,
  `status` enum('nije zavrseno','zavrseno','','') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `to-do_task`
--

INSERT INTO `to-do_task` (`id_to-do`, `id_task`, `status`) VALUES
(20, 92, 'zavrseno'),
(20, 93, 'zavrseno'),
(20, 94, 'zavrseno'),
(20, 95, 'nije zavrseno'),
(20, 96, 'nije zavrseno'),
(20, 97, 'zavrseno'),
(22, 104, 'zavrseno'),
(22, 105, 'zavrseno'),
(22, 106, 'zavrseno'),
(20, 109, 'nije zavrseno'),
(23, 110, 'nije zavrseno'),
(23, 111, 'zavrseno'),
(23, 112, 'zavrseno'),
(23, 113, 'zavrseno'),
(23, 114, 'zavrseno'),
(24, 115, 'nije zavrseno'),
(24, 116, 'nije zavrseno'),
(24, 117, 'zavrseno'),
(25, 118, 'zavrseno');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnik_to-do`
--
ALTER TABLE `korisnik_to-do`
  ADD KEY `id_korisnik` (`id_korisnik`,`id_to-do`),
  ADD KEY `id_to-do` (`id_to-do`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to-do`
--
ALTER TABLE `to-do`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `to-do_task`
--
ALTER TABLE `to-do_task`
  ADD KEY `id_to-do` (`id_to-do`,`id_task`),
  ADD KEY `id_task` (`id_task`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT for table `to-do`
--
ALTER TABLE `to-do`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `korisnik_to-do`
--
ALTER TABLE `korisnik_to-do`
  ADD CONSTRAINT `korisnik_to-do_ibfk_1` FOREIGN KEY (`id_to-do`) REFERENCES `to-do` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `korisnik_to-do_ibfk_2` FOREIGN KEY (`id_korisnik`) REFERENCES `korisnik` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `to-do_task`
--
ALTER TABLE `to-do_task`
  ADD CONSTRAINT `to-do_task_ibfk_1` FOREIGN KEY (`id_task`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `to-do_task_ibfk_2` FOREIGN KEY (`id_to-do`) REFERENCES `to-do` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
