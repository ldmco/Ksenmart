UPDATE 
	`#__modules` 
SET 
	`title` = 'Методы доставки KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["shippings"]}'
WHERE 
	`module` = 'mod_km_shipping_methods'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_shipping_methods'), 
	'0'
);