UPDATE 
	`#__modules` 
SET 
	`title` = 'Список продуктов', 
	`position` = 'content_top', 
	`published` = '1',
	`params`='{"layout":"_:dropdown"}'
WHERE 
	`module` = 'mod_km_products_list'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_products_list'), 
	'0'
);