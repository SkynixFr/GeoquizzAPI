-- Adminer 4.7.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `partie`;
CREATE TABLE `partie` (
  `id` varchar(200) NOT NULL,
  `token` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nbphotos` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT '0',
  `pseudo` varchar(15) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `partie` (`id`, `token`, `nbphotos`, `status`, `score`, `pseudo`, `created_at`, `updated_at`, `deleted_at`) VALUES
('0131e259-0cc3-4e32-954f-29a7a383b9ef',	'ce6d45db-fa4d-4c95-a8ea-d3eb063fc1a4',	10,	2,	0,	'Skynix',	'2020-03-23 11:31:47',	'2020-03-23 11:38:20',	'2020-03-23 11:38:20'),
('f78a1102-be69-4338-aea4-f0d8232ea265',	'a7d979df-ba8a-4a9b-bf7d-a6ae5009460c',	10,	0,	0,	'Skynix',	'2020-03-23 12:28:21',	'2020-03-23 12:31:21',	NULL),
('cdd7fd2e-ece1-4cb7-9776-0db0c83cb9a1',	'6ccb630c-e9d4-401f-bbc8-dbf4b0bb4ad1',	10,	0,	0,	'Sky',	'2020-03-25 09:40:40',	'2020-03-25 09:40:40',	NULL);

DROP TABLE IF EXISTS `photo`;
CREATE TABLE `photo` (
  `id` varchar(200) NOT NULL,
  `description` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `zoom` int(10) DEFAULT NULL,
  `url` varchar(200) DEFAULT NULL,
  `id_serie` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_serie` (`id_serie`),
  CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id_serie`) REFERENCES `serie` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `photo` (`id`, `description`, `latitude`, `longitude`, `zoom`, `url`, `id_serie`) VALUES
('fzfzfzfz',	'zegzgzf',	15,	15,	15,	'zegzfs',	'fsdgstfdsgsgsgs'),
('jkfdlkq:djhfjzekharjjhzbklrjaqfsv',	'ZESDWGSFDQGHETJ',	15.2,	12.2,	15,	'zfezhezaqEDTJRJ',	'fsdgstfdsgsgsgs');

DROP TABLE IF EXISTS `serie`;
CREATE TABLE `serie` (
  `id` varchar(200) NOT NULL,
  `ville` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `map_refs` varchar(200) DEFAULT NULL,
  `dist` decimal(10,0) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `serie` (`id`, `ville`, `map_refs`, `dist`) VALUES
('fsdgstfdsgsgsgs',	'Nancy',	NULL,	15);

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` varchar(200) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motdepasse` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `email`, `motdepasse`) VALUES
('1',	'hugo.pallara@gmail.com',	'salutatous'),
('2',	'romain.day@gmail.com',	'salutatous'),
('3',	'ludo.meligner@gmail.com',	'salutatous'),
('4',	'arthur.zinni@gmail.com',	'salutatous');

-- 2020-03-25 13:03:54
