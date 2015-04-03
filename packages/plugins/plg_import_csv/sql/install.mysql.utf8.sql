UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'import_csv' AND `type` = 'plugin' AND `folder` = 'kmexportimport'
;