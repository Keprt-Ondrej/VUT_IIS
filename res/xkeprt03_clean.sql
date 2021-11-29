-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2021 at 07:47 PM
-- Server version: 5.7.35
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xkeprt03`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `login` char(8) NOT NULL,
  `question_ID` bigint(20) UNSIGNED NOT NULL,
  `answer` varchar(1024) DEFAULT NULL,
  `correct` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answers`
--
-- --------------------------------------------------------

--
-- Table structure for table `answer_ratings`
--

CREATE TABLE `answer_ratings` (
  `question_ID` bigint(20) UNSIGNED NOT NULL,
  `answer_login` char(8) NOT NULL,
  `rating_login` char(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answer_ratings`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_ID` bigint(20) UNSIGNED NOT NULL,
  `subject_ID` varchar(5) DEFAULT NULL,
  `brief` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `question_ID` bigint(20) UNSIGNED NOT NULL,
  `category_ID` bigint(20) UNSIGNED NOT NULL,
  `login` char(8) NOT NULL,
  `brief` varchar(50) NOT NULL,
  `full_question` varchar(1024) NOT NULL,
  `answer` varchar(1024) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions`
--

-- --------------------------------------------------------

--
-- Table structure for table `reactions`
--

CREATE TABLE `reactions` (
  `reaction_ID` bigint(20) UNSIGNED NOT NULL,
  `question_ID` bigint(20) UNSIGNED NOT NULL,
  `answer_login` char(8) NOT NULL,
  `reaction_login` char(8) NOT NULL,
  `text` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reactions`
--


-- --------------------------------------------------------

--
-- Table structure for table `study`
--

CREATE TABLE `study` (
  `login` char(8) NOT NULL,
  `subject_ID` varchar(5) NOT NULL,
  `points` int(11) DEFAULT '0',
  `approved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `study`
--

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_ID` varchar(5) NOT NULL,
  `subject_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `subjects`
--

-- --------------------------------------------------------

--
-- Table structure for table `teach`
--

CREATE TABLE `teach` (
  `login` char(8) NOT NULL,
  `subject_ID` varchar(5) NOT NULL,
  `approved` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `teach`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `login` char(8) NOT NULL COMMENT 'xlogin00',
  `password` varchar(255) NOT NULL,
  `role` char(1) NOT NULL,
  `deleted` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`login`,`question_ID`),
  ADD KEY `question_ID` (`question_ID`);

--
-- Indexes for table `answer_ratings`
--
ALTER TABLE `answer_ratings`
  ADD PRIMARY KEY (`question_ID`,`answer_login`,`rating_login`),
  ADD KEY `answer_login` (`answer_login`),
  ADD KEY `rating_login` (`rating_login`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`question_ID`) USING BTREE,
  ADD KEY `category_ID` (`category_ID`),
  ADD KEY `my_constraint45` (`login`);

--
-- Indexes for table `reactions`
--
ALTER TABLE `reactions`
  ADD PRIMARY KEY (`reaction_ID`),
  ADD KEY `question_ID` (`question_ID`),
  ADD KEY `answer_login` (`answer_login`),
  ADD KEY `reaction_login` (`reaction_login`);

--
-- Indexes for table `study`
--
ALTER TABLE `study`
  ADD PRIMARY KEY (`login`,`subject_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_ID`);

--
-- Indexes for table `teach`
--
ALTER TABLE `teach`
  ADD PRIMARY KEY (`login`,`subject_ID`),
  ADD KEY `subject_ID` (`subject_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`login`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `reaction_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`question_ID`) REFERENCES `questions` (`question_ID`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`login`) REFERENCES `users` (`login`);

--
-- Constraints for table `answer_ratings`
--
ALTER TABLE `answer_ratings`
  ADD CONSTRAINT `answer_ratings_ibfk_1` FOREIGN KEY (`question_ID`) REFERENCES `questions` (`question_ID`),
  ADD CONSTRAINT `answer_ratings_ibfk_2` FOREIGN KEY (`answer_login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `answer_ratings_ibfk_3` FOREIGN KEY (`rating_login`) REFERENCES `users` (`login`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_ibfk_1` FOREIGN KEY (`subject_ID`) REFERENCES `subjects` (`subject_ID`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `my_constraint45` FOREIGN KEY (`login`) REFERENCES `users` (`login`);

--
-- Constraints for table `reactions`
--
ALTER TABLE `reactions`
  ADD CONSTRAINT `reactions_ibfk_1` FOREIGN KEY (`question_ID`) REFERENCES `questions` (`question_ID`),
  ADD CONSTRAINT `reactions_ibfk_2` FOREIGN KEY (`answer_login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `reactions_ibfk_3` FOREIGN KEY (`reaction_login`) REFERENCES `users` (`login`);

--
-- Constraints for table `study`
--
ALTER TABLE `study`
  ADD CONSTRAINT `study_ibfk_1` FOREIGN KEY (`login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `study_ibfk_2` FOREIGN KEY (`subject_ID`) REFERENCES `subjects` (`subject_ID`);

--
-- Constraints for table `teach`
--
ALTER TABLE `teach`
  ADD CONSTRAINT `teach_ibfk_1` FOREIGN KEY (`login`) REFERENCES `users` (`login`),
  ADD CONSTRAINT `teach_ibfk_2` FOREIGN KEY (`subject_ID`) REFERENCES `subjects` (`subject_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
