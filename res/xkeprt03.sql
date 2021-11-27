CREATE TABLE `users` (
  `login` char(8) NOT NULL primary key COMMENT 'xlogin00',
  `password` varchar(255) NOT NULL,
  `role` char(1) NOT NULL
);

CREATE TABLE `subjects` (
  `subject_ID` varchar(5) primary key NOT NULL,
  `subject_name` varchar(255) NOT NULL
);

CREATE TABLE `study` (
  `login` char(8) NOT NULL,
  `subject_ID` varchar(5) NOT NULL,
  `points` int(11) DEFAULT '0',
  `approved` tinyint(1) DEFAULT NULL,
  foreign key(login) references users(login),
  foreign key(subject_ID) references subjects(subject_ID),
  PRIMARY KEY (`login`,`subject_ID`)
);

CREATE TABLE `teach` (
  `login` char(8) NOT NULL,
  `subject_ID` varchar(5) NOT NULL,
  `approved` tinyint(1) DEFAULT NULL,
  foreign key(login) references users(login),
  foreign key(subject_ID) references subjects(subject_ID),
  primary key(login,subject_ID)
);

CREATE TABLE `category` (
  `category_ID` bigint(20) UNSIGNED NOT NULL primary key AUTO_INCREMENT,
  `subject_ID` varchar(5),
  `brief` varchar(255) NOT NULL,
  foreign key(subject_ID) references subjects(subject_ID)
);

CREATE TABLE `questions` (
  `question_ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_ID` bigint(20) UNSIGNED NOT NULL,
  `brief` varchar(50) NOT NULL,
  `full_question` varchar(1024) NOT NULL,
  `answer` varchar(1024) DEFAULT NULL,
  foreign key(category_ID) references category(category_ID),
  primary key(question_ID,category_ID)
);

CREATE TABLE `answers` (
  `login` char(8) NOT NULL,
  `question_ID` bigint(20) UNSIGNED not NULL,
  `answer` varchar(1024) DEFAULT NULL,
  foreign key(question_ID) references questions(question_ID),
  foreign key(login) references users(login),
  primary key(login,question_ID)
);

CREATE TABLE `reactions` (
  reaction_ID bigint(20) UNSIGNED NOT NULL primary key AUTO_INCREMENT,
  question_ID bigint(20) UNSIGNED NOT NULL,
  answer_login char(8) NOT NULL,  
  reaction_login char(8) NOT NULL,
  text varchar(1024) NOT NULL,
  foreign key(question_ID) references questions(question_ID),
  foreign key(answer_login) references users(login),
  foreign key(reaction_login) references users(login)
);

CREATE TABLE `answer_ratings` (
  question_ID bigint(20) UNSIGNED NOT NULL,
  answer_login char(8) NOT NULL,
  rating_login char(8) NOT NULL,
  foreign key(question_ID) references questions(question_ID),
  foreign key(answer_login) references users(login),
  foreign key(rating_login) references users(login),
  primary key(question_ID,answer_login,rating_login)
);

INSERT INTO `users` (`login`, `password`, `role`) VALUES
('admin', 'admin', 'a');


INSERT INTO `subjects` (`subject_ID`, `subject_name`) VALUES
  ('IFJ', 'Formalni jazyky'),
  ('IMS', 'Modelovani a simulace');

INSERT INTO `users` (`login`, `password`, `role`) VALUES
  ('xlogin00','xlogin00','r'),
  ('xlogin01','xlogin01','r'),
  ('xlogin02','xlogin02','r'),
  ('xlogin03','xlogin03','r'),
  ('xlogin04','xlogin04','r'),
  ('xlogin05','xlogin05','r'),
  ('xlogin06','xlogin06','r'),
  ('xlogin07','xlogin07','r'),
  ('xlogin08','xlogin08','r'),
  ('xlogin09','xlogin09','r');

INSERT INTO `users` (`login`, `password`, `role`) VALUES
  ('xmeduna1', 'meduna', 'r'),
  ('xperinge', 'peringe','r');

INSERT INTO `teach` (`login`, `subject_ID`, `approved`) VALUES ('xmeduna1', 'IFJ', NULL);
INSERT INTO `teach` (`login`, `subject_ID`, `approved`) VALUES ('xperinge', 'IMS', NULL);

INSERT INTO `study` (`login`, `subject_ID`, `approved`) VALUES
  ('xlogin00','IFJ',NULL),
  ('xlogin01','IFJ',NULL),
  ('xlogin02','IFJ',NULL),
  ('xlogin03','IFJ',NULL),
  ('xlogin04','IFJ',NULL),
  ('xlogin05','IFJ',NULL),
  ('xlogin06','IMS',NULL),
  ('xlogin07','IMS',NULL),
  ('xlogin08','IMS',NULL),
  ('xlogin09','IMS',NULL),
  ('xlogin02','IMS',NULL),
  ('xlogin03','IMS',NULL),
  ('xlogin06','IFJ',NULL),
  ('xlogin08','IFJ',NULL);

INSERT INTO `category` (`subject_ID`,`brief`) VALUES
  ('IFJ','Pulsemestralni pisemka'),
  ('IMS','pisemka 1'),
  ('IFJ','Semestralni pisemka'),  
  ('IMS','Pulsemestralni pisemka');

  
  
