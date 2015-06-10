CREATE TABLE IF NOT EXISTS `#__ksen_ping` (
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__ksen_ping` (`date`) VALUES
('0000-00-00');

DROP TABLE `#__ksen_seo_texts`;

DELETE FROM `#__ksen_seo_types` WHERE `id` = 4;

ALTER TABLE `#__ksenmart_payments` DROP `introcontent`;