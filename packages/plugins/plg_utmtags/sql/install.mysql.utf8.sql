UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'utmtags' AND `type` = 'plugin' AND `folder` = 'kmplugins'
;

ALTER TABLE  `#__ksenmart_orders` ADD  `utmtags` TEXT NOT NULL, ADD  `referer` TEXT NOT NULL;