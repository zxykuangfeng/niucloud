DROP TABLE IF EXISTS `{{prefix}}zzhc_coupon`;
CREATE TABLE `{{prefix}}zzhc_coupon` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '发放数量',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠券面额',
  `lead_count` int(11) NOT NULL DEFAULT '0' COMMENT '已领取数量',
  `used_count` int(11) NOT NULL DEFAULT '0' COMMENT '已使用数量',
  `atleast` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '满额使用',
  `is_show` tinyint(4) NOT NULL DEFAULT '0' COMMENT '允许领取:0=否,1=是',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '折扣',
  `validity_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '过期类型:0=天数,1=固定时间,2=长期有效',
  `end_usetime` int(11) NOT NULL DEFAULT '0' COMMENT '使用结束时间',
  `fixed_term` int(11) NOT NULL DEFAULT '0' COMMENT '有效天数',
  `max_fetch` int(11) NOT NULL DEFAULT '0' COMMENT '限领张数',
  `receive_time_type` tinyint(4) NOT NULL DEFAULT '2' COMMENT '领取时间类型',
  `start_time` int(11) NOT NULL DEFAULT '0' COMMENT '开始领取时间',
  `end_time` int(11) NOT NULL DEFAULT '0' COMMENT '结束领取时间',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`coupon_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='优惠券';


DROP TABLE IF EXISTS `{{prefix}}zzhc_coupon_member`;
CREATE TABLE `{{prefix}}zzhc_coupon_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `coupon_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠券id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '优惠券名称',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `headimg` varchar(2000) NOT NULL DEFAULT '' COMMENT '会员头像',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '会员手机号',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '领取时间',
  `expire_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '过期时间',
  `use_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '使用时间',
  `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '面值',
  `atleast` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '满额使用',
  `receive_type` varchar(255) NOT NULL DEFAULT '' COMMENT '领取方式',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用订单id',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `coupon_id` (`coupon_id`),
  KEY `member_id` (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='优惠券领取记录表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_goods`;
CREATE TABLE `{{prefix}}zzhc_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `category_id` int(11) NOT NULL COMMENT '分类id',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名称',
  `goods_image` varchar(2000) NOT NULL DEFAULT '' COMMENT '项目图片',
  `duration` int(11) NOT NULL COMMENT '服务时长(分钟)',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '项目价格',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态:up=上架,down=下架',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`goods_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='项目表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_goods_category`;
CREATE TABLE `{{prefix}}zzhc_goods_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `category_name` varchar(255) NOT NULL DEFAULT '' COMMENT '分类名称',
  `category_image` varchar(2000) DEFAULT '' COMMENT '分类图片',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态:normal=显示,hidden=隐藏',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`category_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='项目分类表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_order`;
CREATE TABLE `{{prefix}}zzhc_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `store_id` int(11) NOT NULL DEFAULT '0' COMMENT '门店id',
  `order_no` varchar(60) NOT NULL DEFAULT '' COMMENT '订单编号',
  `order_type` varchar(60) NOT NULL DEFAULT '' COMMENT '订单类型',
  `order_from` varchar(100) NOT NULL DEFAULT '' COMMENT '订单来源',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `headimg` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员头像',
  `mobile` varchar(20) DEFAULT NULL COMMENT '会员手机号',
  `staff_id` int(11) NOT NULL COMMENT '发型师id',
  `staff_headimg` varchar(2000) NOT NULL COMMENT '发型师头像',
  `staff_name` varchar(255) NOT NULL DEFAULT '' COMMENT '发型师姓名',
  `goods_id` int(11) NOT NULL DEFAULT '0' COMMENT '项目id',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '项目名称',
  `goods_image` varchar(2000) NOT NULL DEFAULT '' COMMENT '项目图片',
  `duration` int(11) NOT NULL DEFAULT '0' COMMENT '服务时长(分钟)',
  `goods_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '项目价格',
  `goods_vip_money` decimal(10,2) NOT NULL COMMENT 'VIP价格',
  `is_vip` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否VIP',
  `discount_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '优惠金额',
  `order_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `out_trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '支付流水号',
  `coupon_member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员优惠券id',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单状态:-1=已取消,1=待服务,2=服务中,3=待支付,5=已完成',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '会员ip',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_id`) USING BTREE,
  UNIQUE KEY `order_sn` (`order_no`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `createtime` (`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='预约订单表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_order_log`;
CREATE TABLE `{{prefix}}zzhc_order_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `site_id` int(11) NOT NULL COMMENT '站点id',
  `order_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单id',
  `main_type` varchar(255) NOT NULL DEFAULT '操作人类型' COMMENT '操作人类型',
  `main_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `status` int(11) DEFAULT NULL COMMENT '订单状态',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '操作类型',
  `content` varchar(255) DEFAULT NULL COMMENT '日志内容',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='订单操作日志表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_staff`;
CREATE TABLE `{{prefix}}zzhc_staff` (
  `staff_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `store_id` int(11) NOT NULL COMMENT '上班门店',
  `member_id` int(11) DEFAULT NULL COMMENT '绑定会员',
  `staff_role` varchar(255) NOT NULL DEFAULT '' COMMENT '员工角色',
  `staff_headimg` varchar(2000) NOT NULL DEFAULT '' COMMENT '员工头像',
  `staff_name` varchar(255) NOT NULL DEFAULT '' COMMENT '员工姓名',
  `staff_position` varchar(255) NOT NULL DEFAULT '' COMMENT '员工职位',
  `staff_experience` varchar(255) NOT NULL COMMENT '工作经验',
  `staff_content` text COMMENT '员工介绍',
  `staff_mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `staff_image` text NOT NULL COMMENT '员工照片',
  `work_id` int(11) NOT NULL DEFAULT '0' COMMENT '当前考勤id',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(255) NOT NULL DEFAULT ' work' COMMENT '状态:normal=在职,quit=离职',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`staff_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='员工表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_staff_work`;
CREATE TABLE `{{prefix}}zzhc_staff_work` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `store_id` varchar(255) NOT NULL DEFAULT '' COMMENT '门店id',
  `staff_id` int(11) NOT NULL DEFAULT '0' COMMENT '员工id',
  `status` varchar(30) NOT NULL COMMENT '考勤状态:working=上班中,meal=用餐中,thing=处理事情中,stop=停止接单,rest=下班休息',
  `duration` int(11) DEFAULT '0' COMMENT '预计用时(分钟)',
  `full_address` varchar(255) DEFAULT NULL COMMENT '位置',
  `longitude` varchar(255) DEFAULT NULL COMMENT '经度',
  `latitude` varchar(255) DEFAULT NULL COMMENT '纬度',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='员工考勤记录表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_store`;
CREATE TABLE `{{prefix}}zzhc_store` (
  `store_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `store_logo` varchar(255) NOT NULL DEFAULT '' COMMENT '门店logo',
  `store_name` varchar(255) NOT NULL DEFAULT '' COMMENT '门店名称',
  `trade_time` varchar(255) NOT NULL DEFAULT '' COMMENT '营业时间',
  `store_contacts` varchar(255) NOT NULL DEFAULT '' COMMENT '联系人',
  `store_mobile` varchar(255) NOT NULL DEFAULT '' COMMENT '联系电话',
  `store_image` text NOT NULL COMMENT '门店图片',
  `store_content` text NOT NULL COMMENT '门店介绍',
  `province_id` int(11) NOT NULL DEFAULT '0' COMMENT '省id',
  `city_id` int(11) NOT NULL DEFAULT '0' COMMENT '市',
  `district_id` int(11) NOT NULL DEFAULT '0' COMMENT '县（区）',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
  `full_address` varchar(255) NOT NULL DEFAULT '' COMMENT '完整地址',
  `longitude` varchar(255) NOT NULL DEFAULT '' COMMENT '经度',
  `latitude` varchar(255) NOT NULL DEFAULT '' COMMENT '纬度',
  `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态:normal=正常,shut=停业',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`store_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='门店表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_vip`;
CREATE TABLE `{{prefix}}zzhc_vip` (
  `vip_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `vip_name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
  `days` int(11) NOT NULL COMMENT '时长(天)',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` varchar(30) NOT NULL DEFAULT 'up' COMMENT '状态:up=上架,down=下架',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`vip_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='VIP会员套餐表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_vip_log`;
CREATE TABLE `{{prefix}}zzhc_vip_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `vip_member_id` int(11) NOT NULL DEFAULT '0' COMMENT 'VIP会员id',
  `main_type` varchar(255) NOT NULL DEFAULT '操作人类型' COMMENT '操作人类型',
  `main_id` int(11) NOT NULL DEFAULT '0' COMMENT '操作人id',
  `days` int(11) DEFAULT NULL COMMENT '时长(天)',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '操作类型',
  `content` varchar(255) DEFAULT NULL COMMENT '日志内容',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='VIP会员操作日志表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_vip_member`;
CREATE TABLE `{{prefix}}zzhc_vip_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `headimg` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员头像',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '会员手机号',
  `overdue_time` int(11) NOT NULL DEFAULT '0' COMMENT '到期时间',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `createtime` (`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='办卡会员表';


DROP TABLE IF EXISTS `{{prefix}}zzhc_vip_order`;
CREATE TABLE `{{prefix}}zzhc_vip_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '站点id',
  `order_no` varchar(60) NOT NULL DEFAULT '' COMMENT '订单编号',
  `order_type` varchar(60) NOT NULL DEFAULT '' COMMENT '订单类型',
  `order_from` varchar(100) NOT NULL DEFAULT '' COMMENT '订单来源',
  `member_id` int(11) NOT NULL DEFAULT '0' COMMENT '会员id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '会员昵称',
  `headimg` varchar(1000) NOT NULL DEFAULT '' COMMENT '会员头像',
  `mobile` varchar(20) DEFAULT NULL COMMENT '会员手机号',
  `vip_id` int(11) NOT NULL DEFAULT '0' COMMENT 'VIPid',
  `vip_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'VIP名称',
  `days` int(11) NOT NULL DEFAULT '0' COMMENT '时长(天)',
  `vip_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'VIP金额',
  `order_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `pay_time` int(11) NOT NULL DEFAULT '0' COMMENT '支付时间',
  `out_trade_no` varchar(100) NOT NULL DEFAULT '' COMMENT '支付流水号',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单状态:1=待支付,2=已支付',
  `ip` varchar(100) NOT NULL DEFAULT '' COMMENT '会员ip',
  `create_time` int(11) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`order_id`) USING BTREE,
  UNIQUE KEY `order_sn` (`order_no`) USING BTREE,
  KEY `status` (`status`) USING BTREE,
  KEY `createtime` (`create_time`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci COMMENT='办卡订单表';
