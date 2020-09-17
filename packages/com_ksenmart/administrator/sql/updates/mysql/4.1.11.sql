ALTER TABLE `#__ksenmart_order_statuses` ADD `withdraw` int(1) NOT NULL;
INSERT INTO `#__ksenmart_order_statuses` (`id`, `title`, `system`, `withdraw`) VALUES
(6, 'order_confirmed', 1, 1);