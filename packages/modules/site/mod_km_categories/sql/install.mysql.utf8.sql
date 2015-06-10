UPDATE 
	`#__modules` 
SET 
	`title` = 'Категории (Ksenmart)', 
	`position` = 'ks-categories', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_categories'
AND
	`client_id`=0
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_categories' AND `client_id`=0 LIMIT 1), 
	'0'
);