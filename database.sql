# MySQL Database
CREATE DATABASE `datadrop` /*!40100 DEFAULT CHARACTER SET latin1 */

CREATE TABLE `users` (
 `username` text NOT NULL,
 `password` text NOT NULL,
 `yubikey` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `files` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `author` text NOT NULL,
 `metadata` mediumtext NOT NULL,
 `content` mediumblob NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1