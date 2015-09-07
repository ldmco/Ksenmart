UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'smmhunter' AND `type` = 'plugin' AND `folder` = 'system'
;

INSERT INTO 
	`#__ksen_widgets` (`id`, `extension`, `parent_id`, `group`, `class`, `href`, `image`, `name`, `view`) 
VALUES
	(NULL, 'com_ksenmart', 0, 4, 'main', 'index.php?option=com_ksenmart&view=smmhunter', 'smmhunter.png', 'smmhunter', 'smmhunter')
;

ALTER TABLE  
	`#__ksenmart_orders`
ADD
	`vk_user_id`
VARCHAR( 256 ) NOT NULL
;