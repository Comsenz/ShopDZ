-- ----------------------------
-- Table structure for `pre_admin`
-- ----------------------------
DROP TABLE IF EXISTS `pre_admin`;
CREATE TABLE `pre_admin` (
  `uid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL DEFAULT '' COMMENT '用户名',
  `groupid` int(4) unsigned NOT NULL DEFAULT 0 COMMENT 'id',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `email` varchar(32) NOT NULL DEFAULT '' COMMENT '电子邮件',
  `salt` varchar(32) NOT NULL DEFAULT '' COMMENT '盐值',
  `ip` varchar(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `isfounder` tinyint(4) unsigned NOT NULL DEFAULT 0 COMMENT ' ',
  `lastdateline` int(6) unsigned NOT NULL DEFAULT 0,
  `dateline` int(10) unsigned NOT NULL default 0,
  `avatar` varchar(50) DEFAULT NULL COMMENT '头像',
  `open_id` varchar(255) DEFAULT NULL default '',
  `statues` varchar(5) DEFAULT NULL default '',
  PRIMARY KEY (`uid`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
