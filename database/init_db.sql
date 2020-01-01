/*
 Navicat Premium Data Transfer

 Source Server         : chhleuy.com_3306
 Source Server Type    : MySQL
 Source Server Version : 100225
 Source Host           : chhleuy.com:3306
 Source Schema         : chhleuyc_pos

 Target Server Type    : MySQL
 Target Server Version : 100225
 File Encoding         : 65001

 Date: 29/12/2019 11:59:01
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions`  (
  `id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ci_sessions_timestamp`(`timestamp`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_accounts
-- ----------------------------
DROP TABLE IF EXISTS `geopos_accounts`;
CREATE TABLE `geopos_accounts`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `acn` varchar(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `holder` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `adate` datetime(0) NOT NULL,
  `lastbal` decimal(16, 2) NULL DEFAULT 0,
  `code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `loc` int(4) NULL DEFAULT NULL,
  `account_type` enum('Assets','Expenses','Income','Liabilities','Equity','Basic') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Basic',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `acn`(`acn`) USING BTREE,
  INDEX `id`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_accounts
-- ----------------------------
INSERT INTO `geopos_accounts` VALUES (1, '000001', 'Cash', '2018-01-01 00:00:00', 0.00, 'Default Sales Account', 0, 'Basic');

-- ----------------------------
-- Table structure for geopos_attendance
-- ----------------------------
DROP TABLE IF EXISTS `geopos_attendance`;
CREATE TABLE `geopos_attendance`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp` int(11) NOT NULL,
  `created` datetime(0) NOT NULL DEFAULT current_timestamp,
  `adate` date NOT NULL,
  `tfrom` time(0) NOT NULL,
  `tto` time(0) NOT NULL,
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `actual_hours` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `emp`(`emp`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_bank_ac
-- ----------------------------
DROP TABLE IF EXISTS `geopos_bank_ac`;
CREATE TABLE `geopos_bank_ac`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `bank` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `acn` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `branch` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `enable` enum('Yes','No') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_config
-- ----------------------------
DROP TABLE IF EXISTS `geopos_config`;
CREATE TABLE `geopos_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL,
  `val1` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `val2` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `val3` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `val4` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rid` int(11) NOT NULL,
  `other` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_currencies
-- ----------------------------
DROP TABLE IF EXISTS `geopos_currencies`;
CREATE TABLE `geopos_currencies`  (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `code` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `symbol` varchar(3) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `rate` decimal(10, 2) NOT NULL,
  `thous` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `dpoint` char(1) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `decim` int(2) NOT NULL,
  `cpos` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_currencies
-- ----------------------------
INSERT INTO `geopos_currencies` VALUES (1, '000', '៛', 4000.00, ',', '.', 0, 1);

-- ----------------------------
-- Table structure for geopos_cust_group
-- ----------------------------
DROP TABLE IF EXISTS `geopos_cust_group`;
CREATE TABLE `geopos_cust_group`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `summary` varchar(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `disc_rate` decimal(9, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_cust_group
-- ----------------------------
INSERT INTO `geopos_cust_group` VALUES (1, 'Default Group', 'Default Group', NULL);

-- ----------------------------
-- Table structure for geopos_custom_data
-- ----------------------------
DROP TABLE IF EXISTS `geopos_custom_data`;
CREATE TABLE `geopos_custom_data`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_id` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `module` int(3) NOT NULL,
  `data` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fid`(`field_id`, `rid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_custom_fields
-- ----------------------------
DROP TABLE IF EXISTS `geopos_custom_fields`;
CREATE TABLE `geopos_custom_fields`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `f_module` int(3) NOT NULL,
  `f_type` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `placeholder` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value_data` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `f_view` int(2) NOT NULL,
  `other` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `f_module`(`f_module`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_customers
-- ----------------------------
DROP TABLE IF EXISTS `geopos_customers`;
CREATE TABLE `geopos_customers`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `postbox` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picture` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'example.png',
  `gid` int(5) NOT NULL DEFAULT 1,
  `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `taxid` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `country_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `postbox_s` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `balance` decimal(16, 2) NULL DEFAULT 0,
  `loc` int(11) NULL DEFAULT 0,
  `docid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `custom1` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `discount_c` decimal(16, 2) NULL DEFAULT NULL,
  `reg_date` datetime(0) NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_customers
-- ----------------------------
INSERT INTO `geopos_customers` VALUES (1, 'Walk-in Client', '0987654321', NULL, NULL, NULL, NULL, NULL, 'client@client.com', 'example.png', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0.00, 0, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for geopos_documents
-- ----------------------------
DROP TABLE IF EXISTS `geopos_documents`;
CREATE TABLE `geopos_documents`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `filename` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cdate` date NOT NULL,
  `permission` int(1) NULL DEFAULT NULL,
  `cid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_draft
-- ----------------------------
DROP TABLE IF EXISTS `geopos_draft`;
CREATE TABLE `geopos_draft`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `invoicedate` date NOT NULL,
  `invoiceduedate` date NOT NULL,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `shipping` decimal(16, 2) NULL DEFAULT 0,
  `ship_tax` decimal(16, 2) NULL DEFAULT NULL,
  `ship_tax_type` enum('incl','excl','off') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'off',
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `total` decimal(16, 2) NULL DEFAULT 0,
  `pmethod` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('paid','due','canceled','partial') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'due',
  `csd` int(5) NOT NULL DEFAULT 0,
  `eid` int(4) NOT NULL,
  `pamnt` decimal(16, 2) NULL DEFAULT 0,
  `items` decimal(10, 2) NOT NULL,
  `taxstatus` enum('yes','no','cgst','igst') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes',
  `discstatus` tinyint(1) NOT NULL,
  `format_discount` enum('%','flat','bflat','b_p') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '%',
  `refer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `term` int(3) NOT NULL,
  `multi` int(4) NULL DEFAULT NULL,
  `i_class` int(1) NOT NULL DEFAULT 0,
  `loc` int(4) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `csd`(`csd`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE,
  INDEX `i_class`(`i_class`) USING BTREE,
  INDEX `loc`(`loc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_draft_items
-- ----------------------------
DROP TABLE IF EXISTS `geopos_draft_items`;
CREATE TABLE `geopos_draft_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` decimal(10, 2) NOT NULL DEFAULT 0,
  `price` decimal(16, 2) NOT NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `totaltax` decimal(16, 2) NULL DEFAULT 0,
  `totaldiscount` decimal(16, 2) NULL DEFAULT 0,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `i_class` int(1) NOT NULL DEFAULT 0,
  `unit` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE,
  INDEX `i_class`(`i_class`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_employees
-- ----------------------------
DROP TABLE IF EXISTS `geopos_employees`;
CREATE TABLE `geopos_employees`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `postbox` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phonealt` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picture` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'example.png',
  `sign` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'sign.png',
  `joindate` datetime(0) NOT NULL DEFAULT current_timestamp,
  `dept` int(11) NULL DEFAULT NULL,
  `degis` int(11) NULL DEFAULT NULL,
  `salary` decimal(16, 2) NULL DEFAULT 0,
  `clock` int(1) NULL DEFAULT NULL,
  `clockin` int(11) NULL DEFAULT NULL,
  `clockout` int(11) NULL DEFAULT NULL,
  `c_rate` decimal(16, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_employees
-- ----------------------------
INSERT INTO `geopos_employees` VALUES (14, 'admin', 'BusinessOwner', 'Test Street', 'Test City', 'Test Region', 'Test Country', '123456', '12345678', '0', 'example.png', 'sign.png', '2019-11-17 15:15:06', NULL, NULL, 0.00, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for geopos_events
-- ----------------------------
DROP TABLE IF EXISTS `geopos_events`;
CREATE TABLE `geopos_events`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '#3a87ad',
  `start` datetime(0) NOT NULL,
  `end` datetime(0) NULL DEFAULT NULL,
  `allDay` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'true',
  `rel` int(2) NOT NULL DEFAULT 0,
  `rid` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `rel`(`rel`) USING BTREE,
  INDEX `rid`(`rid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_gateways
-- ----------------------------
DROP TABLE IF EXISTS `geopos_gateways`;
CREATE TABLE `geopos_gateways`  (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `enable` enum('Yes','No') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `key1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `key2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `currency` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'USD',
  `dev_mode` enum('true','false') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `ord` int(5) NOT NULL,
  `surcharge` decimal(16, 2) NOT NULL,
  `extra` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_gateways
-- ----------------------------
INSERT INTO `geopos_gateways` VALUES (1, 'Stripe', 'Yes', 'sk_test_secratekey', 'stripe_public_key', 'USD', 'true', 1, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (2, 'Authorize.Net', 'Yes', 'TRANSACTIONKEY', 'LOGINID', 'AUD', 'true', 2, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (3, 'Pin Payments', 'Yes', 'TEST', 'none', 'AUD', 'true', 3, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (4, 'PayPal', 'Yes', 'MyPayPalClientId', 'MyPayPalSecret', 'USD', 'true', 4, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (5, 'SecurePay', 'Yes', 'ABC0001', 'abc123', 'AUD', 'true', 5, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (6, '2Checkout', 'Yes', 'Publishable Key', 'Private Key', 'USD', 'true', 6, 0.00, 'seller_id');
INSERT INTO `geopos_gateways` VALUES (7, 'PayU Money', 'Yes', 'MERCHANT_KEY', 'MERCHANT_SALT', 'USD', 'true', 7, 0.00, 'none');
INSERT INTO `geopos_gateways` VALUES (8, 'RazorPay', 'Yes', 'Key Id', 'Key Secret', 'INR', 'true', 8, 0.00, 'none');

-- ----------------------------
-- Table structure for geopos_goals
-- ----------------------------
DROP TABLE IF EXISTS `geopos_goals`;
CREATE TABLE `geopos_goals`  (
  `id` int(1) NOT NULL,
  `income` bigint(20) NOT NULL,
  `expense` bigint(20) NOT NULL,
  `sales` bigint(20) NOT NULL,
  `netincome` bigint(20) NOT NULL
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_goals
-- ----------------------------
INSERT INTO `geopos_goals` VALUES (1, 999999, 999999, 999999, 999999);

-- ----------------------------
-- Table structure for geopos_hrm
-- ----------------------------
DROP TABLE IF EXISTS `geopos_hrm`;
CREATE TABLE `geopos_hrm`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typ` int(2) NOT NULL,
  `rid` int(11) NOT NULL,
  `val1` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `val2` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `val3` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_invoice_items
-- ----------------------------
DROP TABLE IF EXISTS `geopos_invoice_items`;
CREATE TABLE `geopos_invoice_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL DEFAULT 0,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` decimal(10, 2) NOT NULL DEFAULT 0,
  `price` decimal(16, 2) NOT NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `totaltax` decimal(16, 2) NULL DEFAULT 0,
  `totaldiscount` decimal(16, 2) NULL DEFAULT 0,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `i_class` int(1) NOT NULL DEFAULT 0,
  `unit` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `product_unique_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `warehouse_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE,
  INDEX `i_class`(`i_class`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_invoices
-- ----------------------------
DROP TABLE IF EXISTS `geopos_invoices`;
CREATE TABLE `geopos_invoices`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `invoicedate` date NOT NULL,
  `invoiceduedate` date NOT NULL,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `shipping` decimal(16, 2) NULL DEFAULT 0,
  `ship_tax` decimal(16, 2) NULL DEFAULT NULL,
  `ship_tax_type` enum('incl','excl','off') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'off',
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `discount_rate` decimal(10, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `total` decimal(16, 2) NULL DEFAULT 0,
  `pmethod` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('paid','due','canceled','partial') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'due',
  `csd` int(5) NOT NULL DEFAULT 0,
  `eid` int(4) NOT NULL DEFAULT 0,
  `pamnt` decimal(16, 2) NULL DEFAULT 0,
  `items` decimal(10, 2) NOT NULL DEFAULT 0,
  `taxstatus` enum('yes','no','incl','cgst','igst') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'yes',
  `discstatus` tinyint(1) NOT NULL,
  `format_discount` enum('%','flat','b_p','bflat') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '%',
  `refer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `term` int(3) NOT NULL DEFAULT 0,
  `multi` int(4) NULL DEFAULT NULL,
  `i_class` int(1) NOT NULL DEFAULT 0,
  `loc` int(4) NULL DEFAULT NULL,
  `r_time` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `warehouse_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `csd`(`csd`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE,
  INDEX `i_class`(`i_class`) USING BTREE,
  INDEX `loc`(`loc`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_locations
-- ----------------------------
DROP TABLE IF EXISTS `geopos_locations`;
CREATE TABLE `geopos_locations`  (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `cname` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `country` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `postbox` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `taxid` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `logo` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'logo.png',
  `cur` int(4) NOT NULL,
  `ware` int(11) NULL DEFAULT 0,
  `ext` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_log
-- ----------------------------
DROP TABLE IF EXISTS `geopos_log`;
CREATE TABLE `geopos_log`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `note` mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` datetime(0) NOT NULL DEFAULT current_timestamp,
  `user` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 417 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_log
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_login_attempts
-- ----------------------------
DROP TABLE IF EXISTS `geopos_login_attempts`;
CREATE TABLE `geopos_login_attempts`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(39) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT '0',
  `timestamp` datetime(0) NULL DEFAULT NULL,
  `login_attempts` tinyint(2) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 35 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_login_attempts
-- ----------------------------
INSERT INTO `geopos_login_attempts` VALUES (9, '117.20.117.179', '2019-11-30 21:24:45', 1);
INSERT INTO `geopos_login_attempts` VALUES (12, '64.233.173.138', '2019-12-01 01:09:33', 1);

-- ----------------------------
-- Table structure for geopos_metadata
-- ----------------------------
DROP TABLE IF EXISTS `geopos_metadata`;
CREATE TABLE `geopos_metadata`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(3) NOT NULL,
  `rid` int(11) NOT NULL,
  `col1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `col2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `d_date` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `rid`(`rid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_metadata
-- ----------------------------
INSERT INTO `geopos_metadata` VALUES (1, 9, 1001, '0', NULL, '2019-11-27');
INSERT INTO `geopos_metadata` VALUES (3, 9, 7, '5', NULL, '2019-11-27');
INSERT INTO `geopos_metadata` VALUES (4, 9, 8, '71', NULL, '2019-11-27');
INSERT INTO `geopos_metadata` VALUES (6, 9, 10, '95986.5', NULL, '2019-11-30');
INSERT INTO `geopos_metadata` VALUES (12, 9, 3, '112000', NULL, '2019-12-04');

-- ----------------------------
-- Table structure for geopos_milestones
-- ----------------------------
DROP TABLE IF EXISTS `geopos_milestones`;
CREATE TABLE `geopos_milestones`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `exp` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_movers
-- ----------------------------
DROP TABLE IF EXISTS `geopos_movers`;
CREATE TABLE `geopos_movers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `d_type` int(3) NOT NULL,
  `rid1` int(11) NOT NULL,
  `rid2` int(11) NOT NULL,
  `rid3` int(11) NOT NULL,
  `d_time` timestamp(0) NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP(0),
  `note` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `d_type`(`d_type`, `rid1`, `rid2`, `rid3`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 39 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_movers
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_notes
-- ----------------------------
DROP TABLE IF EXISTS `geopos_notes`;
CREATE TABLE `geopos_notes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cdate` date NOT NULL,
  `last_edit` datetime(0) NOT NULL DEFAULT current_timestamp,
  `cid` int(11) NOT NULL DEFAULT 0,
  `fid` int(11) NOT NULL DEFAULT 0,
  `rid` int(11) NOT NULL DEFAULT 0,
  `ntype` int(2) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_pms
-- ----------------------------
DROP TABLE IF EXISTS `geopos_pms`;
CREATE TABLE `geopos_pms`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) UNSIGNED NOT NULL,
  `receiver_id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `date_sent` datetime(0) NULL DEFAULT NULL,
  `date_read` datetime(0) NULL DEFAULT NULL,
  `pm_deleted_sender` int(1) NOT NULL,
  `pm_deleted_receiver` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `full_index`(`id`, `sender_id`, `receiver_id`, `date_read`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_premissions
-- ----------------------------
DROP TABLE IF EXISTS `geopos_premissions`;
CREATE TABLE `geopos_premissions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` enum('Sales','Stock','Crm','Project','Accounts','Miscellaneous','Employees','Assign Project','Customer Profile','Reports','Delete') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `r_1` int(1) NOT NULL,
  `r_2` int(1) NOT NULL,
  `r_3` int(1) NOT NULL,
  `r_4` int(1) NOT NULL,
  `r_5` int(1) NOT NULL,
  `r_6` int(1) NOT NULL,
  `r_7` int(1) NOT NULL,
  `r_8` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_premissions
-- ----------------------------
INSERT INTO `geopos_premissions` VALUES (1, 'Sales', 0, 1, 1, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (2, 'Stock', 1, 0, 1, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (3, 'Crm', 0, 0, 1, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (4, 'Project', 0, 0, 0, 1, 1, 1, 0, 0);
INSERT INTO `geopos_premissions` VALUES (5, 'Accounts', 0, 0, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (6, 'Miscellaneous', 0, 0, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (7, 'Assign Project', 0, 1, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (8, 'Customer Profile', 0, 0, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (9, 'Employees', 0, 0, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (10, 'Reports', 0, 0, 0, 1, 1, 0, 0, 0);
INSERT INTO `geopos_premissions` VALUES (11, 'Delete', 1, 1, 1, 1, 1, 1, 0, 1);

-- ----------------------------
-- Table structure for geopos_product_cat
-- ----------------------------
DROP TABLE IF EXISTS `geopos_product_cat`;
CREATE TABLE `geopos_product_cat`  (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `c_type` int(2) NULL DEFAULT 0,
  `rel_id` int(11) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_product_cat
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_products
-- ----------------------------
DROP TABLE IF EXISTS `geopos_products`;
CREATE TABLE `geopos_products`  (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pcat` int(3) NOT NULL DEFAULT 1,
  `warehouse` int(11) NULL DEFAULT 1,
  `product_name` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `product_code` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `product_price` decimal(16, 2) NULL DEFAULT 0,
  `fproduct_price` decimal(16, 2) NULL DEFAULT 0,
  `taxrate` decimal(16, 2) NULL DEFAULT 0,
  `disrate` decimal(16, 2) NULL DEFAULT 0,
  `qty` decimal(10, 2) NULL DEFAULT NULL,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `alert` int(11) NULL DEFAULT NULL,
  `unit` varchar(4) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `image` varchar(120) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'default.png',
  `barcode` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `merge` int(2) NOT NULL,
  `sub` int(11) NOT NULL,
  `vb` int(11) NOT NULL,
  `expiry` date NULL DEFAULT NULL,
  `code_type` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'EAN13',
  `sub_id` int(11) NULL DEFAULT 0,
  `b_id` int(11) NULL DEFAULT 0,
  `i_stock` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`pid`) USING BTREE,
  INDEX `pcat`(`pcat`) USING BTREE,
  INDEX `warehouse`(`warehouse`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_products
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_project_meta
-- ----------------------------
DROP TABLE IF EXISTS `geopos_project_meta`;
CREATE TABLE `geopos_project_meta`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `meta_key` int(11) NOT NULL,
  `meta_data` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key3` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key4` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `pid`(`pid`) USING BTREE,
  INDEX `meta_key`(`meta_key`) USING BTREE,
  INDEX `key3`(`key3`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_project_meta
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_projects
-- ----------------------------
DROP TABLE IF EXISTS `geopos_projects`;
CREATE TABLE `geopos_projects`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `p_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('Waiting','Pending','Terminated','Finished','Progress') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Pending',
  `priority` enum('Low','Medium','High','Urgent') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Medium',
  `progress` int(3) NOT NULL,
  `cid` int(11) NOT NULL,
  `sdate` date NOT NULL,
  `edate` date NOT NULL,
  `tag` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `phase` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `note` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `worth` decimal(16, 2) NOT NULL DEFAULT 0,
  `ptype` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `p_id`(`p_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_projects
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_promo
-- ----------------------------
DROP TABLE IF EXISTS `geopos_promo`;
CREATE TABLE `geopos_promo`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `amount` decimal(10, 2) NOT NULL,
  `valid` date NOT NULL,
  `active` int(1) NOT NULL,
  `note` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `reflect` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `available` int(11) NOT NULL,
  `location` int(1) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `code_2`(`code`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_purchase
-- ----------------------------
DROP TABLE IF EXISTS `geopos_purchase`;
CREATE TABLE `geopos_purchase`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `invoicedate` date NOT NULL,
  `invoiceduedate` date NOT NULL,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `shipping` decimal(16, 2) NULL DEFAULT 0,
  `ship_tax` decimal(16, 2) NULL DEFAULT NULL,
  `ship_tax_type` enum('incl','excl','off') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'off',
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `total` decimal(16, 2) NULL DEFAULT 0,
  `pmethod` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('paid','due','canceled','partial') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'due',
  `csd` int(5) NULL DEFAULT 0,
  `eid` int(4) NOT NULL,
  `pamnt` decimal(16, 2) NULL DEFAULT 0,
  `items` decimal(10, 2) NOT NULL,
  `taxstatus` enum('yes','no','incl','cgst','igst') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'yes',
  `discstatus` tinyint(1) NOT NULL,
  `format_discount` enum('%','flat','b_p','bflat') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `refer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `term` int(3) NOT NULL,
  `loc` int(4) NOT NULL,
  `multi` int(11) NULL DEFAULT NULL,
  `warehouse_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoice`(`tid`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `csd`(`csd`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_purchase
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_purchase_items
-- ----------------------------
DROP TABLE IF EXISTS `geopos_purchase_items`;
CREATE TABLE `geopos_purchase_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` decimal(10, 2) NOT NULL,
  `price` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `totaltax` decimal(16, 2) NULL DEFAULT 0,
  `totaldiscount` decimal(16, 2) NULL DEFAULT 0,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `unit` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `product_unique_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `warehouse_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_purchase_items
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_quotes
-- ----------------------------
DROP TABLE IF EXISTS `geopos_quotes`;
CREATE TABLE `geopos_quotes`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `invoicedate` date NOT NULL,
  `invoiceduedate` date NOT NULL,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `shipping` decimal(16, 2) NULL DEFAULT 0,
  `ship_tax` decimal(16, 2) NULL DEFAULT NULL,
  `ship_tax_type` enum('incl','excl','off') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'off',
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `total` decimal(16, 2) NULL DEFAULT 0,
  `pmethod` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('pending','accepted','rejected') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'pending',
  `csd` int(5) NOT NULL DEFAULT 0,
  `eid` int(4) NOT NULL,
  `pamnt` decimal(16, 2) NOT NULL,
  `items` decimal(10, 2) NOT NULL,
  `taxstatus` enum('yes','no','incl','cgst','igst') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'yes',
  `discstatus` tinyint(1) NOT NULL,
  `format_discount` enum('%','flat','b_p','bflat') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '%',
  `refer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `term` int(3) NOT NULL,
  `proposal` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `multi` int(4) NULL DEFAULT NULL,
  `loc` int(4) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoice`(`tid`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `csd`(`csd`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_quotes_items
-- ----------------------------
DROP TABLE IF EXISTS `geopos_quotes_items`;
CREATE TABLE `geopos_quotes_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` decimal(10, 0) NOT NULL,
  `price` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `totaltax` decimal(16, 2) NULL DEFAULT 0,
  `totaldiscount` decimal(16, 2) NULL DEFAULT 0,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `unit` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_register
-- ----------------------------
DROP TABLE IF EXISTS `geopos_register`;
CREATE TABLE `geopos_register`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `o_date` datetime(0) NOT NULL,
  `c_date` datetime(0) NOT NULL,
  `cash` decimal(16, 2) NOT NULL,
  `card` decimal(16, 2) NOT NULL,
  `bank` decimal(16, 2) NOT NULL,
  `cheque` decimal(16, 2) NOT NULL,
  `r_change` decimal(16, 2) NOT NULL,
  `active` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `active`(`active`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_reports
-- ----------------------------
DROP TABLE IF EXISTS `geopos_reports`;
CREATE TABLE `geopos_reports`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `year` int(4) NOT NULL,
  `invoices` int(11) NOT NULL,
  `sales` decimal(16, 2) NULL DEFAULT 0,
  `items` decimal(10, 2) NOT NULL,
  `income` decimal(16, 2) NULL DEFAULT 0,
  `expense` decimal(16, 2) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_reports
-- ----------------------------
INSERT INTO `geopos_reports` VALUES (1, '11', 2019, 3, 117.50, 1.00, NULL, NULL);

-- ----------------------------
-- Table structure for geopos_restkeys
-- ----------------------------
DROP TABLE IF EXISTS `geopos_restkeys`;
CREATE TABLE `geopos_restkeys`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT 0,
  `is_private_key` tinyint(1) NOT NULL DEFAULT 0,
  `ip_addresses` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `date_created` date NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_smtp
-- ----------------------------
DROP TABLE IF EXISTS `geopos_smtp`;
CREATE TABLE `geopos_smtp`  (
  `id` int(11) NOT NULL,
  `host` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `port` int(11) NOT NULL,
  `auth` enum('true','false') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `auth_type` enum('none','tls','ssl') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `sender` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_smtp
-- ----------------------------
INSERT INTO `geopos_smtp` VALUES (1, 'smtp.gmail.com', 587, 'true', 'tls', 'tafu.team@gmail.com', '@aaa', 'tafu.team@gmail.com');

-- ----------------------------
-- Table structure for geopos_stock_r
-- ----------------------------
DROP TABLE IF EXISTS `geopos_stock_r`;
CREATE TABLE `geopos_stock_r`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(8) NOT NULL,
  `invoicedate` date NOT NULL,
  `invoiceduedate` date NOT NULL,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `shipping` decimal(16, 2) NULL DEFAULT 0,
  `ship_tax` decimal(16, 2) NULL DEFAULT NULL,
  `ship_tax_type` enum('incl','excl','off') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'off',
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `total` decimal(16, 2) NULL DEFAULT 0,
  `pmethod` varchar(14) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('pending','accepted','rejected','partial','canceled') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'pending',
  `csd` int(5) NOT NULL DEFAULT 0,
  `eid` int(4) NOT NULL,
  `pamnt` decimal(16, 2) NULL DEFAULT 0,
  `items` decimal(10, 0) NOT NULL,
  `taxstatus` enum('yes','no','incl','cgst','igst') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'yes',
  `discstatus` tinyint(1) NOT NULL,
  `format_discount` enum('%','flat','bflat','b_p') CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `refer` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `term` int(3) NOT NULL,
  `loc` int(4) NOT NULL,
  `i_class` int(1) NOT NULL DEFAULT 0,
  `multi` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `invoice`(`tid`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `csd`(`csd`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_stock_r_items
-- ----------------------------
DROP TABLE IF EXISTS `geopos_stock_r_items`;
CREATE TABLE `geopos_stock_r_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `product` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `code` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `qty` decimal(10, 2) NOT NULL,
  `price` decimal(16, 2) NULL DEFAULT 0,
  `tax` decimal(16, 2) NULL DEFAULT 0,
  `discount` decimal(16, 2) NULL DEFAULT 0,
  `subtotal` decimal(16, 2) NULL DEFAULT 0,
  `totaltax` decimal(16, 2) NULL DEFAULT 0,
  `totaldiscount` decimal(16, 2) NULL DEFAULT 0,
  `product_des` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `unit` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_supplier
-- ----------------------------
DROP TABLE IF EXISTS `geopos_supplier`;
CREATE TABLE `geopos_supplier`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `address` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `city` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `region` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `country` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `postbox` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(60) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `picture` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'example.png',
  `gid` int(5) NOT NULL DEFAULT 1,
  `company` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `taxid` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `loc` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `gid`(`gid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_supplier
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_system
-- ----------------------------
DROP TABLE IF EXISTS `geopos_system`;
CREATE TABLE `geopos_system`  (
  `id` int(1) NOT NULL,
  `cname` char(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `city` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `region` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `country` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `postbox` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `email` varchar(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `taxid` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `tax` int(11) NOT NULL,
  `currency` varchar(4) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `currency_format` int(1) NOT NULL,
  `prefix` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `dformat` int(1) NOT NULL,
  `zone` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `logo` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `lang` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'english',
  `foundation` date NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_system
-- ----------------------------
INSERT INTO `geopos_system` VALUES (1, 'Tafu', 'Kampong Chhnang', 'Kampong Chhnang', 'KH', 'Cambodia', '123', '+85589266009 / +8558', 'tafu.team@gmail.com', '', -1, '៛', 0, 'INV#', 1, 'Etc/Greenwich', '15750839731438812209.png', 'english', '2019-11-30');

-- ----------------------------
-- Table structure for geopos_terms
-- ----------------------------
DROP TABLE IF EXISTS `geopos_terms`;
CREATE TABLE `geopos_terms`  (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` int(1) NOT NULL,
  `terms` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_terms
-- ----------------------------
INSERT INTO `geopos_terms` VALUES (1, 'Payment On Receipt', 1, '<p><b>Delivery will be made within 3 days.</b></p><p>Thank you for your business. We look forward to serving you again soon.</p>');
INSERT INTO `geopos_terms` VALUES (2, 'Quote Term and Condition', 2, '<p><span style=\"font-weight: bolder;\">Delivery will be made within 3 days after Quotation Confirm and Invoice Generated.</span></p><p>The price of product will not change until the quotation\'s valid date.</p><p>Thank you for your business. We look forward to serving you soon.</p>');
INSERT INTO `geopos_terms` VALUES (3, 'Purchase Term and Condition', 4, '<p>The product can be replaced due to broken or expired.</p>');

-- ----------------------------
-- Table structure for geopos_tickets
-- ----------------------------
DROP TABLE IF EXISTS `geopos_tickets`;
CREATE TABLE `geopos_tickets`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `created` datetime(0) NOT NULL,
  `cid` int(11) NOT NULL,
  `status` enum('Solved','Processing','Waiting') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `section` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_tickets_th
-- ----------------------------
DROP TABLE IF EXISTS `geopos_tickets_th`;
CREATE TABLE `geopos_tickets_th`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `message` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `cid` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `cdate` datetime(0) NOT NULL,
  `attach` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `tid`(`tid`) USING BTREE,
  INDEX `cid`(`cid`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_todolist
-- ----------------------------
DROP TABLE IF EXISTS `geopos_todolist`;
CREATE TABLE `geopos_todolist`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tdate` date NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` enum('Due','Done','Progress') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'Due',
  `start` date NOT NULL,
  `duedate` date NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `eid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `related` int(11) NOT NULL,
  `priority` enum('Low','Medium','High','Urgent') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_trans_cat
-- ----------------------------
DROP TABLE IF EXISTS `geopos_trans_cat`;
CREATE TABLE `geopos_trans_cat`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_trans_cat
-- ----------------------------
INSERT INTO `geopos_trans_cat` VALUES (1, 'Income');
INSERT INTO `geopos_trans_cat` VALUES (2, 'Expenses');
INSERT INTO `geopos_trans_cat` VALUES (3, 'Other');

-- ----------------------------
-- Table structure for geopos_transactions
-- ----------------------------
DROP TABLE IF EXISTS `geopos_transactions`;
CREATE TABLE `geopos_transactions`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `acid` int(11) NOT NULL,
  `account` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` enum('Income','Expense','Transfer') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `cat` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `debit` decimal(16, 2) NULL DEFAULT 0,
  `credit` decimal(16, 2) NULL DEFAULT 0,
  `payer` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `payerid` int(11) NOT NULL DEFAULT 0,
  `method` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `date` date NOT NULL,
  `tid` int(11) NOT NULL DEFAULT 0,
  `eid` int(11) NOT NULL,
  `note` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ext` int(1) NULL DEFAULT 0,
  `loc` int(4) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `loc`(`loc`) USING BTREE,
  INDEX `acid`(`acid`) USING BTREE,
  INDEX `eid`(`eid`) USING BTREE,
  INDEX `tid`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for geopos_units
-- ----------------------------
DROP TABLE IF EXISTS `geopos_units`;
CREATE TABLE `geopos_units`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `code` varchar(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` int(1) NOT NULL,
  `sub` int(1) NOT NULL,
  `rid` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for geopos_users
-- ----------------------------
DROP TABLE IF EXISTS `geopos_users`;
CREATE TABLE `geopos_users`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `pass` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `banned` tinyint(1) NULL DEFAULT 0,
  `last_login` datetime(0) NULL DEFAULT NULL,
  `last_activity` datetime(0) NULL DEFAULT NULL,
  `date_created` datetime(0) NULL DEFAULT NULL,
  `forgot_exp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `remember_time` datetime(0) NULL DEFAULT NULL,
  `remember_exp` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `verification_code` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `totp_secret` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `ip_address` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `roleid` int(1) NOT NULL,
  `picture` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `loc` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `username`(`username`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_users
-- ----------------------------
INSERT INTO `geopos_users` VALUES (14, 'dev@reahou.com', 'cca7ee4536b947d44b053dfa3f28a77558700b0e0c9239915db8a126fcbffcf8', 'admin', 0, '2019-12-22 04:15:11', '2019-12-22 04:15:11', '2019-11-17 09:14:42', NULL, NULL, NULL, '', NULL, '202.93.153.242', 5, 'example.png', 0);

-- ----------------------------
-- Table structure for geopos_warehouse
-- ----------------------------
DROP TABLE IF EXISTS `geopos_warehouse`;
CREATE TABLE `geopos_warehouse`  (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `extra` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `loc` int(4) NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of geopos_warehouse
-- ----------------------------
INSERT INTO `geopos_warehouse` VALUES (1, 'Main WareHouse', 'The Main WareHouse', 0);
INSERT INTO `geopos_warehouse` VALUES (2, 'Using Warehouse', 'Backoffice Stock', 0);

-- ----------------------------
-- Table structure for tb_adjustment_items
-- ----------------------------
DROP TABLE IF EXISTS `tb_adjustment_items`;
CREATE TABLE `tb_adjustment_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NULL DEFAULT NULL,
  `pid` int(11) NULL DEFAULT 0,
  `qty` int(11) NULL DEFAULT NULL,
  `stock_id` int(11) NULL DEFAULT NULL,
  `cost` decimal(10, 2) NULL DEFAULT NULL,
  `sub_total` decimal(10, 2) NULL DEFAULT NULL,
  `warehouse_id` int(11) NULL DEFAULT NULL,
  `unique_id` varbinary(255) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_adjustment_items
-- ----------------------------

-- ----------------------------
-- Table structure for tb_adjustments
-- ----------------------------
DROP TABLE IF EXISTS `tb_adjustments`;
CREATE TABLE `tb_adjustments`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adjustdate` date NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `items` decimal(10, 2) NULL DEFAULT NULL,
  `eid` int(3) NULL DEFAULT NULL,
  `from_warehouse` int(4) NULL DEFAULT NULL,
  `loc` int(4) NULL DEFAULT NULL,
  `total_cost` decimal(10, 2) NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_adjustments
-- ----------------------------

-- ----------------------------
-- Table structure for tb_stock
-- ----------------------------
DROP TABLE IF EXISTS `tb_stock`;
CREATE TABLE `tb_stock`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NULL DEFAULT 0,
  `warehouse_id` int(11) NULL DEFAULT 0,
  `unique_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `qty` int(11) NULL DEFAULT 0,
  `purchase_detail_id` int(11) NULL DEFAULT 0,
  `sale_detail_id` int(11) NULL DEFAULT NULL,
  `product_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `sale_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `init_stock` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_stock
-- ----------------------------

-- ----------------------------
-- Table structure for tb_transfer_items
-- ----------------------------
DROP TABLE IF EXISTS `tb_transfer_items`;
CREATE TABLE `tb_transfer_items`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NULL DEFAULT NULL,
  `pid` int(11) NULL DEFAULT 0,
  `qty` int(11) NULL DEFAULT NULL,
  `stock_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `invoice`(`tid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_transfers
-- ----------------------------
DROP TABLE IF EXISTS `tb_transfers`;
CREATE TABLE `tb_transfers`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transferdate` date NULL DEFAULT NULL,
  `notes` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `items` decimal(10, 2) NULL DEFAULT NULL,
  `eid` int(3) NULL DEFAULT NULL,
  `from_warehouse` int(4) NULL DEFAULT NULL,
  `to_warehouse` int(1) NULL DEFAULT 0,
  `loc` int(4) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `i_class`(`to_warehouse`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for univarsal_api
-- ----------------------------
DROP TABLE IF EXISTS `univarsal_api`;
CREATE TABLE `univarsal_api`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key1` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `key2` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `method` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `other` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `active` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 67 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of univarsal_api
-- ----------------------------
INSERT INTO `univarsal_api` VALUES (1, 'Goo.gl URL Shortner', 'yourkey', '0', '0', '0', '0', 0);
INSERT INTO `univarsal_api` VALUES (2, 'Twilio SMS API', 'ACf7ebc8f570d1cf4d2f2c6ffa285bad26', '07b87568808989e7ef02ecf429ebeb6c', '+85589266009', '0', '', 1);
INSERT INTO `univarsal_api` VALUES (3, 'Company Support', '1', '1', 'support@gmail.com', NULL, '<p>Your footer</p>', 1);
INSERT INTO `univarsal_api` VALUES (4, 'Currency', '.', ',', '0', 'r', NULL, NULL);
INSERT INTO `univarsal_api` VALUES (5, 'Exchange', 'key1v', 'key2', 'KHR', NULL, '0', 0);
INSERT INTO `univarsal_api` VALUES (6, 'New Invoice Notification', '[{Company}] Invoice #{BillNumber} Generated', NULL, NULL, NULL, '<p>Dear\n            Client,\r\n</p><p>We are contacting you in regard to a payment received for invoice # {BillNumber} that has\n            been created on your account. You may find the invoice with below link.\r\n\r\nView\n            Invoice\r\n{URL}\r\n\r\nWe look forward to conducting future business with you.\r\n\r\nKind\n            Regards,\r\nTeam\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (7, 'Invoice Payment Reminder', '[{Company}] Invoice #{BillNumber} Payment Reminder', NULL, NULL, NULL, '<p>Dear\n            Client,</p><p>We are contacting you in regard to a payment reminder of invoice # {BillNumber} that has been\n            created on your account. You may find the invoice with below link. Please pay the balance of {Amount} due by\n            {DueDate}.</p><p>\r\n\r\n<b>View Invoice</b></p><p><span style=\"font-size: 1rem;\">{URL}\r\n</span></p><p>\n            <span style=\"font-size: 1rem;\">\r\nWe look forward to conducting future business with you.</span></p><p>\n            <span style=\"font-size: 1rem;\">\r\n\r\nKind Regards,\r\n</span></p><p><span style=\"font-size: 1rem;\">\r\nTeam\r\n</span>\n        </p><p><span style=\"font-size: 1rem;\">\r\n{CompanyDetails}</span></p>', NULL);
INSERT INTO `univarsal_api` VALUES (8, 'Invoice Refund Proceeded', '{Company} Invoice #{BillNumber} Refund Proceeded', NULL, NULL, NULL, '<p>Dear\n            Client,</p><p>\r\nWe are contacting you in regard to a refund request processed for invoice # {BillNumber}\n            that has been created on your account. You may find the invoice with below link. Please pay the balance of\n            {Amount} by {DueDate}.\r\n</p><p>\r\nView Invoice\r\n</p><p>{URL}\r\n</p><p>\r\nWe look forward to\n            conducting future business with you.\r\n</p><p>\r\nKind Regards,\r\n</p><p>\n            \r\nTeam\r\n\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (9, 'Invoice payment Received', '{Company} Payment Received for Invoice #{BillNumber}', NULL, NULL, NULL, '<p>\n            Dear Client,\r\n</p><p>We are contacting you in regard to a payment received for invoice # {BillNumber} that\n            has been created on your account. You can find the invoice with below link.\r\n</p><p>\r\nView Invoice</p>\n        <p>\r\n{URL}\r\n</p><p>\r\nWe look forward to conducting future business with you.\r\n</p><p>\r\nKind\n            Regards,\r\n</p><p>\r\nTeam\r\n</p><p>\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (10, 'Invoice Overdue Notice', '{Company} Invoice #{BillNumber} Generated for you', NULL, NULL, NULL, '<p>Dear\n            Client,</p><p>\r\nWe are contacting you in regard to an Overdue Notice for invoice # {BillNumber} that has\n            been created on your account. You may find the invoice with below link.\r\nPlease pay the balance of\n            {Amount} due by {DueDate}.\r\n</p><p>View Invoice\r\n</p><p>{URL}\r\n</p><p>\r\nWe look forward to\n            conducting future business with you.\r\n</p><p>\r\nKind Regards,\r\n</p><p>\r\nTeam</p><p>\n            \r\n\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (11, 'Quote Proposal', '{Company} Quote #{BillNumber} Generated for you', NULL, NULL, NULL, '<p>Dear Client,</p>\n        <p>\r\nWe are contacting you in regard to a new quote # {BillNumber} that has been created on your account. You\n            may find the quote with below link.\r\n</p><p>\r\nView Invoice\r\n</p><p>{URL}\r\n</p><p>\r\nWe look forward\n            to conducting future business with you.</p><p>\r\n\r\nKind Regards,</p><p>\r\n\r\nTeam</p><p>\n            \r\n\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (12, 'Purchase Order Request', '{Company} Purchase Order #{BillNumber} Requested', NULL, NULL, NULL, '<p>Dear\n            Client,\r\n</p><p>We are contacting you in regard to a new purchase # {BillNumber} that has been requested\n            on your account. You may find the order with below link. </p><p>\r\n\r\nView Invoice\r\n</p><p>{URL}</p><p>\n            \r\n\r\nWe look forward to conducting future business with you.</p><p>\r\n\r\nKind Regards,\r\n</p><p>\n            \r\nTeam</p><p>\r\n\r\n{CompanyDetails}</p>', NULL);
INSERT INTO `univarsal_api` VALUES (13, 'Stock Return Mail', '{Company} New purchase return # {BillNumber}', NULL, NULL, NULL, 'Dear Client,\r\n\r\nWe are contacting you in regard to a new purchase return # {BillNumber} that has been requested on your account. You may find the order with below link.\r\n\r\nView Invoice\r\n\r\n{URL}\r\n\r\nWe look forward to conducting future business with you.\r\n\r\nKind Regards,\r\n\r\nTeam\r\n\r\n{CompanyDetails}', NULL);
INSERT INTO `univarsal_api` VALUES (14, 'Customer Registration', '{Company}  Customer Registration - {NAME}', NULL, NULL, NULL, 'Dear Customer,\r\nThank You for registration, please confirm the registration by the following URL {REG_URL}\r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (15, 'Â Customer Password Reset', '{Company} Â Customer Password Reset- {NAME}', NULL, NULL, NULL, 'Dear Customer,\r\nPlease reset the password by the following URL {RESET_URL}\r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (16, 'Customer Registration by Employee', '{Company} Â Customer Registration - {NAME}', '0', '0', '0', 'Dear Customer,\r\nThank You for registration.\r\nLogin URL: {URL}\r\nLogin Email: {EMAIL}\r\nPassword: {PASSWORD}\r\n\r\nRegards\r\n{CompanyDetails}', 0);
INSERT INTO `univarsal_api` VALUES (30, 'New Invoice Notification', 'SMS', NULL, NULL, NULL, 'Dear Customer, \r\nNew invoice  # {BillNumber} generated. \r\n{URL} \r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (31, 'Invoice Payment Reminder', 'SMS', NULL, NULL, NULL, 'Dear Customer, \r\nPlease make payment of invoice  # {BillNumber}. \r\n{URL} \r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (32, 'Invoice Refund Proceeded', 'SMS', NULL, NULL, NULL, 'Dear Customer, \r\nRefund generated of invoice # {BillNumber}. \r\n{URL} \r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (33, 'Invoice payment Received', 'SMS', NULL, NULL, NULL, 'Dear Customer, \r\nPayment received of invoice # {BillNumber}. \r\n{URL} \r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (34, 'Invoice Overdue Notice', 'SMS', NULL, NULL, NULL, 'Dear Customer, \r\nPayment is overdue of invoice # {BillNumber}. \r\n{URL}\r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (35, 'Quote Proposal', 'SMS', NULL, NULL, NULL, 'Dear Valued Customer, \r\nA quote created for you # {BillNumber}. {URL} \r\nTafu\r\nRegards', NULL);
INSERT INTO `univarsal_api` VALUES (36, 'Purchase Order Request', NULL, NULL, NULL, NULL, 'Dear Customer, Dear, a purchased order for you # {BillNumber}. {URL} Regards', NULL);
INSERT INTO `univarsal_api` VALUES (51, 'QT#', 'PO#', 'SUB#', 'SR#', 'TRN#', 'SRN#', 1);
INSERT INTO `univarsal_api` VALUES (52, 'ThermalPrint', '0', NULL, NULL, NULL, 'POS#', 0);
INSERT INTO `univarsal_api` VALUES (53, 'ConfPort', 'Public Key', '0', 'Private Key', NULL, NULL, 1);
INSERT INTO `univarsal_api` VALUES (54, 'online_payment', '1', 'USD', '1', '1', NULL, 1);
INSERT INTO `univarsal_api` VALUES (55, 'CronJob', '99293768', 'rec_email', 'email', 'rec_due', 'recemail', NULL);
INSERT INTO `univarsal_api` VALUES (56, 'Auto Email SMS', 'email', 'sms', NULL, NULL, NULL, NULL);
INSERT INTO `univarsal_api` VALUES (60, 'Warehouse', '1', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `univarsal_api` VALUES (61, 'Discount & Shipping', '%', '10.00', 'incl', NULL, NULL, NULL);
INSERT INTO `univarsal_api` VALUES (62, 'AutoAttendance', '1', '0', '0', '0', '0', NULL);
INSERT INTO `univarsal_api` VALUES (63, 'Zero Stock Billing', '1', '0', '0', '0', '0', 0);
INSERT INTO `univarsal_api` VALUES (64, 'FrontEndSection', '0', '0', '0', '0', NULL, 0);
INSERT INTO `univarsal_api` VALUES (65, 'Dual Entry', '0', '1', '0', '0', '0', 0);
INSERT INTO `univarsal_api` VALUES (66, 'Email Alert', '0', '0', 'sample@email.com', '0', '0', 0);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `users_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `var_key` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_deleted` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `profile_pic` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `user_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `cid` int(11) NULL DEFAULT NULL,
  `lang` varchar(25) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'english',
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`users_id`) USING BTREE,
  INDEX `code`(`code`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, '1', NULL, 'active', '0', 'Walk-in Client', '$2y$10$TKfTVltchv/jxZIouy8i8O7rSzVvtdx4Y5wNRodK5RNyTBJkEoIAW', 'example@example.com', NULL, 'Member', 1, 'english', NULL);

SET FOREIGN_KEY_CHECKS = 1;
