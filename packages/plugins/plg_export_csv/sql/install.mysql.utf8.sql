UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'export_csv' AND `type` = 'plugin' AND `folder` = 'kmexportimport'
;