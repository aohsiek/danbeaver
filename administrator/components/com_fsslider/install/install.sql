DROP TABLE IF EXISTS `#__fsslider`;
 
CREATE TABLE `#__fsslider` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `ordering` int(11),
  `location` varchar(120) NOT NULL,
  `title` varchar(40) NOT NULL,
  `artist` varchar(250) NOT NULL,
  `album` varchar(250) NOT NULL,
  `image` varchar(120) NOT NULL,
  `state` tinyint(3) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;