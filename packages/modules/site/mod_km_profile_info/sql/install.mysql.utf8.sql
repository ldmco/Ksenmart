UPDATE 
	`#__modules` 
SET 
	`title` = 'Меню пользователя (Ksenmart)', 
	`position` = 'ks-profile', 
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