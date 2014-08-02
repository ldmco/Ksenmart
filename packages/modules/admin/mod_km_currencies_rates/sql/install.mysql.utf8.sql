UPDATE 
	`#__modules` 
SET 
	`title` = 'Курсы валют KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["currencies"]}'
WHERE 
	`module` = 'mod_km_currencies_rates'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_currencies_rates'), 
	'0'
);