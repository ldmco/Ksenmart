DELETE FROM 
	`#__ksen_widgets` 
WHERE 
	`extension` = 'com_ksenmart' AND `name` = 'smmhunter'
;

ALTER TABLE 
	`#__ksenmart_orders`
DROP
	`vk_user_id`
;