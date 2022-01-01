/*
SQLyog Community v12.2.5 (64 bit)
MySQL - 5.7.21 : Database - dopamine
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`dopamine` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dopamine`;

/*Table structure for table `sys_nav` */

DROP TABLE IF EXISTS `sys_nav`;

CREATE TABLE `sys_nav` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) DEFAULT NULL,
  `icon` varchar(16) DEFAULT NULL,
  `query_string` varchar(50) DEFAULT NULL,
  `root` int(11) DEFAULT NULL,
  `position` tinyint(1) DEFAULT NULL,
  `is` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

/*Data for the table `sys_nav` */

insert  into `sys_nav`(`id`,`title`,`icon`,`query_string`,`root`,`position`,`is`) values 
(1,'System',NULL,NULL,NULL,99,0),
(2,'Configuration','fa fa-sliders-h','',1,0,0),
(3,'Index','','Configuration/index',2,0,0),
(4,'Item','','Configuration/item',2,0,10),
(5,'Group','','Configuration/group',2,0,10),
(6,'Remove','','Configuration/remove',2,0,10),
(7,'Update','','Configuration/update',2,0,10),
(8,'Operator','far fa-user','',1,0,1),
(9,'Manage','','User/add',8,0,1),
(10,'Users','','User/approve',8,0,10),
(11,'Privilege','','User/privilege',8,0,10),
(12,'Profile','','User/profile',8,0,10),
(13,'Remove Profile Image','','User/remove-profile-image',8,0,11),
(14,'Change Password','','User/change-password',8,0,11),
(15,'Home',NULL,NULL,NULL,NULL,0),
(16,'Home','fa fa-home','Home/index',15,0,1);

/*Table structure for table `sys_privilege` */

DROP TABLE IF EXISTS `sys_privilege`;

CREATE TABLE `sys_privilege` (
  `uid` int(11) NOT NULL,
  `nav` int(11) NOT NULL,
  `permit` tinyint(4) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `sys_privilege` */

/*Table structure for table `sys_user` */

DROP TABLE IF EXISTS `sys_user`;

CREATE TABLE `sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varbinary(50) DEFAULT NULL,
  `position` int(11) NOT NULL DEFAULT '0',
  `permit` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

/*Data for the table `sys_user` */

insert  into `sys_user`(`id`,`first_name`,`last_name`,`username`,`password`,`email`,`position`,`permit`) values 
(1,'System','Administrator','root','5ad9bc9f6a29e906964dcb09e730364f','ashraf.fahim75@gmail.com',0,1);

/*Table structure for table `privilege` */

DROP TABLE IF EXISTS `privilege`;

/*!50001 DROP VIEW IF EXISTS `privilege` */;
/*!50001 DROP TABLE IF EXISTS `privilege` */;

/*!50001 CREATE TABLE  `privilege`(
 `id` int(11) ,
 `title` varchar(20) ,
 `icon` varchar(16) ,
 `query_string` varchar(50) ,
 `root` int(11) ,
 `position` tinyint(4) ,
 `is` tinyint(4) ,
 `uid` varchar(11) ,
 `nav` varchar(11) ,
 `permit` varchar(4) 
)*/;

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

/*!50001 DROP VIEW IF EXISTS `user` */;
/*!50001 DROP TABLE IF EXISTS `user` */;

/*!50001 CREATE TABLE  `user`(
 `id` int(11) ,
 `first_name` varchar(20) ,
 `last_name` varchar(20) ,
 `username` varchar(20) ,
 `password` varchar(50) ,
 `email` varbinary(50) ,
 `position` int(11) ,
 `permit` tinyint(1) ,
 `position_first_name` varchar(20) ,
 `position_last_name` varchar(20) 
)*/;

/*View structure for view privilege */

/*!50001 DROP TABLE IF EXISTS `privilege` */;
/*!50001 DROP VIEW IF EXISTS `privilege` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `privilege` AS (select `priv`.`id` AS `id`,`priv`.`title` AS `title`,`priv`.`icon` AS `icon`,`priv`.`query_string` AS `query_string`,`priv`.`root` AS `root`,`priv`.`position` AS `position`,`priv`.`is` AS `is`,`priv`.`uid` AS `uid`,`priv`.`nav` AS `nav`,`priv`.`permit` AS `permit` from (select `n`.`id` AS `id`,`n`.`title` AS `title`,`n`.`icon` AS `icon`,`n`.`query_string` AS `query_string`,`n`.`root` AS `root`,`n`.`position` AS `position`,`n`.`is` AS `is`,`p`.`uid` AS `uid`,`p`.`nav` AS `nav`,`p`.`permit` AS `permit` from (`dopamine`.`sys_nav` `n` join `dopamine`.`sys_privilege` `p` on((`n`.`id` = `p`.`nav`))) union select `dopamine`.`sys_nav`.`id` AS `id`,`dopamine`.`sys_nav`.`title` AS `title`,`dopamine`.`sys_nav`.`icon` AS `icon`,`dopamine`.`sys_nav`.`query_string` AS `query_string`,`dopamine`.`sys_nav`.`root` AS `root`,`dopamine`.`sys_nav`.`position` AS `position`,`dopamine`.`sys_nav`.`is` AS `is`,'0' AS `uid`,'0' AS `nav`,'1' AS `permit` from `dopamine`.`sys_nav` where ((`dopamine`.`sys_nav`.`is` = 1) or (`dopamine`.`sys_nav`.`is` = 11))) `priv`) */;

/*View structure for view user */

/*!50001 DROP TABLE IF EXISTS `user` */;
/*!50001 DROP VIEW IF EXISTS `user` */;

/*!50001 CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user` AS select `p`.`id` AS `id`,`p`.`first_name` AS `first_name`,`p`.`last_name` AS `last_name`,`p`.`username` AS `username`,`p`.`password` AS `password`,`p`.`email` AS `email`,`p`.`position` AS `position`,`p`.`permit` AS `permit`,`u`.`first_name` AS `position_first_name`,`u`.`last_name` AS `position_last_name` from (`sys_user` `p` left join `sys_user` `u` on((`p`.`position` = `u`.`id`))) */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
