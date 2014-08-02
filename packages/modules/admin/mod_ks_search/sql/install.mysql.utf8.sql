UPDATE 
	`#__modules` 
SET 
	`title` = 'Поиск KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`ordering` = 1,
	`params` = '{"views":["catalog"]}'
WHERE 
	`module` = 'mod_ks_search'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ks_search'), 
	'0'
);