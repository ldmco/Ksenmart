UPDATE 
	`#__extensions` 
SET 
	`enabled` = 1
WHERE 
	`element` = 'roistat'
;

ALTER TABLE `#__ksenmart_orders` ADD `roistat` INT(11) NOT NULL AFTER `status_id`;