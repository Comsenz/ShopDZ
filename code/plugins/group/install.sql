
CREATE TABLE `__PREFIX__group_set` (
  `group_img` varchar(120) DEFAULT '' COMMENT '拼团封面',
  `group_content` text COMMENT '拼团玩法'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `__PREFIX__group_join` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `group_id` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '开团表id',
  `active_id` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '活动id，group表id',
  `order_sn` bigint(20) unsigned NOT NULL COMMENT '订单编号',
  `pay_sn` bigint(20) unsigned NOT NULL COMMENT '支付单号',
  `buyer_id` int(11) unsigned NOT NULL COMMENT '买家id',
  `buyer_name` varchar(50) NOT NULL DEFAULT '' COMMENT '买家姓名',
  `buyer_email` varchar(80) DEFAULT NULL COMMENT '买家电子邮箱',
  `buyer_phone` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '买家手机',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单生成时间',
  `payment_code` char(10) NOT NULL DEFAULT '' COMMENT '支付方式名称代码',
  `payment_time` int(10) unsigned DEFAULT '0' COMMENT '支付(付款)时间',
  `payment_starttime` int(10) unsigned DEFAULT '0' COMMENT '支付发起时间',
  `goods_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品总价格',
  `order_amount` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '订单总价格',
  `order_from` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1WEB2mobile',
  `invisible` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0是正常，-1 是没有在规定时间内付款删除',
  `shipping_fee` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '物流费用',
  `trade_no` varchar(50) DEFAULT NULL COMMENT '外部交易订单号',
  `refund_trade_no` varchar(50) DEFAULT NULL COMMENT '退款外部交易订单号',
  `refund_back_time` int(10) unsigned DEFAULT '0' COMMENT '成功退款时间',
  `refund_payment_code` varchar(50) DEFAULT NULL COMMENT '退款支付方式名称代码',
  `refund_time` int(10) unsigned DEFAULT '0' COMMENT '退款执行时间',
  `refund_status` tinyint(5) DEFAULT '0' COMMENT '0 默认，1退款成功 -1 退款失败 2 支付成功',
  `refund_order_sn` varchar(50) DEFAULT NULL COMMENT '退款订单号',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_sn` (`order_sn`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='订单参与表';

CREATE TABLE `__PREFIX__group_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '拼团id',
  `active_id` int(20) unsigned NOT NULL DEFAULT '0' COMMENT '活动id，group表id',
  `buyer_id` int(11) unsigned NOT NULL COMMENT '买家id',
  `buyer_name` varchar(50) NOT NULL DEFAULT '' COMMENT '买家姓名',
  `buyer_email` varchar(80) DEFAULT NULL COMMENT '买家电子邮箱',
  `buyer_phone` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '买家手机',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态0 进行中 1 已成功 -1失败',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '团成员数量',
  `version` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '版本号',
  `add_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单生成时间',
  `group_cron` tinyint(1) DEFAULT '0' COMMENT '拼团关闭后执行计划任务的标志 默认0， 1表示执行',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单开团表';

CREATE TABLE `__PREFIX__group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' id',
  `group_name` varchar(255) NOT NULL DEFAULT '' COMMENT '活动名称',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '显示商品名称',
  `goods_id` varchar(255) NOT NULL DEFAULT '' COMMENT '拼团对应的id',
  `group_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '拼团价格',
  `group_content` varchar(255) NOT NULL DEFAULT '' COMMENT '拼团商品简介',
  `group_person_num` int(11) NOT NULL DEFAULT '0' COMMENT '成团人数',
  `max_ok_group` int(11) NOT NULL DEFAULT '0' COMMENT '最大成团数量',
  `max_group` int(11) NOT NULL DEFAULT '0' COMMENT '最大开团数量',
  `add_num` int(11) NOT NULL DEFAULT '0' COMMENT '每人参团限制',
  `group_hour` int(11) NOT NULL DEFAULT '0' COMMENT '组团时限',
  `starttime` int(11) NOT NULL DEFAULT '0' COMMENT '组团生效时间',
  `endtime` int(11) NOT NULL DEFAULT '0' COMMENT '组团过期时间',
  `add_time` int(11) NOT NULL DEFAULT '0',
  `group_image` varchar(255) DEFAULT NULL COMMENT ' 拼团商品封面',
  `is_shipping` tinyint(1) unsigned DEFAULT '0' COMMENT '是否免邮 0 免物流 1 正常物流',
  `stype` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0正常 -1 删除',
  `active_end_time` int(11) NOT NULL DEFAULT '0' COMMENT '该活动所有组团最长过期时间，结束时间+活动时长',
  `head_welfare` varchar(10) CHARACTER SET utf8mb4 DEFAULT 'none' COMMENT '团长福利内容  购买立减  折扣 无福利',
  `head_num` varchar(110) DEFAULT NULL COMMENT '福利内容数量',
  `head_welfare_type` varchar(10) DEFAULT 'none' COMMENT '福利类型',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='拼团表';