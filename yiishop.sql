-- ----------------------------
-- Table structure for `admin_user`
-- ----------------------------
DROP TABLE IF EXISTS `sp_admin_user`;
CREATE TABLE `admin_user` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `sp_admin_user`(username,password_hash,auth_key,email,created_at) VALUES('admin','$2y$13$vBQwQoyE.x3/18LpkUNDE.0o5om2VZd5F1pbK/eb9m8VI4ngizb4e','owK8MnOIcXvKBCZg84qdDL4Qv9OzfNLg','admin@163.com',UNIX_TIMESTAMP());