CREATE TABLE IF NOT EXISTS `#__ksen_billing_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` blob NOT NULL,
  `sessid` decimal(12,0) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_files` (
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

CREATE TABLE IF NOT EXISTS `#__ksen_seo_config` (
  `id` int(10) NOT NULL AUTO_INCREMENT,	
  `extension` varchar(256) NOT NULL,  
  `part` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`id`)  
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_seo_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__ksen_seo_types` (`id`, `title`) VALUES
(1, 'seo-urls-config'),
(2, 'seo-meta-config'),
(3, 'seo-titles-config');

CREATE TABLE IF NOT EXISTS `#__ksen_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(256) NOT NULL,
  `middle_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,  
  `region_id` int(10) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `watched` text NOT NULL,
  `favorites` text NOT NULL,
  `social` varchar(10) NOT NULL,
  `settings` varchar(255) NOT NULL DEFAULT '{"catalog_layout":"grid"} ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_user_addresses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `city` varchar(256) NOT NULL,
  `zip` varchar(256) NOT NULL,
  `street` varchar(256) NOT NULL,
  `house` varchar(256) NOT NULL,
  `entrance` varchar(256) NOT NULL,
  `floor` varchar(256) NOT NULL,
  `flat` varchar(256) NOT NULL,
  `coords` varchar(256) NOT NULL,
  `default` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_user_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `ordering` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_user_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`field_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `extension` varchar(255) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `group` int(2) NOT NULL,
  `class` set('double','half','main','sub') NOT NULL DEFAULT 'sub',
  `href` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `view` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_widgets_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `extension` varchar(255) NOT NULL,  
  `name` varchar(256) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_widgets_types_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksen_widgets_users_config` (
  `user_id` int(11) NOT NULL,
  `extension` varchar(30) NOT NULL,
  `widget_type` varchar(30) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`user_id`,`extension`,`widget_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ksen_ping` (
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__ksen_ping` (`date`) VALUES
('0000-00-00');