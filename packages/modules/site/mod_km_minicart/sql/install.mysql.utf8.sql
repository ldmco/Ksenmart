UPDATE 
	`#__modules` 
SET 
	`title` = 'Миникорзина (Ksenmart)', 
	`position` = 'ks-minicart', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_minicart'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_minicart'), 
	'0'
);