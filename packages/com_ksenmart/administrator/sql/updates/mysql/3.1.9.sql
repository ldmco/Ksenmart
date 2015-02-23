ALTER TABLE `#__ksenmart_payments` ADD `introcontent` TEXT NOT NULL AFTER `title`;
ALTER TABLE `#__ksenmart_shippings` ADD `introcontent` TEXT NOT NULL AFTER `title`;
INSERT INTO `#__ksenmart_exportimport_types` (`name`) VALUES ('export_to_csv');