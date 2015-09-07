UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'callbackkiller' AND `type` = 'plugin' AND `folder` = 'system'
;

INSERT INTO 
	`#__ksen_widgets` (`id`, `extension`, `parent_id`, `group`, `class`, `href`, `image`, `name`, `view`) 
VALUES
	(NULL, 'com_ksenmart', 0, 4, 'main', 'index.php?option=com_ksenmart&view=callbackkiller', 'callbackkiller.png', 'callbackkiller', 'callbackkiller')
;
