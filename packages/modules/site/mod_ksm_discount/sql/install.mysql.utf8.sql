UPDATE 
	`#__modules` 
SET 
	`title` = 'Информация о скидках (Ksenmart)', 
	`position` = 'ks-discount', 
	`published` = '1'
WHERE 
	`module` = 'mod_ksm_discount'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ksm_discount'), 
	'0'
);