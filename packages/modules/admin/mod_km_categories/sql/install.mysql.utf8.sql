UPDATE 
	`#__modules` 
SET 
	`title` = 'Категории KsenMart', 
	`position` = 'km-list-left', 
	`published` = 1,
	`ordering` = 2,
	`params` = '{"views":["catalog", "properties"]}'
WHERE 
	`module` = 'mod_km_categories'
AND
	`client_id`=1
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_categories' AND `client_id`=1 LIMIT 1), 
	'0'
);