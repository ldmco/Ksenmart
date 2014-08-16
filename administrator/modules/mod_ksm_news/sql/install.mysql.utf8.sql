UPDATE 
	`#__modules` 
SET 
	`title` = 'Новости KsenMart', 
	`position` = 'km-list-left', 
	`published` = '1',
	`ordering` = '3',
	`params` = '{"views":["catalog"]}'
WHERE 
	`module` = 'mod_ksm_news'
;
INSERT INTO 
	`#__modules_menu` 
	(
		`moduleid`, 
		`menuid`
	) 
VALUES (
	(SELECT `id` FROM `#__modules` WHERE `module` = 'mod_ksm_news'), 
	'0'
);