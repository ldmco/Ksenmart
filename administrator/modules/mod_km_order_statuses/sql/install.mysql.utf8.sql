UPDATE 
	`#__modules` 
SET 
	`title` = 'Статусы заказов KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["orders"]}'
WHERE 
	`module` = 'mod_km_order_statuses'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_order_statuses'), 
	'0'
);