UPDATE 
	`#__modules` 
SET 
	`title` = 'Виды сео-настроек KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`params` = '{"views":["seo"]}'
WHERE 
	`module` = 'mod_ks_seo_types'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ks_seo_types'), 
	'0'
);