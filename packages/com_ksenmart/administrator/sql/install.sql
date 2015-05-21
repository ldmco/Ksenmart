CREATE TABLE IF NOT EXISTS `#__ksenmart_files` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) NOT NULL,
  `media_type` varchar(15) NOT NULL,
  `owner_type` varchar(256) NOT NULL,
  `folder` varchar(32) NOT NULL,
  `filename` varchar(256) NOT NULL,
  `mime_type` varchar(32) NOT NULL,
  `title` varchar(256) NOT NULL,
  `ordering` int(10) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner_id` (`owner_id`),
  KEY `media_type` (`media_type`),
  KEY `owner_type` (`owner_type`),
  KEY `folder` (`folder`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `childs_title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `introcontent` text NOT NULL,
  `published` int(2) NOT NULL DEFAULT '1',
  `hits` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `name` varchar(256) NOT NULL,
  `comment` text NOT NULL,
  `good` text NOT NULL,
  `bad` text NOT NULL,
  `rate` float NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` varchar(25) NOT NULL DEFAULT 'review',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_comment_rates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_comment_rates_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `value` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`rate_id`,`comment_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_countries` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `code` varchar(10) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `introcontent` text NOT NULL,
  `published` int(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `#__ksenmart_countries` (`id`, `title`, `code`, `alias`, `content`, `introcontent`, `published`, `ordering`, `metatitle`, `metadescription`, `metakeywords`) VALUES
(1, 'Россия', 'RU', 'rossiya', '', '', 1, 0, '', '', '');

CREATE TABLE IF NOT EXISTS `#__ksenmart_currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `code` varchar(10) NOT NULL,
  `template` varchar(255) NOT NULL,
  `separator` varchar(1) NOT NULL,
  `fractional` int(11) NOT NULL,
  `default` int(11) NOT NULL,
  `rate` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `#__ksenmart_currencies` (`id`, `title`, `code`, `template`, `separator`, `fractional`, `default`, `rate`) VALUES
(1, 'Рубли', 'RUR', '{price} р.', ' ', 0, 1, 1),
(2, 'Доллары', 'USD', '{price} $', ' ', 0, 0, 0.0322),
(3, 'Евро', 'EUR', '{price} евро', ' ', 0, 0, 0.0221);

CREATE TABLE IF NOT EXISTS `#__ksenmart_discounts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `sum` int(1) NOT NULL,
  `enabled` int(1) NOT NULL,
  `categories` text NOT NULL,
  `manufacturers` text NOT NULL,
  `regions` text NOT NULL,
  `user_groups` text NOT NULL,
  `user_actions` text NOT NULL,
  `actions_limit` int(1) NOT NULL,
  `params` text NOT NULL,
  `info_methods` text NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_discount_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL DEFAULT '',
  `published` int(2) NOT NULL DEFAULT '1',
  `used` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_manufacturers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `alias` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `introcontent` text NOT NULL,
  `country` int(11) NOT NULL,
  `published` int(4) NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_orders` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cost` float NOT NULL,
  `discounts` text NOT NULL,
  `user_id` int(10) NOT NULL,
  `region_id` int(10) NOT NULL,
  `shipping_id` int(10) NOT NULL,
  `shipping_coords` varchar(256) NOT NULL,
  `customer_fields` text NOT NULL,
  `address_fields` text NOT NULL,
  `payment_id` int(10) NOT NULL,
  `note` text NOT NULL,
  `admin_note` text NOT NULL,
  `status_id` int(10) NOT NULL,
  `date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_order_items` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL,
  `product_id` int(10) NOT NULL,
  `basic_price` float NOT NULL,
  `price` float NOT NULL,
  `count` double NOT NULL,
  `properties` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_order_statuses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `system` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

INSERT INTO `#__ksenmart_order_statuses` (`id`, `title`, `system`) VALUES
(1, 'order_new', 1),
(2, 'order_new_unconfirmed', 1),
(3, 'order_done', 1),
(4, 'order_canceled', 1),
(5, 'order_paid', 1);

CREATE TABLE IF NOT EXISTS `#__ksenmart_payments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `introcontent` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(256) NOT NULL,
  `regions` text NOT NULL,
  `params` text NOT NULL,
  `ordering` int(10) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `childs_group` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) NOT NULL DEFAULT '',
  `price` double NOT NULL,
  `old_price` double NOT NULL,
  `purchase_price` DOUBLE NOT NULL,
  `price_type` int(10) NOT NULL,
  `content` text NOT NULL,
  `introcontent` text NOT NULL,
  `product_code` varchar(256) NOT NULL,
  `in_stock` int(10) NOT NULL,
  `product_unit` int(11) NOT NULL,
  `product_packaging` decimal(10,4) NOT NULL,
  `manufacturer` int(11) NOT NULL,
  `promotion` int(1) NOT NULL,
  `recommendation` int(1) NOT NULL,
  `hot` int(1) NOT NULL,
  `new` int(1) NOT NULL,
  `published` int(2) NOT NULL DEFAULT '1',
  `hits` int(11) NOT NULL,
  `carted` int(10) NOT NULL,
  `ordering` int(11) NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  `date_added` datetime NOT NULL,
  `is_parent` int(1) NOT NULL,
  `type` varchar(10) NOT NULL,
  `tag` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `introcontent` (`introcontent`),
  FULLTEXT KEY `product_code` (`product_code`),
  FULLTEXT KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_products_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_default` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`category_id`,`is_default`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_products_child_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_products_relations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `relative_id` int(11) NOT NULL,
  `relation_type` enum('set','relation') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `relative_id` (`relative_id`),
  KEY `relation_type` (`relation_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_product_categories_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_product_properties_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `value_id` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`,`property_id`,`value_id`,`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_product_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `form1` varchar(255) NOT NULL,
  `form2` varchar(255) NOT NULL,
  `form5` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

INSERT INTO `#__ksenmart_product_units` (`id`, `form1`, `form2`, `form5`) VALUES
(3, 'штука', 'штуки', 'штук'),
(5, 'фишка', 'фишки', 'фишек'),
(6, 'тонна', 'тонны', 'тоннов');

CREATE TABLE IF NOT EXISTS `#__ksenmart_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(256) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(256) NOT NULL,
  `view` varchar(256) NOT NULL,
  `default` varchar(256) NOT NULL,
  `prefix` varchar(256) NOT NULL,
  `suffix` varchar(256) NOT NULL,
  `edit_price` int(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_property_values` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `alias` varchar(256) NOT NULL,
  `property_id` int(10) NOT NULL,
  `title` varchar(256) NOT NULL,
  `image` varchar(256) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_regions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) CHARACTER SET utf8 NOT NULL,
  `country_id` int(10) NOT NULL,
  `ordering` int(10) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

INSERT INTO `#__ksenmart_regions` (`id`, `title`, `country_id`, `ordering`, `published`) VALUES
(1, 'Москва', 1, 1, 1),
(35, 'Московская область', 1, 2, 1),
(5, 'Санкт-Петербург', 1, 3, 1),
(25, 'Алтайский край', 1, 5, 1),
(9, 'Амурская область', 1, 6, 1),
(57, 'Архангельская область', 1, 7, 1),
(29, 'Астраханская область', 1, 8, 1),
(13, 'Белгородская область', 1, 9, 1),
(10, 'Брянская область', 1, 10, 1),
(55, 'Владимирская область', 1, 11, 1),
(21, 'Волгоградская область', 1, 12, 1),
(18, 'Вологодская область', 1, 13, 1),
(30, 'Воронежская область', 1, 14, 1),
(82, 'Еврейская автономная область', 1, 15, 1),
(58, 'Ивановская область', 1, 16, 1),
(34, 'Иркутская область', 1, 17, 1),
(49, 'Калининградская область', 1, 18, 1),
(37, 'Калужская область', 1, 19, 1),
(62, 'Камчатская область', 1, 20, 1),
(53, 'Кемеровская область', 1, 21, 1),
(22, 'Кировская область', 1, 22, 1),
(71, 'Костромская область', 1, 23, 1),
(6, 'Краснодарский край', 1, 24, 1),
(46, 'Красноярский край', 1, 25, 1),
(63, 'Курганская область', 1, 26, 1),
(12, 'Курская область', 1, 27, 1),
(75, 'Ленинградская область', 1, 28, 1),
(69, 'Липецкая область', 1, 29, 1),
(2, 'Магаданская область', 1, 30, 1),
(17, 'Мурманская область', 1, 33, 1),
(59, 'Нижегородская область', 1, 34, 1),
(73, 'Новгородская область', 1, 35, 1),
(47, 'Новосибирская область', 1, 36, 1),
(70, 'Омская область', 1, 37, 1),
(36, 'Оренбургская область', 1, 38, 1),
(11, 'Орловская область', 1, 39, 1),
(20, 'Пензенская область', 1, 40, 1),
(7, 'Пермская область', 1, 41, 1),
(41, 'Приморский край', 1, 42, 1),
(14, 'Псковская область', 1, 43, 1),
(56, 'Республика Адыгея', 1, 44, 1),
(61, 'Республика Алтай', 1, 45, 1),
(33, 'Республика Башкортостан', 1, 46, 1),
(24, 'Республика Бурятия', 1, 47, 1),
(28, 'Республика Дагестан', 1, 48, 1),
(79, 'Республика Ингушетия', 1, 49, 1),
(65, 'Республика Кабардино-Балкария', 1, 50, 1),
(66, 'Республика Калмыкия', 1, 51, 1),
(51, 'Республика Карачаево-Черкессия', 1, 52, 1),
(76, 'Республика Карелия', 1, 53, 1),
(67, 'Республика Коми', 1, 54, 1),
(68, 'Республика Марий-Эл', 1, 55, 1),
(16, 'Республика Мордовия', 1, 56, 1),
(4, 'Республика Саха (Якутия)', 1, 57, 1),
(72, 'Республика Северная Осетия (Алания)', 1, 58, 1),
(23, 'Республика Татарстан', 1, 59, 1),
(80, 'Республика Тыва (Тува)', 1, 60, 1),
(64, 'Республика Удмуртия', 1, 61, 1),
(15, 'Республика Хакасия', 1, 62, 1),
(81, 'Республика Чечня', 1, 63, 1),
(54, 'Республика Чувашия', 1, 64, 1),
(27, 'Ростовская область', 1, 65, 1),
(40, 'Рязанская область', 1, 66, 1),
(26, 'Самарская область', 1, 67, 1),
(45, 'Саратовская область', 1, 69, 1),
(78, 'Сахалинская область', 1, 70, 1),
(43, 'Свердловская область', 1, 71, 1),
(48, 'Смоленская область', 1, 72, 1),
(31, 'Ставропольский край', 1, 73, 1),
(32, 'Тамбовская область', 1, 74, 1),
(52, 'Тверская область', 1, 75, 1),
(50, 'Томская область', 1, 76, 1),
(60, 'Тульская область', 1, 77, 1),
(44, 'Тюменская область', 1, 78, 1),
(19, 'Ульяновская область', 1, 79, 1),
(38, 'Хабаровский край', 1, 80, 1),
(42, 'Ханты-Мансийский автономный округ', 1, 81, 1),
(39, 'Челябинская область', 1, 82, 1),
(8, 'Читинская область', 1, 83, 1),
(77, 'Чукотский автономный округ', 1, 84, 1),
(74, 'Ямало-Ненецкий автономный округ', 1, 85, 1),
(3, 'Ярославская область', 1, 86, 1);

CREATE TABLE IF NOT EXISTS `#__ksenmart_reports` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__ksenmart_reports` (`id`, `name`) VALUES
(1, 'productsReport'),
(2, 'ordersReport'),
(3, 'watchedReport'),
(4, 'favoritesReport');

CREATE TABLE IF NOT EXISTS `#__ksenmart_rights` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `#__ksenmart_rights` (`id`, `title`) VALUES
(1, 'right_add_product');

CREATE TABLE IF NOT EXISTS `#__ksenmart_searches_query` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `hit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_shippings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `introcontent` text NOT NULL,
  `type` varchar(256) NOT NULL,
  `regions` text NOT NULL,
  `days` int(10) NOT NULL,
  `params` text NOT NULL,
  `ordering` int(10) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_shipping_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_id` int(11) NOT NULL,
  `position` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `title` varchar(256) NOT NULL,
  `required` int(1) NOT NULL,
  `system` int(1) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__ksenmart_shipping_fields` (`id`, `shipping_id`, `position`, `type`, `title`, `required`, `system`, `ordering`, `published`) VALUES
(1, 0, 'customer', 'text', 'name', 0, 1, 1, 1),
(2, 0, 'customer', 'text', 'phone', 0, 1, 2, 1),
(3, 0, 'customer', 'text', 'email', 0, 1, 3, 1);

CREATE TABLE IF NOT EXISTS `#__ksenmart_shipping_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `#__ksen_widgets` (`id`, `extension`, `parent_id`, `group`, `class`, `href`, `image`, `name`, `view`) VALUES
(1, 'com_ksenmart', 0, 1, 'double,main', 'index.php?option=com_ksenmart&view=orders', 'orders.png', 'orders', 'orders'),
(2, 'com_ksenmart', 0, 1, 'main', 'index.php?option=com_ksenmart&view=catalog', 'products.png', 'catalog', 'catalog'),
(3, 'com_ksenmart', 2, 1, 'main', 'index.php?option=com_ksenmart&view=properties', 'properties.png', 'properties', 'properties'),
(4, 'com_ksenmart', 0, 2, 'main', 'index.php?option=com_ksenmart&view=discounts', 'sales.png', 'discounts', 'discounts'),
(5, 'com_ksenmart', 0, 2, 'main', 'index.php?option=com_ksenmart&view=reports', 'reports.png', 'reports', 'reports'),
(6, 'com_ksenmart', 0, 2, 'main', 'index.php?option=com_ksenmart&view=currencies', 'currencies.png', 'currencies', 'currencies'),
(7, 'com_ksenmart', 2, 1, 'main', 'index.php?option=com_ksenmart&view=exportimport', 'exportimport.png', 'exportimport', 'exportimport'),
(8, 'com_ksenmart', 0, 2, 'main', 'index.php?option=com_ksenmart&view=payments', 'payments.png', 'payments', 'payments'),
(9, 'com_ksenmart', 0, 3, 'sub', 'index.php?option=com_ksen&view=settings&extension=com_ksenmart', 'settings.png', 'settings', 'settings'),
(10, 'com_ksenmart', 0, 3, 'sub', 'index.php?option=com_ksen&view=seo&extension=com_ksenmart', 'seo.png', 'seo', 'seo'),
(11, 'com_ksenmart', 0, 3, 'sub', 'index.php?option=com_ksenmart&view=countries', 'countries.png', 'countries', 'countries'),
(12, 'com_ksenmart', 0, 3, 'sub', 'index.php?option=com_ksenmart&view=comments', 'comments.png', 'comments', 'comments'),
(13, 'com_ksenmart', 0, 2, 'main', 'index.php?option=com_ksenmart&view=shippings', 'shippings.png', 'shippings', 'shippings'),
#(14, 'com_ksenmart', 0, 5, 'sub', 'index.php?option=com_ksen&view=account&layout=vhost&extension=com_ksenmart', 'hardware.png', 'vhost', 'account'),
#(15, 'com_ksenmart', 23, 5, 'sub', 'index.php?option=com_ksen&view=account&layout=domains&extension=com_ksenmart', 'domain.png', 'domains', 'account'),
#(16, 'com_ksenmart', 0, 5, 'sub', 'index.php?option=com_ksen&view=account&layout=tickets_list&extension=com_ksenmart', 'support.png', 'tickets', 'account'),
(17, 'com_ksenmart', 0, 4, 'sub', 'index.php?option=com_ksen&view=users&extension=com_ksenmart', 'users.png', 'users', 'users');

INSERT INTO `#__ksen_widgets_types` (`id`, `extension`, `name`, `published`) VALUES
(1, 'com_ksenmart', 'trade', 1),
(2, 'com_ksenmart', 'marketing', 1);

INSERT INTO `#__ksen_widgets_types_values` (`id`, `widget_id`, `type_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 13, 1),
(4, 6, 1),
(5, 7, 1),
(6, 8, 1),
(7, 5, 1),
(8, 17, 1),
(9, 17, 2),
(10, 5, 2),
(11, 4, 2);

CREATE TABLE IF NOT EXISTS `#__ksenmart_yandeximport` (
  `setting` varchar(256) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__ksenmart_yandeximport` (`setting`, `value`) VALUES
('categories', '{}'),
('shopname', ''),
('company', '');

INSERT INTO `#__ksen_seo_config` (`extension`,`part`, `type`, `config`) VALUES
('com_ksenmart','product', 'url', '{"seo-manufacturer":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-country":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-parent-category":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-parent-product":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-product":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','category', 'url', '{"seo-parent-category":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','manufacturer', 'url', '{"seo-country":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','country', 'url', '{"seo-country":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','product', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-properties-and-values","types":["seo-type-properties-and-values","seo-type-tag"]}}'),
('com_ksenmart','category', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-properties","types":["seo-type-properties","seo-type-title"]}}'),
('com_ksenmart','manufacturer', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-title","types":["seo-type-country","seo-type-title"]}}'),
('com_ksenmart','country', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-title","types":["seo-type-title"]}}'),
('com_ksenmart','product', 'title', '{"seo-country":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-parent-category":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-product":{"active":"1","activable":"0","sortable":"1","user":"0"},"seo-product_code":{"active":"0","activable":"1","sortable":"1","user":"0"}}'),
('com_ksenmart','category', 'title', '{"seo-parent-category":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','manufacturer', 'title', '{"seo-country":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"0","sortable":"1","user":"0"}}'),
('com_ksenmart','country', 'title', '{"seo-country":{"active":"1","activable":"0","sortable":"1","user":"0"}}');

INSERT INTO `#__content_types` (`type_id`, `type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) VALUES
(null, 'KsenMart Product Tags', 'com_ksenmart.product', '{"special": {"dbtable": "#__ksenmart_products","key": "id","type": "Products","prefix": "KsenmartTable","config": "array()"}}', '', '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias"}}', '', '');

UPDATE `#__extensions` SET `params`='{"catalog_default_view":"grid","show_products_from_subcategories":"1","show_out_stock":"1","order_process":"0","show_comment_form":"1","site_product_limit":"15","site_use_pagination":"1","parent_products_template":"list","only_auth_buy":"0","use_stock":"0","catalog_mode":"0","full_width":"900","full_height":"900","thumb_width":"200","thumb_height":"200","middle_width":"350","middle_height":"350","manufacturer_width":"240","manufacturer_height":"120","mini_thumb_width":"110","mini_thumb_height":"110","count_result":"5","count_relevants":"3","count_categories":"1","count_manufactured":"1","count_symbol":"400","review_moderation":"0","review_notice":"0","printforms_companyname":"","printforms_companyaddress":"","printforms_companyphone":"","printforms_nds":"","printforms_ceo_name":"","printforms_buh_name":"","printforms_bank_account_number":"","printforms_inn":"","printforms_kpp":"","printforms_bankname":"","printforms_bank_kor_number":"","printforms_bik":"","printforms_ip_name":"","printforms_ip_registration":"","printforms_company_logo":"","printforms_congritulation_message_template":"<p>u0412u0430u0448u0435u043cu0443 u0437u0430u043au0430u0437u0443 u043fu0440u0438u0441u0432u043eu0435u043d u043du043eu043cu0435u0440 %s<\/p><p>u041du0430u0448u0438 u043cu0435u043du0435u0434u0436u0435u0440u044b u0441u0432u044fu0436u0443u0442u0441u044f u0441 u0432u0430u043cu0438 u0432 u0442u0435u0447u0435u043du0438u0438 2 u0447u0430u0441u043eu0432.<\/p><p>u0422u0430u043a u0436u0435 u0432u044b u043cu043eu0436u0435u0442u0435 u0443u0437u043du0430u0442u044c u043e u0441u0442u0430u0442u0443u0441u0435 u0437u0430u043au0430u0437u0430 u043fu043e u0442u0435u043bu0435u0444u043eu043du0443: 8 800 2000 600<\/p>","shop_name":"","shop_email":"","shop_phone":"","include_css":"0","modules_styles":"1","admin_product_limit":"30","calculate_set_price":"0","watermark":"0","watermark_image":"","watermark_type":"0","watermark_valign":"middle","watermark_halign":"center","displace":"1","valign":"middle","halign":"center","background_type":"color","background_file":"","background_color":"ffffff"}' WHERE `name`='ksenmart';