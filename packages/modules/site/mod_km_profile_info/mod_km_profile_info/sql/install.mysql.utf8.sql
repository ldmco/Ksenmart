UPDATE 
	`#__modules` 
SET 
	`title` = 'Профиль', 
	`position` = 'left', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_profile_info'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_profile_info'), 
	'0'
);