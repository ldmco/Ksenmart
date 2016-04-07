ALTER TABLE  `#__ksenmart_payments` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE  `#__ksenmart_payments` CHANGE  `title`  `title` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `introcontent`  `introcontent` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `description`  `description` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `type`  `type` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `regions`  `regions` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
CHANGE  `params`  `params` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ;