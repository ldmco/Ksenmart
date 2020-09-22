UPDATE 
	`#__extensions` 
SET 
	`enabled` = '1'
WHERE 
	`element` = 'metrika' AND `type` = 'plugin' AND `folder` = 'kmplugins'
;

ALTER TABLE
	`#__ksenmart_products`
ADD
	`metrika_watch` varchar(255) NOT NULL DEFAULT '',
ADD
	`metrika_cart` varchar(255) NOT NULL DEFAULT '',
ADD
	`metrika_spy_price` varchar(255) NOT NULL DEFAULT ''
;

ALTER TABLE
	`#__ksenmart_categories`
ADD
	`metrika_watch` varchar(255) NOT NULL DEFAULT ''
;

ALTER TABLE
	`#__ksenmart_manufacturers`
ADD
	`metrika_watch` varchar(255) NOT NULL DEFAULT ''
;