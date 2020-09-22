UPDATE 
	`#__modules` 
SET 
	`position` = 'menu', 
	`published` = '1',
	ordering = '1'
WHERE 
	`module` = 'mod_km_admin_menu'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_admin_menu'), 
	'0'
);