UPDATE 
	`#__modules` 
SET 
	`title` = 'Страны KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["countries"]}'
WHERE 
	`module` = 'mod_km_countries'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_countries'), 
	'0'
);