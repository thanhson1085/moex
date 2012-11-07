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
