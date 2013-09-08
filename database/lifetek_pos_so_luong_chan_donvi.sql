SET FOREIGN_KEY_CHECKS = 0;
#
# TABLE STRUCTURE FOR: phppos_app_config
#

CREATE TABLE `phppos_app_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_app_config (`key`, `value`) VALUES ('address', 'Số 12 - Ngõ 379 - Phố Đội Cấn - Phường Liễu Giai - Ba Đình - Hà Nội');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('company', 'LifeTek co.,Ltd');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('currency_symbol', '');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('date_format', 'little_endian');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_1_name', 'GTGT');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_1_rate', '10%');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_2_cumulative', '0');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_2_name', 'Thuế mua hàng 2');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_2_rate', '');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('default_tax_rate', '8');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('email', 'thuclh@lifetek.com.vn');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('fax', '04.3762.1194');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('language', 'vietnam');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('mailchimp_api_key', '');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('number_of_items_per_page', '100');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('phone', '04.3762.1194');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('print_after_sale', 'print_after_sale');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('return_policy', 'LifeTek co.,Ltd');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('time_format', '24_hour');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('timezone', 'Asia/Bangkok');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('track_cash', '0');
INSERT INTO phppos_app_config (`key`, `value`) VALUES ('website', 'http://lifetek.com.vn/');


#
# TABLE STRUCTURE FOR: phppos_app_files
#

CREATE TABLE `phppos_app_files` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_customers
#

CREATE TABLE `phppos_customers` (
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `taxable` int(1) NOT NULL DEFAULT '1',
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_employees
#

CREATE TABLE `phppos_employees` (
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_employees (`username`, `password`, `person_id`, `deleted`) VALUES ('lifetekpos', '25d55ad283aa400af464c76d713c07ad', 1, 0);


#
# TABLE STRUCTURE FOR: phppos_giftcards
#

CREATE TABLE `phppos_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(15,0) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`),
  KEY `deleted` (`deleted`),
  KEY `phppos_giftcards_ibfk_1` (`customer_id`),
  CONSTRAINT `phppos_giftcards_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_inventory
#

CREATE TABLE `phppos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_inventory` decimal(15,0) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`trans_id`),
  KEY `phppos_inventory_ibfk_1` (`trans_items`),
  KEY `phppos_inventory_ibfk_2` (`trans_user`),
  CONSTRAINT `phppos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_item_kit_items
#

CREATE TABLE `phppos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(15,0) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `phppos_item_kit_items_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_item_kits
#

CREATE TABLE `phppos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_kit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `unit_price` decimal(15,0) DEFAULT NULL,
  `cost_price` decimal(15,0) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`),
  UNIQUE KEY `item_kit_number` (`item_kit_number`),
  KEY `name` (`name`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_item_kits_taxes
#

CREATE TABLE `phppos_item_kits_taxes` (
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`,`name`,`percent`),
  CONSTRAINT `phppos_item_kits_taxes_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_items
#

CREATE TABLE `phppos_items` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cost_price` decimal(15,0) NOT NULL,
  `unit_price` decimal(15,0) NOT NULL,
  `quantity` decimal(15,0) NOT NULL DEFAULT '0.00',
  `reorder_level` decimal(15,0) NOT NULL DEFAULT '0.00',
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `phppos_items_ibfk_1` (`supplier_id`),
  KEY `name` (`name`),
  KEY `category` (`category`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_items_taxes
#

CREATE TABLE `phppos_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`,`name`,`percent`),
  CONSTRAINT `phppos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_modules
#

CREATE TABLE `phppos_modules` (
  `name_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(10) NOT NULL,
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_config', 'module_config_desc', 100, 'config');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_customers', 'module_customers_desc', 10, 'customers');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_employees', 'module_employees_desc', 80, 'employees');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_giftcards', 'module_giftcards_desc', 90, 'giftcards');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_item_kits', 'module_item_kits_desc', 30, 'item_kits');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_items', 'module_items_desc', 20, 'items');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_receivings', 'module_receivings_desc', 60, 'receivings');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_reports', 'module_reports_desc', 50, 'reports');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_sales', 'module_sales_desc', 70, 'sales');
INSERT INTO phppos_modules (`name_lang_key`, `desc_lang_key`, `sort`, `module_id`) VALUES ('module_suppliers', 'module_suppliers_desc', 40, 'suppliers');


#
# TABLE STRUCTURE FOR: phppos_people
#

CREATE TABLE `phppos_people` (
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`person_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_people (`first_name`, `last_name`, `phone_number`, `email`, `address_1`, `address_2`, `city`, `state`, `zip`, `country`, `comments`, `person_id`) VALUES ('Lifetek', 'Pos', '04. 37621194', 'thuclh@lifetek.com.vn', 'Số 12 - Ngõ 379 - Phố Đội Cấn - Phường Liễu Giai - Ba Đình - Hà Nội', '', 'Hà Nội', 'Hà Nội', '084', 'Việt Nam', 'Tài khoản quản trị', 1);


#
# TABLE STRUCTURE FOR: phppos_permissions
#

CREATE TABLE `phppos_permissions` (
  `module_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `phppos_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('config', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('customers', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('employees', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('giftcards', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('item_kits', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('items', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('receivings', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('reports', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('sales', 1);
INSERT INTO phppos_permissions (`module_id`, `person_id`) VALUES ('suppliers', 1);


#
# TABLE STRUCTURE FOR: phppos_receivings
#

CREATE TABLE `phppos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_receivings_items
#

CREATE TABLE `phppos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` int(10) NOT NULL DEFAULT '0',
  `item_cost_price` decimal(15,0) NOT NULL,
  `item_unit_price` decimal(15,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings` (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_register_log
#

CREATE TABLE `phppos_register_log` (
  `register_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `employee_id` int(10) NOT NULL,
  `shift_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shift_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `open_amount` double(15,2) NOT NULL,
  `close_amount` double(15,2) NOT NULL,
  `cash_sales_amount` double(15,2) NOT NULL,
  PRIMARY KEY (`register_log_id`),
  KEY `phppos_register_log_ibfk_1` (`employee_id`),
  CONSTRAINT `phppos_register_log_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales
#

CREATE TABLE `phppos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_item_kits
#

CREATE TABLE `phppos_sales_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kit_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,0) NOT NULL DEFAULT '0.00',
  `item_kit_cost_price` decimal(15,0) NOT NULL,
  `item_kit_unit_price` decimal(15,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`),
  KEY `item_kit_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_item_kits_taxes
#

CREATE TABLE `phppos_sales_item_kits_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_item_kits` (`sale_id`),
  CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_items
#

CREATE TABLE `phppos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,0) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,0) NOT NULL,
  `item_unit_price` decimal(15,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_items_taxes
#

CREATE TABLE `phppos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_items` (`sale_id`),
  CONSTRAINT `phppos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_payments
#

CREATE TABLE `phppos_sales_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(15,0) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`),
  CONSTRAINT `phppos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended
#

CREATE TABLE `phppos_sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_sales_suspended_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_sales_suspended_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended_item_kits
#

CREATE TABLE `phppos_sales_suspended_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kit_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,0) NOT NULL DEFAULT '0.00',
  `item_kit_cost_price` decimal(15,0) NOT NULL,
  `item_kit_unit_price` decimal(15,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`),
  KEY `item_kit_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_suspended_item_kits_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`),
  CONSTRAINT `phppos_sales_suspended_item_kits_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_suspended` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended_item_kits_taxes
#

CREATE TABLE `phppos_sales_suspended_item_kits_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_suspended_item_kits_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_suspended_item_kits` (`sale_id`),
  CONSTRAINT `phppos_sales_suspended_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended_items
#

CREATE TABLE `phppos_sales_suspended_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(15,0) NOT NULL DEFAULT '0.00',
  `item_cost_price` decimal(15,0) NOT NULL,
  `item_unit_price` decimal(15,0) NOT NULL,
  `discount_percent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_suspended_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_sales_suspended_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_suspended` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended_items_taxes
#

CREATE TABLE `phppos_sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,0) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_suspended_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_suspended_items` (`sale_id`),
  CONSTRAINT `phppos_sales_suspended_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sales_suspended_payments
#

CREATE TABLE `phppos_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(15,0) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`),
  CONSTRAINT `phppos_sales_suspended_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_suspended` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#
# TABLE STRUCTURE FOR: phppos_sessions
#

CREATE TABLE `phppos_sessions` (
  `session_id` varchar(40) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `ip_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `user_agent` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`session_id`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO phppos_sessions (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES ('013117602ea7d7f4c9e87f22ee62480a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/535.2 (KHTML, like Gecko) Chrome/15.0.874.121 Safari/535.2', 1321643717, 'a:8:{s:9:\"person_id\";s:1:\"1\";s:8:\"cartRecv\";a:0:{}s:9:\"recv_mode\";s:7:\"receive\";s:8:\"supplier\";i:-1;s:4:\"cart\";a:0:{}s:9:\"sale_mode\";s:4:\"sale\";s:8:\"customer\";i:-1;s:8:\"payments\";a:0:{}}');
INSERT INTO phppos_sessions (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES ('63256e1117531ed0e97025f3984a330a', '127.0.0.1', 'Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0', 1321643210, 'a:1:{s:9:\"person_id\";s:1:\"1\";}');


#
# TABLE STRUCTURE FOR: phppos_suppliers
#

CREATE TABLE `phppos_suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


SET FOREIGN_KEY_CHECKS = 1;