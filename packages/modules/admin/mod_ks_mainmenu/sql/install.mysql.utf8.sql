UPDATE 
	`#__modules` 
SET 
	`title` = 'Главное меню Ksen', 
	`position` = 'ks-top-bottom', 
	`published` = 1
WHERE 
	`module` = 'mod_ks_mainmenu'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ks_mainmenu'), 
	'0'
);