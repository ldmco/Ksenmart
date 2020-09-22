UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'cbrf' AND `type` = 'plugin' AND `folder` = 'kmplugins'
;

ALTER TABLE
	`#__ksenmart_currencies`
ADD 
	`cbrf_update`
DATE NOT NULL
;