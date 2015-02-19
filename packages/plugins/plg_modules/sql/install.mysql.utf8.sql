UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'modules' AND `type` = 'plugin' AND `folder` = 'kmplugins'
;