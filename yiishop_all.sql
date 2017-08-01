/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : yiishop

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-07-31 19:20:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `sp_admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_admin_user`;
CREATE TABLE `sp_admin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `username` varchar(255) NOT NULL COMMENT '管理员帐号',
  `nickname` varchar(255) DEFAULT NULL COMMENT '别名',
  `auth_key` varchar(32) NOT NULL,
  `password_hash` varchar(255) NOT NULL COMMENT '管理员密码',
  `password_reset_token` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL COMMENT '管理员邮箱',
  `role` smallint(6) NOT NULL DEFAULT '10',
  `status` smallint(6) NOT NULL DEFAULT '10',
  `login_at` int(11) NOT NULL COMMENT '最近登陆时间',
  `login_ip` varchar(32) NOT NULL DEFAULT '0' COMMENT '登录IP',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adminuser_username_email` (`username`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_admin_user
-- ----------------------------
INSERT INTO `sp_admin_user` VALUES ('2', 'admin', '宅太浪', 'owK8MnOIcXvKBCZg84qdDL4Qv9OzfNLg', '$2y$13$sLdMVBItIKRY9Gpn4AiMCejZwuh9gHrKLqXEBRnx/sRca00Pw7guq', null, 'admin@163.com', '10', '10', '1501249767', '127.0.0.1', '1500800407', '1501233884');
INSERT INTO `sp_admin_user` VALUES ('3', 'admin123', '你麻痹', 'mmbshgsYf3hcY5Uyqyr_Mm9ZByuqG3gm', '$2y$13$IGNhRQvhF0gb.n.L4wv3duwJDHa/s07i7nQtK4UF3JHKCQIARMVRe', null, 'admin213123@163.com', '10', '10', '0', '0', '1500974268', '1501245169');
INSERT INTO `sp_admin_user` VALUES ('4', 'admin1234', 'das', 'UcymRn7-nVeJ8oq1QLqVc-UpbaReQtFb', '$2y$13$UH.Agh51yhFzTU1oQs9DJu38cyx8RnVaSyOEKGw//W5sqawuP.d9u', null, 'admi3n@163.com', '10', '10', '0', '0', '1501037414', '1501037414');
INSERT INTO `sp_admin_user` VALUES ('5', 'zhaitailang', '哈哈哈', '5Boe4gzMlglYqXBGyE8kAaefgWgOg7v0', '$2y$13$zq2xAJlWZB7m5MMU96v.YuJkqCo4zCN/sEjwj75dRX1od5VPhW23.', null, '282586392@qq.com', '10', '10', '0', '0', '1501037769', '1501037769');

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
INSERT INTO `sp_auth_assignment` VALUES ('上帝视角', '2', '1500881777');

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
INSERT INTO `sp_auth_item` VALUES ('/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/create', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/delete', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/index', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/update', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin-user/view', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/admin/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/*', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/assign', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/index', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/revoke', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/assignment/view', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/default/*', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/default/index', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/*', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/create', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/delete', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/index', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/update', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/menu/view', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/*', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/assign', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/create', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/delete', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/index', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/remove', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/update', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/permission/view', '2', null, null, null, '1500880316', '1500880316');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/assign', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/create', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/delete', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/index', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/remove', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/update', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/role/view', '2', null, null, null, '1500880317', '1500880317');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/assign', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/create', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/index', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/refresh', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/route/remove', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/create', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/delete', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/index', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/update', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/admin/rule/view', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/*', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/db-explain', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/download-mail', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/index', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/toolbar', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/debug/default/view', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/gii/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/action', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/diff', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/index', '2', null, null, null, '1500880318', '1500880318');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/preview', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/gii/default/view', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/site/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/site/error', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/site/index', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/site/login', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/site/logout', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/*', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/create', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/delete', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/index', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/update', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('/user/view', '2', null, null, null, '1500880319', '1500880319');
INSERT INTO `sp_auth_item` VALUES ('上帝视角', '1', 'root', null, null, '1500881758', '1500883475');
INSERT INTO `sp_auth_item` VALUES ('开发测试', '2', 'gii和debug工具', null, null, '1500882216', '1500882216');
INSERT INTO `sp_auth_item` VALUES ('权限控制', '2', 'rbac 以及菜单的操作', null, null, '1500882022', '1500882022');
INSERT INTO `sp_auth_item` VALUES ('用户管理', '2', '前台会员管理', null, null, '1500881691', '1500881709');
INSERT INTO `sp_auth_item` VALUES ('管理员用户', '2', '后台管理人员集合', null, null, '1500881634', '1500881664');

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
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/*');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/create');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/delete');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/index');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/update');
INSERT INTO `sp_auth_item_child` VALUES ('管理员用户', '/admin-user/view');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/assignment/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/assignment/assign');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/assignment/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/assignment/revoke');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/assignment/view');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/create');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/delete');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/update');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/menu/view');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/assign');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/create');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/delete');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/remove');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/update');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/permission/view');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/assign');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/create');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/delete');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/remove');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/update');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/role/view');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/assign');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/create');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/refresh');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/route/remove');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/*');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/create');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/delete');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/index');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/update');
INSERT INTO `sp_auth_item_child` VALUES ('权限控制', '/admin/rule/view');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/*');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/*');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/db-explain');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/download-mail');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/index');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/toolbar');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/debug/default/view');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/*');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/*');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/action');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/diff');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/index');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/preview');
INSERT INTO `sp_auth_item_child` VALUES ('开发测试', '/gii/default/view');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/*');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/create');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/delete');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/index');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/update');
INSERT INTO `sp_auth_item_child` VALUES ('用户管理', '/user/view');
INSERT INTO `sp_auth_item_child` VALUES ('上帝视角', '开发测试');
INSERT INTO `sp_auth_item_child` VALUES ('上帝视角', '权限控制');
INSERT INTO `sp_auth_item_child` VALUES ('上帝视角', '用户管理');
INSERT INTO `sp_auth_item_child` VALUES ('上帝视角', '管理员用户');

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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sp_menu
-- ----------------------------
INSERT INTO `sp_menu` VALUES ('5', '后台管理', null, '/admin/default/index', null, null);
INSERT INTO `sp_menu` VALUES ('6', '管理员用户', '5', '/admin-user/index', null, null);
INSERT INTO `sp_menu` VALUES ('7', '权限管理', '5', '/admin/default/index', null, null);
INSERT INTO `sp_menu` VALUES ('8', '路由', '7', '/admin/route/index', '1', null);
INSERT INTO `sp_menu` VALUES ('9', '权限', '7', '/admin/permission/index', '2', null);
INSERT INTO `sp_menu` VALUES ('10', '角色', '7', '/admin/role/index', '3', null);
INSERT INTO `sp_menu` VALUES ('11', '分配', '7', '/admin/assignment/index', '4', null);
INSERT INTO `sp_menu` VALUES ('12', '菜单管理', '5', '/admin/menu/index', null, null);
INSERT INTO `sp_menu` VALUES ('13', '前台管理', null, '/admin/default/index', null, null);
INSERT INTO `sp_menu` VALUES ('14', '用户管理', '13', '/user/index', null, null);
INSERT INTO `sp_menu` VALUES ('15', '神之左手', '5', '/admin/default/index', null, null);
INSERT INTO `sp_menu` VALUES ('16', 'gii', '15', '/gii/default/index', '1', null);
INSERT INTO `sp_menu` VALUES ('17', 'debug', '15', '/debug/default/index', '2', null);

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
