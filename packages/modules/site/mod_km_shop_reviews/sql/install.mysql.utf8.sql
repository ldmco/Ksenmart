UPDATE 
	`#__modules` 
SET 
	`title` = 'Отзывы (Ksenmart)', 
	`position` = 'ks-reviews', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_shop_reviews'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_shop_reviews'), 
	'0'
);