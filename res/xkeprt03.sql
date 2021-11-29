-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 29, 2021 at 10:18 PM
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
('enexy', 31, 'ipv4 roote', 1),
('xlogin04', 32, 'Pruchod stromu je mozne uskutocnit viacerymi sposobmi ale ide o naozaj zlozitu problematiku a vsak povedal by som ze to mozne je pokial su splnenÃ© poÅ¾adovanÃ© podmienky, ktorÃ© musia platit inak priechod stromu nebude moÅ¾nÃ½ a teda iba jedine sa to da urobiÅ¥ rekurzivne ale zarovne aj nerekruzia sa da uplatniÅ¥.', 1),
('xlogin04', 33, 'Îµ = 8', NULL),
('xlogin04', 35, 'MoÅ¾e to byÅ¥? od inda z youtube ? https://www.youtube.com/watch?v=XB4MIexjvY0', NULL),
('xlogin04', 36, 'Tiez som za to, ze som zaockovany', NULL),
('xlogin04', 37, 'Masky su podla hygieny potrebne aj doma.', NULL),
('xlogin05', 31, 'ip route', 0),
('xlogin05', 33, 'b = 2 ? a epsilon asi 0', 1),
('xlogin07', 31, 'ip rout', 1),
('xlogin07', 32, 'Zhora a zospodu ale da sa aj zlava do prava ', NULL),
('xlogin07', 33, 'epsiol si nemyslim  ze je definovane ale b je isto 1', 1),
('xlogin69', 32, 'Rekurzivne a nerekurzivne, viac si nepamatam.', 1),
('xlogin69', 35, 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', NULL),
('xlogin69', 36, 'Zaockovany', NULL);

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
(33, 'xlogin04', 'xlogin07'),
(37, 'xlogin04', 'mahmudek'),
(37, 'xlogin04', 'xlogin04'),
(32, 'xlogin07', 'xlogin04'),
(32, 'xlogin69', 'xlogin04'),
(32, 'xlogin69', 'xlogin69'),
(36, 'xlogin69', 'xlogin04'),
(36, 'xlogin69', 'xlogin69');

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
(12, 'IAL', 'projekt'),
(13, 'ISA', 'pÅ¯lsemestrÃ¡lnÃ­ test'),
(14, 'IFJ', 'semestrÃ¡lka'),
(15, 'IAL', 'Plagiati'),
(16, 'IAL', 'Covid-42');

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
(30, 12, 'rakosnik', 'Preklad ', 'Dobry den, prosim vas mozme projekt prekladat s standartom std=C11', 'Tohle tu nema co delat, otazky k projektu smerujte jinde ;).'),
(31, 13, 'tomko', 'ARP tabulka', 'AkÃ½m prÃ­kazom si vieme zobraziÅ¥ zÃ¡znamy v smerovacej ARP tabulke?', 'ip route'),
(32, 12, 'mahmudek', 'Stromy', 'NapiÅ¡te prÅ¯chody stromu', 'Level-order, NerekurzivnÃ­ PostOrder,NerekurzivnÃ­ InOrder,NerekurzivnÃ­ PreOrder ...'),
(33, 14, 'mahmudek', 'Å™etÄ›zce ', 'Jakou dÃ©lku majÃ­ Å™etÄ›zce  b a Îµ', '|b|= 1 a |Îµ|= 0'),
(34, 14, 'xlogin69', 'termin', 'Prosim vas, kedy je termin na semestralku? Mal som teraz 2 tyzdne rakovinu, tak som bol nuteny vymeskat skolu.', 'Termin semestrÃ¡lky  sa bohuÅ¾ial nedÃ¡ nahradiÅ¥.S pozdravom Kepy ;).'),
(35, 15, 'rakosnik', 'A DOST', 'Bola za mnou jedna skupina, ktora chcela nahradny termin, pretoze clen tymu sposobil Segmentation Fault kopirovanim kodu z ineho predmetu. Toto sa NETOLERUJE! Tento tym automaticky dostal 0b. Vsetci ostatni, co ste skopirovali co i len jeden riadok kodu sa priznajte, inak dostanete 0 bodov!', NULL),
(36, 16, 'rakosnik', 'Nove nariadenia', 'Kedze sa tu premnozil virus Covid-42, vsetci, co niesu zaockovany maju automaticky znizeny pocet bodov o polovicu. Vsetci co ste zaockovani sa tu nahlaste, nech viem.', NULL),
(37, 16, 'xlogin69', 'Masky', 'Musime nosit masky, ked budeme doma sami pracovat na projekte?', NULL),
(38, 15, 'xlogin04', 'InÅ¡piracia z githubu', 'Kamarat P sa chce skopirovaÅ¥ minulorocne riesenie . PovaÅ¾uje sa to za plagiat ?', 'To je samozrejme v poriadku. Kody z minulorocnych projektov sa beru ako skolsky majetok a co je skolsky majetok mozete pouzit ako ucebny material. Pokial zmenite nazvy premennych, tak by ste mali byt v poriadku.');

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
(11, 31, 'enexy', 'xlogin04', 'Tak to teda urÄite ne...'),
(12, 31, 'xlogin07', 'xlogin05', 'nieÄo ti tam chÃ½ba'),
(13, 33, 'xlogin07', 'xlogin04', 'dlzku epsilonu prece poznÃ¡me ne ? '),
(14, 32, 'xlogin69', 'xlogin07', 'Ako jo to by dÃ¡valo zmysl.'),
(15, 37, 'xlogin04', 'xlogin04', 'Tru'),
(16, 32, 'xlogin04', 'xlogin04', 'VaÅ¡u odpoved som si preÄÃ­tal 3 krat a stale nechapem co ste tÃ½m myslel.'),
(17, 32, 'xlogin04', 'xlogin04', 'VaÅ¡u odpoved som si preÄÃ­tal 3 krat a stale nechapem co ste tÃ½m myslel.'),
(18, 32, 'xlogin04', 'xlogin04', 'No ja si nemyslim, ze to je tak zavisle od pozadovanych podmienok, lebo strom mozes prechadzat aj ked nesplna pozadovane podmienky ale len rucne. Rekurzivne by som povedal, ze strom nie je mozne prechadzat nerekurzivne ak si nevytvoris pomocny ADT ako napriklad stack alebo heap.'),
(19, 37, 'xlogin04', 'mahmudek', 'Tiez si myslim, treba chranit zdravie clenov tvojho timu. A ak sa to da robit z pohodlia vasho domova, o to lepsie.'),
(20, 36, 'xlogin69', 'xlogin04', 'O tom teda neviem');

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
('enexy', 'ISA', 0, 1),
('kepy', 'IAL', 0, NULL),
('mahmudek', 'IAL', 0, 1),
('mahmudek', 'IFJ', 0, 1),
('mod', 'IAL', 0, 0),
('xlogin04', 'IAL', 1, 1),
('xlogin04', 'IFJ', 0, 1),
('xlogin04', 'ISA', 0, 1),
('xlogin05', 'IAL', 0, 0),
('xlogin05', 'IFJ', 6, 1),
('xlogin05', 'ISA', 8, 1),
('xlogin07', 'IAL', 0, 1),
('xlogin07', 'IFJ', 4, 1),
('xlogin07', 'ISA', 4, 1),
('xlogin69', 'IAL', 5, 1),
('xlogin69', 'IFJ', 0, 1),
('xlogin69', 'ISA', 0, 0);

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
('IAL', 'Algoritmy'),
('IFJ', 'FormÃ¡lnÃ­ jazyky a pÅ™ekladaÄe'),
('IMS', 'modelovÃ¡nÃ­ a simulace'),
('ISA', 'SÃ­Å¥ovÃ© aplikace');

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
('kepy', 'IFJ', 1),
('mahmudek', 'IMS', 0),
('rakosnik', 'IAL', 1),
('tomko', 'ISA', 1);

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
('enexy', 'enexy', 'r', NULL),
('kepy', 'kepz', 'r', NULL),
('mahmudek', 'mahmudek', 'r', NULL),
('mod', 'mod', 'm', NULL),
('rakosnik', 'rakosnik', 'r', NULL),
('tomko', 'tomko', 'r', NULL),
('xlogin04', 'xlogin04', 'r', NULL),
('xlogin05', 'xlogin05', 'r', NULL),
('xlogin07', 'xlogin07', 'r', NULL),
('xlogin69', 'xlogin69', 'r', NULL);

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
  MODIFY `category_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `question_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `reactions`
--
ALTER TABLE `reactions`
  MODIFY `reaction_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
