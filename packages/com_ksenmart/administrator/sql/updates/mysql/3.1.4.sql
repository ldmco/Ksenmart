CREATE TABLE IF NOT EXISTS `#__ksenmart_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) NOT NULL,
  `media_type` varchar(15) NOT NULL,
  `owner_type` varchar(256) NOT NULL,
  `folder` varchar(32) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `mime_type` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `ordering` int(10) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `media_type` (`media_type`),
  KEY `owner_type` (`owner_type`),
  KEY `folder` (`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT `#__ksenmart_files` SELECT * FROM `#__ksen_files` AS `ksf` WHERE `ksf`.`owner_type`!='user';