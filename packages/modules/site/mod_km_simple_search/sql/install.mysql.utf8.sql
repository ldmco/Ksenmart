UPDATE 
	`#__modules` 
SET 
	`title` = 'Поиск по каталогу (Ksenmart)', 
	`position` = 'ks-search', 
	`published` = '1'
WHERE 
	`module` = 'mod_km_simple_search'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_km_simple_search'), 
	'0'
);