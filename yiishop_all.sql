/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yiishop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-07-23 20:58:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sp_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_admin_user`;
CREATE TABLE `sp_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) NOT NULL COMMENT '管理员帐号',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT '管理员密码',
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL COMMENT '管理员邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `login_at` int(11) NOT NULL COMMENT '最近登陆时间',
  `login_ip` bigint(20) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adminuser_username_email` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_admin_user
-- ----------------------------
INSERT INTO `sp_admin_user` VALUES ('2', 'admin', 'owK8MnOIcXvKBCZg84qdDL4Qv9OzfNLg', '$2y$13$vBQwQoyE.x3/18LpkUNDE.0o5om2VZd5F1pbK/eb9m8VI4ngizb4e', null, 'admin@163.com', '10', '10', '0', '0', '1500800407', '0');

-- ----------------------------
-- Table structure for `sp_auth_assignment`
-- ----------------------------
DROP TABLE IF EXISTS `sp_auth_assignment`;
CREATE TABLE `sp_auth_assignment` (
  `item_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`item_name`,`user_id`),
  CONSTRAINT `sp_auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `sp_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sp_auth_assignment
-- ----------------------------
INSERT INTO `sp_auth_assignment` VALUES ('管理员用户管理', '2', '1500806651');

-- ----------------------------
-- Table structure for `sp_auth_item`
-- ----------------------------
DROP TABLE IF EXISTS `sp_auth_item`;
CREATE TABLE `sp_auth_item` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `rule_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`),
  KEY `rule_name` (`rule_name`),
  KEY `idx-auth_item-type` (`type`),
  CONSTRAINT `sp_auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `sp_auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sp_auth_item
-- ----------------------------
INSERT INTO `sp_auth_item` VALUES ('/*', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/admin/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1500803381', '1500803381');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/activate', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/change-password', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/delete', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/index', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/login', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/logout', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/request-password-reset', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/reset-password', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/signup', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/admin/user/view', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/*', '2', null, null, null, '1500809290', '1500809290');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/create', '2', null, null, null, '1500809290', '1500809290');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/delete', '2', null, null, null, '1500809290', '1500809290');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/index', '2', null, null, null, '1500809289', '1500809289');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/update', '2', null, null, null, '1500809290', '1500809290');
INSERT INTO `sp_auth_item` VALUES ('/adminuser/view', '2', null, null, null, '1500809289', '1500809289');
INSERT INTO `sp_auth_item` VALUES ('/debug/*', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1500803382', '1500803382');
INSERT INTO `sp_auth_item` VALUES ('/gii/*', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/*', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/action', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/diff', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/index', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/preview', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/view', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/site/*', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/site/error', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/site/index', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/site/login', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('/site/logout', '2', null, null, null, '1500803383', '1500803383');
INSERT INTO `sp_auth_item` VALUES ('管理员用户', '2', null, null, null, '1500805995', '1500806507');
INSERT INTO `sp_auth_item` VALUES ('管理员用户管理', '1', null, null, null, '1500806199', '1500806555');

-- ----------------------------
-- Table structure for `sp_auth_item_child`
-- ----------------------------
DROP TABLE IF EXISTS `sp_auth_item_child`;
CREATE TABLE `sp_auth_item_child` (
  `parent` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`,`child`),
  KEY `child` (`child`),
  CONSTRAINT `sp_auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `sp_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `sp_auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `sp_auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sp_auth_item_child
-- ----------------------------
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/*');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/create');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/delete');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/index');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/update');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/adminuser/view');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户管理', '管理员用户');

-- ----------------------------
-- Table structure for `sp_auth_rule`
-- ----------------------------
DROP TABLE IF EXISTS `sp_auth_rule`;
CREATE TABLE `sp_auth_rule` (
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `data` blob,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of sp_auth_rule
-- ----------------------------

-- ----------------------------
-- Table structure for `sp_menu`
-- ----------------------------
DROP TABLE IF EXISTS `sp_menu`;
CREATE TABLE `sp_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `route` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`),
  KEY `parent` (`parent`),
  CONSTRAINT `sp_menu_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `sp_menu` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_menu
-- ----------------------------
INSERT INTO `sp_menu` VALUES ('3', '管理员用户', '4', '/adminuser/index', null, null);
INSERT INTO `sp_menu` VALUES ('4', '后台权限', null, '/admin/default/index', null, null);

-- ----------------------------
-- Table structure for `sp_migration`
-- ----------------------------
DROP TABLE IF EXISTS `sp_migration`;
CREATE TABLE `sp_migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_migration
-- ----------------------------
INSERT INTO `sp_migration` VALUES ('m000000_000000_base', '1500776810');
INSERT INTO `sp_migration` VALUES ('m130524_201442_init', '1500776815');
INSERT INTO `sp_migration` VALUES ('m140602_111327_create_menu_table', '1500781213');
INSERT INTO `sp_migration` VALUES ('m140506_102106_rbac_init', '1500787705');

-- ----------------------------
-- Table structure for `sp_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_user`;
CREATE TABLE `sp_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_user
-- ----------------------------
INSERT INTO `sp_user` VALUES ('3', 'zhaitailang', 'k3EdRJ6QOzCCJD-nC_ODjNxtVG7-fZyg', '$2y$13$uP2CFN0f24FHYAlMXxVIT.6MUH6vfrXASw3YNTui3iVgxxr16uz7m', null, '282586392@qq.com', '10', '10', '1500793479', '1500793479');
INSERT INTO `sp_user` VALUES ('4', 'zhaitailang1', 'M6lrqU_EA93Ecq1ZSAIk2diL_273qLa4', '$2y$13$gv8isE4qp8OV/VAKrgJJGON1iIAoSuoj1P7JeLDjm7.2IbCBj3utu', null, 'admin@qq.com', '10', '10', '1500795728', '1500795728');
INSERT INTO `sp_user` VALUES ('6', 'yui', 'owK8MnOIcXvKBCZg84qdDL4Qv9OzfNLg', '$2y$13$vBQwQoyE.x3/18LpkUNDE.0o5om2VZd5F1pbK/eb9m8VI4ngizb4e', null, 'admin11@qq.com', '10', '10', '1500800122', '1500800122');
