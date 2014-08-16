UPDATE 
	`#__modules` 
SET 
	`title` = 'Типы оплаты KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["payments"]}'
WHERE 
	`module` = 'mod_km_payment_types'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_payment_types'), 
	'0'
);