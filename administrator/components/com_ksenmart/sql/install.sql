CREATE TABLE IF NOT EXISTS `#__ksenmart_billing_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` blob NOT NULL,
  `sessid` decimal(12,0) NOT NULL,
  `type` varchar(5) NOT NULL,
  PRIMARY KEY (`id`)
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
  `parent` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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

CREATE TABLE IF NOT EXISTS `#__ksenmart_exportimport_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `#__ksenmart_exportimport_types` (`id`, `name`) VALUES
(1, 'import_from_csv'),
(2, 'export_to_yandexmarket');

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
  `customer_fields` varchar(255) NOT NULL DEFAULT '{}',
  `address_fields` varchar(255) NOT NULL DEFAULT '{}',
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
  PRIMARY KEY (`id`)
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

CREATE TABLE IF NOT EXISTS `#__ksenmart_seo_config` (
  `part` varchar(256) NOT NULL,
  `type` varchar(256) NOT NULL,
  `config` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__ksenmart_seo_config` (`part`, `type`, `config`) VALUES
('product', 'url', '{"seo-manufacturer":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-country":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-parent-product":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-product":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('category', 'url', '{"user_value_1364275977":{"active":"1","activable":"1","sortable":"1","user":"1","title":"category"},"seo-category":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('manufacturer', 'url', '{"user_value_1364278764":{"active":"1","activable":"1","sortable":"1","user":"1","title":"manufacturers"},"seo-country":{"active":"0","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('country', 'url', '{"user_value_1364278772":{"active":"1","activable":"1","sortable":"1","user":"1","title":"countries"},"seo-country":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('product', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-properties-and-values","types":["seo-type-properties-and-values","seo-type-tag"]}}'),
('category', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-properties","types":["seo-type-properties","seo-type-title"]}}'),
('manufacturer', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-title","types":["seo-type-country","seo-type-title"]}}'),
('country', 'meta', '{"description":{"flag":"1","type":"seo-type-mini-description","symbols":"455","types":["seo-type-mini-description","seo-type-description"]},"keywords":{"flag":"1","type":"seo-type-title","types":["seo-type-title"]}}'),
('product', 'title', '{"property_2":{"active":"1","activable":"1","sortable":"1","user":"1","title":"\\u0420\\u0430\\u0437\\u043c\\u0435\\u0440"},"property_1":{"active":"1","activable":"1","sortable":"1","user":"1","title":"\\u0426\\u0432\\u0435\\u0442"},"seo-country":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-parent-category":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-category":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-product":{"active":"1","activable":"0","sortable":"1","user":"0"},"seo-product_code":{"active":"0","activable":"1","sortable":"1","user":"0"}}'),
('category', 'title', '{"seo-category":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('manufacturer', 'title', '{"seo-country":{"active":"1","activable":"1","sortable":"1","user":"0"},"seo-manufacturer":{"active":"1","activable":"0","sortable":"0","user":"0"}}'),
('country', 'title', '{"seo-country":{"active":"1","activable":"0","sortable":"0","user":"0"}}');

CREATE TABLE IF NOT EXISTS `#__ksenmart_seo_texts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categories` varchar(256) NOT NULL,
  `manufacturers` varchar(256) NOT NULL,
  `countries` varchar(256) NOT NULL,
  `properties` varchar(256) NOT NULL,
  `text` text NOT NULL,
  `metatitle` varchar(256) NOT NULL,
  `metadescription` text NOT NULL,
  `metakeywords` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_seo_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

INSERT INTO `#__ksenmart_seo_types` (`id`, `title`) VALUES
(1, 'seo-urls-config'),
(2, 'seo-meta-config'),
(3, 'seo-titles-config'),
(4, 'seo-texts-config');

CREATE TABLE IF NOT EXISTS `#__ksenmart_shippings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
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

CREATE TABLE IF NOT EXISTS `#__ksenmart_users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `region_id` int(10) NOT NULL,
  `phone` varchar(256) NOT NULL,
  `watched` text NOT NULL,
  `favorites` text NOT NULL,
  `social` varchar(10) NOT NULL,
  `settings` varchar(255) NOT NULL DEFAULT '{"catalog_layout":"grid"} ',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_user_addresses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL,
  `city` varchar(256) NOT NULL,
  `zip` varchar(256) NOT NULL,
  `street` varchar(256) NOT NULL,
  `house` varchar(256) NOT NULL,
  `floor` varchar(256) NOT NULL,
  `flat` varchar(256) NOT NULL,
  `coords` varchar(256) NOT NULL,
  `default` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_user_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `ordering` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_user_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`,`field_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__ksenmart_widgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `group` int(2) NOT NULL,
  `class` set('double','half','') NOT NULL DEFAULT '',
  `href` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `view` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

INSERT INTO `#__ksenmart_widgets` (`id`, `parent_id`, `group`, `class`, `href`, `image`, `name`, `view`) VALUES
(1, 0, 1, 'double', 'index.php?option=com_ksenmart&view=orders', 'orders.png', 'orders', 'orders'),
(2, 0, 1, '', 'index.php?option=com_ksenmart&view=catalog', 'products.png', 'catalog', 'catalog'),
(3, 2, 1, '', 'index.php?option=com_ksenmart&view=properties', 'properties.png', 'properties', 'properties'),
(4, 0, 2, '', 'index.php?option=com_ksenmart&view=discounts', 'sales.png', 'discounts', 'discounts'),
(5, 0, 2, '', 'index.php?option=com_ksenmart&view=reports', 'reports.png', 'reports', 'reports'),
(6, 0, 2, '', 'index.php?option=com_ksenmart&view=currencies', 'currencies.png', 'currencies', 'currencies'),
(7, 2, 1, '', 'index.php?option=com_ksenmart&view=exportimport', 'module.png', 'exportimport', 'exportimport'),
(8, 0, 2, '', 'index.php?option=com_ksenmart&view=payments', 'payments.png', 'payments', 'payments'),
(9, 0, 3, '', 'index.php?option=com_ksenmart&view=allsettings', 'settings.png', 'allsettings', 'allsettings'),
(10, 0, 3, '', 'index.php?option=com_ksenmart&view=seo', 'seo.png', 'seo', 'seo'),
(11, 0, 3, '', 'index.php?option=com_ksenmart&view=countries', 'module.png', 'countries', 'countries'),
(12, 0, 3, '', 'index.php?option=com_ksenmart&view=comments', 'comments.png', 'comments', 'comments'),
(13, 0, 2, '', 'index.php?option=com_ksenmart&view=shippings', 'shippings.png', 'shippings', 'shippings'),
(14, 0, 5, '', 'index.php?option=com_ksenmart&view=account&layout=vhost', 'hardware.png', 'vhost', 'account'),
(15, 23, 5, '', 'index.php?option=com_ksenmart&view=account&layout=domains', 'domain.png', 'domains', 'account'),
(16, 0, 5, '', 'index.php?option=com_ksenmart&view=account&layout=tickets_list', 'support.png', 'tickets', 'account'),
(17, 0, 4, '', 'index.php?option=com_ksenmart&view=users', 'users.png', 'users', 'users');

CREATE TABLE IF NOT EXISTS `#__ksenmart_widgets_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

INSERT INTO `#__ksenmart_widgets_types` (`id`, `name`, `published`) VALUES
(1, 'trade', 1),
(2, 'marketing', 1);

CREATE TABLE IF NOT EXISTS `#__ksenmart_widgets_types_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `widget_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

INSERT INTO `#__ksenmart_widgets_types_values` (`id`, `widget_id`, `type_id`) VALUES
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

CREATE TABLE IF NOT EXISTS `#__ksenmart_widgets_users_config` (
  `user_id` int(11) NOT NULL,
  `config_all` text NOT NULL,
  `config_trade` text NOT NULL,
  `config_marketing` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__ksenmart_yandeximport` (
  `setting` varchar(256) NOT NULL,
  `value` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `#__ksenmart_yandeximport` (`setting`, `value`) VALUES
('categories', '{}'),
('shopname', ''),
('company', '');