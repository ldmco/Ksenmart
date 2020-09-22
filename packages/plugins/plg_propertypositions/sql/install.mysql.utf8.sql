UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'propertypositions' AND `type` = 'plugin' AND `folder` = 'kmplugins'
;

ALTER TABLE
	`#__ksenmart_properties`
ADD
	`position`
varchar(30) NOT NULL
;