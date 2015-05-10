UPDATE 
	`#__modules` 
SET 
	`title` = 'Фильтры (Ksenmart)', 
	`position` = 'ks-filters', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_filter'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_filter'), 
	'0'
);