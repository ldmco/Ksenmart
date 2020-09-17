ALTER TABLE `#__ksenmart_products` ADD `weight` FLOAT NOT NULL;
ALTER TABLE `#__ksenmart_products` ADD `length` FLOAT NOT NULL;
ALTER TABLE `#__ksenmart_products` ADD `width` FLOAT NOT NULL;
ALTER TABLE `#__ksenmart_products` ADD `height` FLOAT NOT NULL;
INSERT INTO `#__ksen_billing_data` (`id`, `disabled`, `type`, `extension`) VALUES
(13, 1, 'shipping', 'b2cpl');