UPDATE 
	`#__modules` 
SET 
	`title` = 'Контакты (Ksenmart)', 
	`position` = 'ks-contacts', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_contacts'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_contacts'), 
	'0'
);