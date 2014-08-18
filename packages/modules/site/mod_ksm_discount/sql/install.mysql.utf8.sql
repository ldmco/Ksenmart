UPDATE 
	`#__modules` 
SET 
	`title` = 'Скидка', 
	`position` = 'content_bottom', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_discount'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_discount'), 
	'0'
);