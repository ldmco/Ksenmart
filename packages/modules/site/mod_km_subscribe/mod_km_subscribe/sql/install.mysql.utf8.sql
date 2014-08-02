UPDATE 
	`#__modules` 
SET 
	`title` = 'Подписка', 
	`position` = 'content_bottom', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_subscribe'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_subscribe'), 
	'0'
);