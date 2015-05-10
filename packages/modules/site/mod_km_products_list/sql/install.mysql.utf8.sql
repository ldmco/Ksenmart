UPDATE 
	`#__modules` 
SET 
	`title` = 'Список товаров (Ksenmart)', 
	`position` = 'ks-main-products-list', 
	`published` = '1'
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