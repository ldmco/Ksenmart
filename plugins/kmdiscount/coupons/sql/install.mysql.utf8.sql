CREATE TABLE IF NOT EXISTS `#__ksenmart_discount_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL DEFAULT '',
  `published` int(2) NOT NULL DEFAULT '1',
  `used` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'coupons'
;