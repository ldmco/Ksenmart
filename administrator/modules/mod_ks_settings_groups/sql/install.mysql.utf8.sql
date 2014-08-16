UPDATE 
	`#__modules` 
SET 
	`title` = 'Группы настроек Ksen', 
	`position` = 'ks-list-left', 
	`published` = 1,
	`params` = '{"views":["settings"], "views_ksg":["settings"]}'
WHERE 
	`module` = 'mod_ks_settings_groups'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ks_settings_groups'), 
	'0'
);