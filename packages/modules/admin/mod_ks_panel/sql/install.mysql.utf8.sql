UPDATE 
	`#__modules` 
SET 
	`title` = 'Навигационная панель',
	`position` = 'cpanel',
	`published` = '1'
WHERE 
	`module` = 'mod_ks_panel'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ks_panel'),
	'0'
);