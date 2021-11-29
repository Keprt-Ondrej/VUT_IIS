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

INSERT INTO `answers` (`login`, `question_ID`, `answer`, `correct`) VALUES
('admin', 6, 'hello', NULL),
('admin', 8, 'ahoj', 1),
('rakosnik', 1, 'Pre jazyk C existuje funkcia char *fgets(char *str, int n, FILE *stream), ktorej das buffer a pocet znakov na nacitanie. POZOR ale, ak chces zapisat viac znakov ako je v poli, tak sa strelis do nohy a bude zle!', 1),
('xlogin02', 2, 'Tohle je test odpovedi', 1),
('xlogin03', 2, 'Tohle je test odpovedi', 1),
('xlogin04', 2, 'Tohle je test odpovedi', 0),
('xlogin05', 2, 'Tohle je test odpovedi', 1),
('xlogin25', 2, 'Odpovidam', 1);

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

INSERT INTO `answer_ratings` (`question_ID`, `answer_login`, `rating_login`) VALUES
(1, 'rakosnik', 'peringer'),
(1, 'rakosnik', 'rakosnik'),
(2, 'xlogin02', 'xlogin13'),
(2, 'xlogin02', 'xlogin14'),
(2, 'xlogin02', 'xlogin24'),
(2, 'xlogin02', 'xlogin25'),
(2, 'xlogin03', 'xlogin13'),
(2, 'xlogin03', 'xlogin14'),
(2, 'xlogin03', 'xlogin24'),
(2, 'xlogin03', 'xlogin25'),
(2, 'xlogin04', 'xlogin13'),
(2, 'xlogin04', 'xlogin14'),
(2, 'xlogin04', 'xlogin24'),
(2, 'xlogin04', 'xlogin25'),
(2, 'xlogin05', 'xlogin13'),
(2, 'xlogin05', 'xlogin24'),
(2, 'xlogin25', 'xlogin03'),
(2, 'xlogin25', 'xlogin09'),
(2, 'xlogin25', 'xlogin24'),
(2, 'xlogin25', 'xlogin25'),
(2, 'xlogin25', 'xlogin28');

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

INSERT INTO `category` (`category_ID`, `subject_ID`, `brief`) VALUES
(3, 'IFJ', 'Pulsemestralni pisemka'),
(4, 'IMS', 'pisemka 1'),
(5, 'IFJ', 'Semestralni pisemka'),
(6, 'IMS', 'Pulsemestralni pisemka'),
(7, 'IZP', 'Dovody preco toto je ez predmet'),
(8, 'IZP', 'Zaklady sedliackeho rozumu'),
(9, 'IFJ', 'Otazky k projektu'),
(10, 'IFJ', 'necekany testik'),
(11, 'IFJ', 'lopata');

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

INSERT INTO `questions` (`question_ID`, `category_ID`, `login`, `brief`, `full_question`, `answer`) VALUES
(1, 8, '', 'Ako mam citat uzivatelsky vstup?', 'Zacal som robit na projekte, ale zistil som, ze vlastne neviem programovat. Tak som si pozrel Indov na tom jutube a oni tam nejako brali vstup od uzivatela, ale nevidel som ako, lebo to video bolo v 360p kvalite. Ako mam teda ziskat uzivatelk vstup?', 'Git gud scrub.'),
(2, 3, '', 'Automaty', 'JakÃ© druhy koneÄnÃ½ch automatu znÃ¡te. KrÃ¡tce kaÅ¾dÃ½ popiÅ¡te.', 'Mame zasobnikove RZA a nevim uz'),
(5, 3, '', 'test', 'test', NULL),
(6, 3, '', 'ahoj', 'ahoj', NULL),
(7, 7, '', 'xD', 'XD', NULL),
(8, 7, '', 'ahojky', 'ahojky', 'Cau more'),
(9, 7, '', 'mozno', 'mozno', NULL),
(10, 7, '', 'Haah', 'Haha', NULL),
(11, 7, '', 'uz?', 'Uz', NULL),
(12, 7, '', 'xaxa', 'xaxa', NULL),
(13, 3, '', 'XD', 'XD', NULL),
(14, 3, '', 'ahaaa', 'aha', NULL),
(18, 3, '', 'asfhgfjfdhgfjfhgjfgh', 'sdfsdghfdhfgjhgj', NULL),
(19, 3, '', 'UZ?', 'i hope so', NULL),
(20, 3, '', 'konecna', 'konecnaasd', NULL),
(21, 3, '', 'final test question', 'karel', NULL),
(22, 3, '', 'really final', 'karel2', NULL),
(24, 7, 'x pog 69', 'Why???', 'Why are we here? Just to suffer?', NULL),
(25, 7, 'x pog 69', 'Why???', 'Why are we here? Just to suffer?', NULL),
(26, 7, 'x pog 69', 'Why???', 'Why are we here? Just to suffer?', NULL),
(27, 7, 'x pog 69', 'Why???', 'Why are we here? Just to suffer?', NULL),
(28, 7, 'x pog 69', 'Why???', 'Why are we here? Just to suffer?', NULL),
(29, 7, 'x pog 69', 'Sorry bois', 'I did\'t mean to spam.. Forgive me?', NULL);

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

INSERT INTO `reactions` (`reaction_ID`, `question_ID`, `answer_login`, `reaction_login`, `text`) VALUES
(1, 1, 'rakosnik', 'peringer', 'Jo, strelite se do nohy KEKW'),
(2, 2, 'xlogin04', 'xlogin25', 'reakceeeeeeeeee'),
(3, 2, 'xlogin04', 'xlogin25', 'reakce 2'),
(4, 2, 'xlogin04', 'xlogin25', 'reakce 2'),
(5, 2, 'xlogin04', 'xlogin25', 'reakce 3'),
(6, 2, 'xlogin04', 'xlogin25', 'reakce 4'),
(7, 2, 'xlogin04', 'xlogin25', 'karelll'),
(8, 2, 'xlogin04', 'xlogin25', 'lopata'),
(9, 2, 'xlogin04', 'xlogin25', 'karellllllll'),
(10, 2, 'xlogin02', 'xtester1', 'karel');

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

INSERT INTO `study` (`login`, `subject_ID`, `points`, `approved`) VALUES
('admin', 'IFJ', 2, 1),
('admin', 'IMA1', 0, NULL),
('admin', 'IMS', 2, 0),
('admin', 'IPT', 2, NULL),
('admin', 'ISA', 2, NULL),
('admin', 'IZP', 140, 0),
('rakosnik', 'IFJ', 4, 0),
('x pog 69', 'IZP', 0, 1),
('xD', 'IZP', 0, NULL),
('xlogin00', 'IFJ', 2, NULL),
('xlogin00', 'IMS', 2, 0),
('xlogin00', 'IPT', 2, 0),
('xlogin00', 'IZP', 2, 0),
('xlogin01', 'IFJ', 2, NULL),
('xlogin02', 'IFJ', 52, NULL),
('xlogin02', 'IMS', 2, NULL),
('xlogin03', 'IFJ', 2, 1),
('xlogin03', 'IMS', 2, NULL),
('xlogin04', 'IFJ', 12, 0),
('xlogin05', 'IFJ', 2, 0),
('xlogin05', 'IMS', 2, 0),
('xlogin05', 'IPT', 2, 0),
('xlogin05', 'ISA', 2, 0),
('xlogin05', 'IZP', 2, 0),
('xlogin06', 'IFJ', 2, 1),
('xlogin06', 'IMS', 2, NULL),
('xlogin06', 'IPT', 2, 0),
('xlogin06', 'ISA', 2, 0),
('xlogin06', 'IZP', 2, 0),
('xlogin07', 'IMS', 2, NULL),
('xlogin08', 'IFJ', 2, 0),
('xlogin08', 'IMS', 2, NULL),
('xlogin08', 'IPT', 2, 0),
('xlogin08', 'ISA', 2, 0),
('xlogin08', 'IZP', 2, 0),
('xlogin09', 'IMS', 2, NULL),
('xlogin11', 'IFJ', 0, 1),
('xlogin12', 'IFJ', 0, 1),
('xlogin13', 'IFJ', 0, NULL),
('xlogin14', 'IFJ', 0, NULL),
('xlogin15', 'IFJ', 0, 1),
('xlogin16', 'IFJ', 0, 0),
('xlogin17', 'IFJ', 0, 1),
('xlogin18', 'IFJ', 0, 1),
('xlogin19', 'IFJ', 0, 1),
('xlogin21', 'IFJ', 0, NULL),
('xlogin22', 'IFJ', 0, NULL),
('xlogin23', 'IFJ', 0, 1),
('xlogin24', 'IFJ', 0, NULL),
('xlogin25', 'IFJ', 100, 1),
('xlogin25', 'IZP', 0, NULL),
('xlogin26', 'IFJ', 0, NULL),
('xlogin45', 'IFJ', 0, 1),
('xlogin45', 'IMA1', 0, NULL),
('xlogin45', 'IZP', 0, NULL),
('xmeduna1', 'IMS', 0, NULL),
('xtester0', 'IMA1', 0, NULL),
('xtester0', 'IZP', 0, NULL),
('xtester1', 'IFJ', 0, 1);

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

INSERT INTO `subjects` (`subject_ID`, `subject_name`) VALUES
('IFJ', 'Formalni jazyky'),
('IMA1', 'MatematickÃ¡ analÃ½za 1'),
('IMS', 'Modelovani a simulace'),
('IPT', 'Cancer'),
('ISA', 'Velky bordel'),
('IZP', 'Brutalne ez predmet');

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

INSERT INTO `teach` (`login`, `subject_ID`, `approved`) VALUES
('rakosnik', 'IPT', 0),
('rakosnik', 'ISA', 0),
('rakosnik', 'IZP', 1),
('xmeduna1', 'IFJ', 1),
('xmeduna1', 'IMA1', 1),
('xperinge', 'IMS', NULL);

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

INSERT INTO `users` (`login`, `password`, `role`, `deleted`) VALUES
('admin', 'admin', 'a', NULL),
('ahoj', 'ahoj', 'm', 1),
('bordel', 'bordel', 'a', 1),
('peringer', 'lopata', 'a', 1),
('rakosnik', 'rakosnik', 'a', NULL),
('x pog 69', 'x pog 69', 'r', NULL),
('xD', 'ahoj', 'r', 1),
('xlogin00', 'xlogin00', 'a', 1),
('xlogin01', 'xlogin01', 'r', 1),
('xlogin02', 'xlogin02', 'r', NULL),
('xlogin03', 'xlogin03', 'r', NULL),
('xlogin04', 'xlogin04', 'r', NULL),
('xlogin05', 'xlogin05', 'r', NULL),
('xlogin06', 'xlogin06', 'r', NULL),
('xlogin07', 'xlogin07', 'r', NULL),
('xlogin08', 'xlogin08', 'r', NULL),
('xlogin09', 'xlogin09', 'r', NULL),
('xlogin11', 'xlogin11', 'r', NULL),
('xlogin12', 'xlogin12', 'r', NULL),
('xlogin13', 'xlogin13', 'r', NULL),
('xlogin14', 'xlogin14', 'r', NULL),
('xlogin15', 'xlogin15', 'r', NULL),
('xlogin16', 'xlogin16', 'r', NULL),
('xlogin17', 'xlogin17', 'r', NULL),
('xlogin18', 'xlogin18', 'r', NULL),
('xlogin19', 'xlogin19', 'r', NULL),
('xlogin21', 'xlogin21', 'r', NULL),
('xlogin22', 'xlogin22', 'r', NULL),
('xlogin23', 'xlogin23', 'r', NULL),
('xlogin24', 'xlogin24', 'r', NULL),
('xlogin25', 'xlogin25', 'r', 1),
('xlogin26', 'xlogin26', 'r', NULL),
('xlogin27', 'xlogin27', 'r', NULL),
('xlogin28', 'xlogin28', 'r', NULL),
('xlogin29', 'xlogin29', 'r', 1),
('xlogin30', 'xlogin30', 'r', NULL),
('xlogin31', 'xlogin31', 'r', NULL),
('xlogin32', 'xlogin32', 'r', NULL),
('xlogin33', 'xlogin33', 'r', NULL),
('xlogin34', 'xlogin34', 'r', NULL),
('xlogin45', 'xlogin45', 'r', NULL),
('xlogin46', 'xlogin46', 'r', NULL),
('xlogin69', 'xlogin69', 'r', 1),
('xlogin78', 'karel', 'a', NULL),
('xmeduna1', 'xmeduna1', 'r', NULL),
('xperinge', 'peringe', '', NULL),
('xtester0', 'xtester0', 'a', NULL),
('xtester1', 'xtester1', 'r', NULL);

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
