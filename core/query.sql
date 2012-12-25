ALTER TABLE me_orders ADD order_status VARCHAR(250) NOT NULL
CREATE TABLE me_order_driver (id BIGINT AUTO_INCREMENT NOT NULL, order_id BIGINT NOT NULL, driver_id BIGINT NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB;
ALTER TABLE me_order_driver ADD money VARCHAR(250) DEFAULT NULL, ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL;
ALTER TABLE me_drivers ADD money VARCHAR(250) DEFAULT NULL;
ALTER TABLE me_orders ADD start_time DATETIME DEFAULT NULL;
ALTER TABLE me_drivers ADD moto_no VARCHAR(250) DEFAULT NULL AFTER driver_info;
ALTER TABLE me_drivers CHANGE driver_age driver_age INT DEFAULT NULL;
ALTER TABLE me_orders ADD distance VARCHAR(250) DEFAULT NULL AFTER order_to; 
ALTER TABLE me_orders ADD service_type TINYINT NOT NULL AFTER price;
ALTER TABLE  `me_orders` ADD  `customer_id` BIGINT( 20 ) NOT NULL AFTER  `user_id`
ALTER TABLE  `me_drivers` ADD  `driver_code` VARCHAR( 250 ) NULL AFTER `id`
CREATE TABLE me_money (id BIGINT AUTO_INCREMENT NOT NULL, from_id BIGINT NOT NULL, to_id BIGINT NOT NULL, amount VARCHAR(250) NOT NULL, description TEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB;
ALTER TABLE  `me_money` ADD INDEX (  `from_id` ,  `to_id` ) ;
ALTER TABLE  `me_order_driver` ADD  `driver_money` VARCHAR( 250 ) NULL AFTER  `money`;
ALTER TABLE me_order_driver ADD moex_money VARCHAR(250) DEFAULT NULL AFTER money;
ALTER TABLE me_drivers ADD moex_money VARCHAR(250) NOT NULL, ADD d_money VARCHAR(250) NOT NULL;
ALTER TABLE me_drivers ADD image VARCHAR(255) AFTER position;
ALTER TABLE  `me_drivers` CHANGE  `moex_money`  `moex_money` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE  `me_drivers` CHANGE  `d_money`  `d_money` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE  `me_drivers` ADD  `driver_type` TINYINT NOT NULL AFTER  `driver_code`;
ALTER TABLE  `me_orders` ADD  `order_code` VARCHAR( 250 ) NULL AFTER  `customer_id`;
CREATE TABLE IF NOT EXISTS `me_ordermeta` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=193;
ALTER TABLE  `me_orders` ADD  `receiver_name` VARCHAR( 250 ) NULL AFTER  `service_type` ,
ADD  `receiver_phone` VARCHAR( 250 ) NULL AFTER  `receiver_name` ,
ADD  `receiver_address` VARCHAR( 250 ) NULL AFTER  `recieved_phone`;
ALTER TABLE  `me_orders` ADD  `surcharge` VARCHAR( 250 ) NULL AFTER  `price` ,
ADD  `promotion` VARCHAR( 250 ) NULL AFTER  `surcharge`;
ALTER TABLE  `me_orders` ADD  `extra_price` VARCHAR( 250 ) NULL AFTER  `price`;
ALTER TABLE  `me_orders` ADD  `order_time` DATETIME NULL AFTER  `order_code`;
ALTER TABLE  `me_orders` ADD  `sender_address` VARCHAR( 255 ) NULL AFTER  `receiver_address`;
ALTER TABLE  `me_orders` ADD  `road_price` VARCHAR( 250 ) NOT NULL DEFAULT  '0' AFTER  `price`;
ALTER TABLE  `me_orders` CHANGE  `order_from`  `order_from` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE  `me_orders` CHANGE  `order_to`  `order_to` VARCHAR( 250 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;
ALTER TABLE  `me_orders` ADD  `thereturn` TINYINT NULL AFTER  `sender_address`;
ALTER TABLE  `me_order_driver` ADD  `road_money` VARCHAR( 250 ) NULL AFTER  `driver_money`
