ALTER TABLE 
	`#__ksenmart_products` 
ADD 
	`vk_user_id` 
int(11) not null;

ALTER TABLE 
	`#__ksenmart_categories` 
ADD 
	`vk_user_id` 
int(11) not null;


UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'import_ym' AND `type` = 'plugin' AND `folder` = 'kmexportimport'
;