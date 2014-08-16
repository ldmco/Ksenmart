UPDATE 
	`#__modules` 
SET 
	`title` = 'Типы скидок KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["discounts"]}'
WHERE 
	`module` = 'mod_km_discount_types'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_discount_types'), 
	'0'
);