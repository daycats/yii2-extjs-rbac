SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for dp_admin_action
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_action`;
CREATE TABLE `dp_admin_action` (
  `action_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id,对应本表action_id字段',
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员权限列表树';

-- ----------------------------
-- Table structure for dp_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_group`;
CREATE TABLE `dp_admin_group` (
  `group_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统用户组 0.否,1.是',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁止,1.启用,2.删除',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员用户组';

-- ----------------------------
-- Table structure for dp_admin_group_menu_relation
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_group_menu_relation`;
CREATE TABLE `dp_admin_group_menu_relation` (
  `group_id` smallint(5) unsigned NOT NULL COMMENT '分组id,对应admin_group表的group_id字段',
  `menu_id` smallint(5) unsigned NOT NULL COMMENT '菜单id,对应admin_menu表的menu_id字段',
  PRIMARY KEY (`group_id`,`menu_id`),
  KEY `menu_id` (`menu_id`) USING BTREE,
  CONSTRAINT `dp_admin_group_menu_relation_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `dp_admin_group` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dp_admin_group_menu_relation_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `dp_admin_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='admin_group表和admin_menu表的关系表';

-- ----------------------------
-- Table structure for dp_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_menu`;
CREATE TABLE `dp_admin_menu` (
  `menu_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id,对应本表的menu_id字段',
  `text` varchar(255) NOT NULL COMMENT '菜单名',
  `title` varchar(255) NOT NULL COMMENT '标题名',
  `url` varchar(255) NOT NULL COMMENT 'URL',
  `view_package` varchar(255) NOT NULL COMMENT '视图名',
  `expanded` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '展开 0.否,1.是',
  `closable` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '允许关闭窗口 0.否,1是',
  `is_folder` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '文件夹',
  `is_open_url` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '打开URL 0.否,1.是',
  `is_open_target` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '新的窗口打开 0.否,1.是',
  `is_every_open` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '每次重新打开 0.否,1是',
  `is_hide` tinyint(1) unsigned NOT NULL COMMENT '隐藏 0.否,1.是',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT '显示排序号',
  `note` text NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁用,1.启用,2.删除',
  PRIMARY KEY (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='菜单';

-- ----------------------------
-- Table structure for dp_admin_menu_url
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_menu_url`;
CREATE TABLE `dp_admin_menu_url` (
  `url_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(255) NOT NULL COMMENT '名称',
  `alias` varchar(255) NOT NULL COMMENT '别名',
  `route` varchar(255) NOT NULL COMMENT '路由',
  `method` varchar(255) NOT NULL COMMENT '请求方式',
  `host` varchar(255) NOT NULL COMMENT '主机地址',
  `enable_rule` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '启用规则 0.否,1.是',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁用,1.启用.2.删除',
  PRIMARY KEY (`url_id`),
  UNIQUE KEY `alias` (`alias`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='菜单URL';

-- ----------------------------
-- Table structure for dp_admin_menu_url_relation
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_menu_url_relation`;
CREATE TABLE `dp_admin_menu_url_relation` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `menu_id` smallint(5) unsigned NOT NULL COMMENT '菜单id,对应admin_menu表的menu_id字段',
  `url_id` smallint(5) unsigned NOT NULL COMMENT 'URL id,对应admin_menu_url表的url_id字段',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁止,1.启用,2.删除',
  PRIMARY KEY (`link_id`),
  KEY `menu_id` (`menu_id`) USING BTREE,
  KEY `url_id` (`url_id`) USING BTREE,
  CONSTRAINT `dp_admin_menu_url_relation_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `dp_admin_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dp_admin_menu_url_relation_ibfk_2` FOREIGN KEY (`url_id`) REFERENCES `dp_admin_menu_url` (`url_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='admin_menu表和admin_menu_url表关系表';

-- ----------------------------
-- Table structure for dp_admin_menu_url_rule
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_menu_url_rule`;
CREATE TABLE `dp_admin_menu_url_rule` (
  `rule_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `url_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '菜单URL id,对应admin_menu_url表的url_id字段',
  `param_name` varchar(255) NOT NULL COMMENT '参数名',
  `rule` varchar(255) NOT NULL COMMENT '规则',
  `note` varchar(255) NOT NULL COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁用,1.启用,2.删除',
  PRIMARY KEY (`rule_id`),
  KEY `url_id` (`url_id`) USING BTREE,
  CONSTRAINT `dp_admin_menu_url_rule_ibfk_1` FOREIGN KEY (`url_id`) REFERENCES `dp_admin_menu_url` (`url_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='菜单URL规则';

-- ----------------------------
-- Table structure for dp_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_user`;
CREATE TABLE `dp_admin_user` (
  `user_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID号',
  `username` char(20) NOT NULL COMMENT '用户名',
  `nickname` char(20) NOT NULL COMMENT '昵称',
  `password_hash` varchar(255) NOT NULL COMMENT '哈希密码',
  `password_reset_token` varchar(255) NOT NULL COMMENT '访问令牌',
  `auth_key` char(32) NOT NULL COMMENT '验证码',
  `group_ids` varchar(255) NOT NULL COMMENT '用户组id集#对应admin_group的id字段',
  `is_group_access` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户组权限 0.关闭,1.开启',
  `is_user_access` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '用户权限 0.关闭,1.开启',
  `is_system` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '系统用户 0.否,1.是',
  `is_super` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '超级用户 0.否,1.是',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态 0.禁用,1.启用,2.删除',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='管理员资料';

-- ----------------------------
-- Table structure for dp_admin_user_menu_relation
-- ----------------------------
DROP TABLE IF EXISTS `dp_admin_user_menu_relation`;
CREATE TABLE `dp_admin_user_menu_relation` (
  `user_id` smallint(5) unsigned NOT NULL COMMENT '用户ID#对应admin_user表的user_id字段',
  `menu_id` smallint(5) unsigned NOT NULL COMMENT '菜单ID#对应admin_menu表的menu_id字段',
  PRIMARY KEY (`user_id`,`menu_id`),
  KEY `menu_id` (`menu_id`) USING BTREE,
  CONSTRAINT `dp_admin_user_menu_relation_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `dp_admin_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `dp_admin_user_menu_relation_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `dp_admin_menu` (`menu_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='admin_user表和admin_menu表的关系表';
