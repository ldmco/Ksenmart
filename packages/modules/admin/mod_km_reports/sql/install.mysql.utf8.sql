UPDATE 
	`#__modules` 
SET 
	`title` = 'Отчеты KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["reports"]}'
WHERE 
	`module` = 'mod_km_reports'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_reports'), 
	'0'
);