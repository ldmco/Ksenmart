UPDATE 
	`#__modules` 
SET 
	`title` = 'Типы экспорта/импорта KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["exportimport"]}'
WHERE 
	`module` = 'mod_km_exportimport_types'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_exportimport_types'), 
	'0'
);