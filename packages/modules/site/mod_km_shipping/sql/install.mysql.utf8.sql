UPDATE 
	`#__modules` 
SET 
	`title` = 'Доставка (Ksenmart)', 
	`position` = 'ks-shipping-info', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_shipping'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_shipping'), 
	'0'
);