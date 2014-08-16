UPDATE 
	`#__modules` 
SET 
	`title` = 'Производители KsenMart', 
	`position` = 'km-list-left', 
	`published` = 1,
	`ordering` = 4,
	`params` = '{"views":["catalog"]}'
WHERE 
	`module` = 'mod_km_manufacturers'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_manufacturers'), 
	'0'
);