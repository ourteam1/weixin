/*
SQLyog Enterprise - MySQL GUI v7.11 
MySQL - 5.5.37-log : Database - wanzhuanhua
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`wanzhuanhua` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `wanzhuanhua`;

/*Table structure for table `ms_admin` */

DROP TABLE IF EXISTS `ms_admin`;

CREATE TABLE `ms_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `account` varchar(32) NOT NULL COMMENT '登录帐号',
  `password` varchar(64) NOT NULL COMMENT '登录密码',
  `last_login_ip` varchar(20) DEFAULT NULL COMMENT '最后登录IP',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `modify_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`admin_id`,`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `ms_admin` */

insert  into `ms_admin`(`admin_id`,`account`,`password`,`last_login_ip`,`last_login_time`,`modify_time`,`create_time`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','127.0.0.1','2015-07-04 18:21:08','2015-07-04 18:21:08',NULL);

/*Table structure for table `ms_cart` */

DROP TABLE IF EXISTS `ms_cart`;

CREATE TABLE `ms_cart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `user_id` varchar(32) NOT NULL COMMENT '消费者帐号',
  `session_id` varchar(32) NOT NULL COMMENT '消费者帐号',
  `goods_id` int(11) NOT NULL COMMENT '商品编号',
  `goods_price` float DEFAULT NULL COMMENT '下单时商品的价格',
  `goods_score` int(10) NOT NULL DEFAULT '0' COMMENT '商品积分',
  `cart_num` int(11) DEFAULT '0' COMMENT '商品购买数量',
  `create_time` datetime NOT NULL COMMENT '购物车添加时间',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ms_cart` */

insert  into `ms_cart`(`cart_id`,`user_id`,`session_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`,`create_time`) values (7,'1','cer3gua724pu5k5fvug3rt7053',15,0,100,1,'2015-07-04 19:28:30');

/*Table structure for table `ms_category` */

DROP TABLE IF EXISTS `ms_category`;

CREATE TABLE `ms_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '商品分类编号',
  `category_name` varchar(32) NOT NULL COMMENT '商品分类',
  `location` varchar(32) NOT NULL COMMENT '排序',
  `status` int(1) DEFAULT NULL COMMENT '状态，0=删除，1=正常',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_category` */

insert  into `ms_category`(`category_id`,`category_name`,`location`,`status`) values (1,'礼品专区','1',1);
insert  into `ms_category`(`category_id`,`category_name`,`location`,`status`) values (2,'爆款专区','2',1);

/*Table structure for table `ms_feedback` */

DROP TABLE IF EXISTS `ms_feedback`;

CREATE TABLE `ms_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `content` varchar(256) NOT NULL COMMENT '建议内容',
  `username` varchar(32) DEFAULT NULL COMMENT '姓名',
  `mobile` varchar(32) DEFAULT NULL COMMENT '联系方式',
  `user_id` varchar(32) DEFAULT NULL COMMENT '填写建议者',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_feedback` */

insert  into `ms_feedback`(`id`,`content`,`username`,`mobile`,`user_id`,`create_time`) values (1,'继续努力啊','吴桐','18510381580','1','2014-09-06 00:34:20');
insert  into `ms_feedback`(`id`,`content`,`username`,`mobile`,`user_id`,`create_time`) values (2,'我都买这么多菜，咋还不送来','123','13611341796','4','2014-09-10 20:57:27');

/*Table structure for table `ms_free_get_score` */

DROP TABLE IF EXISTS `ms_free_get_score`;

CREATE TABLE `ms_free_get_score` (
  `code` int(32) NOT NULL COMMENT '兑换码',
  `user_id` int(20) NOT NULL COMMENT '用户ID',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_free_get_score` */

insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (3,20,'2015-07-02 19:57:35');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (1,20,'2015-07-02 19:57:42');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (1,21,'2015-07-03 14:26:43');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (1,22,'2015-07-03 14:37:50');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (2,22,'2015-07-03 14:38:47');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (3,22,'2015-07-03 14:38:49');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (4,22,'2015-07-03 14:43:07');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (5,22,'2015-07-03 14:43:09');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (2,21,'2015-07-03 20:29:35');
insert  into `ms_free_get_score`(`code`,`user_id`,`create_time`) values (2,1,'2015-07-04 19:17:00');

/*Table structure for table `ms_goods` */

DROP TABLE IF EXISTS `ms_goods`;

CREATE TABLE `ms_goods` (
  `goods_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `goods_name` varchar(32) NOT NULL COMMENT '商品名称',
  `goods_sn` varchar(32) NOT NULL COMMENT '商品编号',
  `image` varchar(128) DEFAULT NULL COMMENT '商品图片',
  `thumb` varchar(128) DEFAULT NULL COMMENT '商品缩略图',
  `price` float DEFAULT NULL COMMENT '价格',
  `discount` float DEFAULT NULL COMMENT '优惠价，如果为空，就不显示；如果不为空，价格就划横线',
  `score` int(11) NOT NULL COMMENT '购买商品使用积分数',
  `category_id` int(4) NOT NULL COMMENT '商品分类编号',
  `order_number` int(11) DEFAULT '0' COMMENT '下单次数',
  `status` int(1) DEFAULT NULL COMMENT '状态，0=删除，1=上架，2=下架',
  `desc` text COMMENT '描述信息',
  `modify_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`goods_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*Data for the table `ms_goods` */

insert  into `ms_goods`(`goods_id`,`goods_name`,`goods_sn`,`image`,`thumb`,`price`,`discount`,`score`,`category_id`,`order_number`,`status`,`desc`,`modify_time`,`create_time`) values (14,'莲藕排骨汤','20140903064357','78dfa7f6a5c0c39dddc884575907104bf8dd5920.jpg','cb49e6c889e9a81d348f84d80a4bfa6182d5d24c.jpg',0,0,120,1,12,1,'<h3>\n	食材\n</h3>\n主料：排骨500g、莲藕600g<br />\n辅料：清水900ml、蜜枣1粒<br />\n<br />\n<h3>\n	步骤\n</h3>\n<p>\n	<br />\n</p>\n<div class=\"intro\" style=\"margin:0px;padding:0px;color:#333333;font-family:Tahoma, Arial, Helvetica, 宋体, \'Arial Narrow\', Geneva, sans-serif;\">\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/3bf785fb8bd63e6dca685b1625ce62edd36ca934.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">1.</span>食材：<a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>和<a href=\"http://www.haodou.com/recipe/all/8\" target=\"_blank\">排骨</a>。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/30d10abefbd7d72f198644af3398b040e1815596.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">2.</span><a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>洗净，去皮，切块。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/3be3ef1a9aa96c3e7966a6bda0bdbb1f745933c8.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">3.</span><a href=\"http://www.haodou.com/recipe/all/8\" target=\"_blank\">排骨</a>去血水，用大火烧开，转小火煮30分钟。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/79ef4e1850a11de5caa7c3edde96a7c2c997b862.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">4.</span>放入<a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/e02667630410adca584144a6f76755c651f5a6b1.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">5.</span>盖锅盖，大火烧开，转小火煲25分钟。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<img src=\"http://115.28.80.161/imaged/29c441254f4403cfaaa4d120cc60045a61f52402.jpg\" alt=\"\" /> \n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">6.</span>熄火盛出。\n	</p>\n</div>\n<span style=\"background-color:#FF9900;\">小贴士</span><span id=\"stips\" style=\"background-color:#FF9900;\">莲藕与菊花同食，很可能会导致肠胃不适。</span><span style=\"background-color:#FF9900;\"></span> \n<p>\n	<br />\n</p>','2015-07-04 18:39:54','2014-09-03 06:43:57');
insert  into `ms_goods`(`goods_id`,`goods_name`,`goods_sn`,`image`,`thumb`,`price`,`discount`,`score`,`category_id`,`order_number`,`status`,`desc`,`modify_time`,`create_time`) values (15,'冬瓜玉米排骨汤','20140903064753','6bedc1e7546c334372c13feb03272bd342b9f3f0.jpg','caea6b3755d007e1c06f7857c8baac0c19638364.jpg',0,0,100,2,15,1,'<h3>\n	<p>\n		<br />\n	</p>\n	<p>\n		<span style=\"line-height:25px;font-family:微软雅黑;color:#000000;\">产品信息</span><span class=\"s2\" style=\"font-family:微软雅黑;font-size:12px;color:#000000;\">Product Information</span> \n	</p>\n	<p>\n		<br />\n	</p>\n</h3>','2015-07-04 18:39:54','2014-09-03 06:47:53');

/*Table structure for table `ms_logs` */

DROP TABLE IF EXISTS `ms_logs`;

CREATE TABLE `ms_logs` (
  `logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `username` varchar(32) DEFAULT NULL COMMENT '操作者',
  `content` varchar(256) DEFAULT NULL COMMENT '操作内容',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*Data for the table `ms_logs` */

insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (1,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 10:57:22');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (2,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 10:57:29');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (3,'admin','登陆系统','2015-07-04 11:49:34');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (4,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:49:54');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (5,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:50:28');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (6,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:52:29');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (7,'admin','登陆系统','2015-07-04 14:28:54');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (8,'admin','修改商品：莲藕排骨汤','2015-07-04 14:29:02');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (9,'admin','登陆系统','2015-07-04 15:11:40');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (10,'admin','修改订单[编号：1]状态：已确认订单','2015-07-04 16:02:25');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (11,'admin','修改订单[编号：1]状态：已出库发货','2015-07-04 16:02:32');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (12,'admin','修改订单[编号：1]状态：用户未收货，重新确认','2015-07-04 16:02:38');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (13,'admin','修改订单[编号：1]状态：已确认订单','2015-07-04 16:02:43');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (14,'admin','修改订单[编号：1]状态：已出库发货','2015-07-04 16:02:45');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (15,'admin','修改订单[编号：1]状态：用户已收货','2015-07-04 16:02:47');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (16,'admin','登陆系统','2015-07-04 18:21:08');
insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (17,'admin','修改商品：莲藕排骨汤','2015-07-04 18:21:24');

/*Table structure for table `ms_order` */

DROP TABLE IF EXISTS `ms_order`;

CREATE TABLE `ms_order` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `order_sn` varchar(32) DEFAULT NULL COMMENT '订单编号，生成规则：酒店编号+年月日时分秒+A-Z的一个随记字符',
  `user_id` varchar(32) DEFAULT NULL COMMENT '下单者帐号',
  `amount` float DEFAULT NULL COMMENT '订单金额',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '订单使用积分',
  `is_pay` int(11) DEFAULT NULL COMMENT '是否支付，0=未支付，1=已支付',
  `invoice` varchar(256) DEFAULT NULL COMMENT '订单发票抬头',
  `remark` varchar(256) DEFAULT NULL COMMENT '订单备注',
  `username` varchar(32) NOT NULL COMMENT '收货人姓名',
  `mobile` varchar(32) NOT NULL COMMENT '收货人联系方式',
  `city` varchar(32) DEFAULT NULL COMMENT '收货人所在城市',
  `area` varchar(32) DEFAULT NULL COMMENT '收货人所在区域',
  `address` varchar(256) DEFAULT NULL COMMENT '收货人联系地址',
  `delivery_times` varchar(128) DEFAULT NULL COMMENT '订单配送时间',
  `payment_model` varchar(32) DEFAULT NULL COMMENT '支付方式',
  `status` varchar(32) DEFAULT NULL COMMENT '订单状态，1=确认订单, 2=出库发货, 3=用户已收货, 4=用户未收货，重新确认',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `modify_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_order` */

insert  into `ms_order`(`order_id`,`order_sn`,`user_id`,`amount`,`score`,`is_pay`,`invoice`,`remark`,`username`,`mobile`,`city`,`area`,`address`,`delivery_times`,`payment_model`,`status`,`create_time`,`modify_time`) values (1,'20150704152949540','1',0,200,1,NULL,'','吴桐','15120026623','北京市','清华科技园','123123','15:30 - 18:00','积分支付','4','2015-07-04 15:29:49','2015-07-04 16:02:47');
insert  into `ms_order`(`order_id`,`order_sn`,`user_id`,`amount`,`score`,`is_pay`,`invoice`,`remark`,`username`,`mobile`,`city`,`area`,`address`,`delivery_times`,`payment_model`,`status`,`create_time`,`modify_time`) values (2,'20150704183954804','1',0,340,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','1','2015-07-04 18:39:54','2015-07-04 18:39:54');

/*Table structure for table `ms_order_action` */

DROP TABLE IF EXISTS `ms_order_action`;

CREATE TABLE `ms_order_action` (
  `order_id` int(11) NOT NULL COMMENT '关联的订单号',
  `action` varchar(32) NOT NULL COMMENT '订单操作编号，1=确认订单, 2=出库发货, 3=用户已收货, 4=用户未收货，重新确认',
  `action_name` varchar(32) NOT NULL COMMENT '订单操作名称',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_order_action` */

insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.confirm','已确认订单','2015-07-04 16:02:25');
insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.shipment','已出库发货','2015-07-04 16:02:32');
insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.reconfirm','用户未收货，重新确认','2015-07-04 16:02:38');
insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.confirm','已确认订单','2015-07-04 16:02:43');
insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.shipment','已出库发货','2015-07-04 16:02:45');
insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.receipt','用户已收货','2015-07-04 16:02:47');

/*Table structure for table `ms_order_info` */

DROP TABLE IF EXISTS `ms_order_info`;

CREATE TABLE `ms_order_info` (
  `order_id` int(11) NOT NULL COMMENT '关联的订单号',
  `goods_id` int(11) NOT NULL COMMENT '关联商品编号',
  `goods_price` float DEFAULT NULL COMMENT '下单时商品的价格',
  `goods_score` int(10) NOT NULL COMMENT '商品单个积分',
  `cart_num` int(11) DEFAULT NULL COMMENT '订单数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_order_info` */

insert  into `ms_order_info`(`order_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`) values (1,15,0,100,2);
insert  into `ms_order_info`(`order_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`) values (2,14,0,120,2);
insert  into `ms_order_info`(`order_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`) values (2,15,0,100,1);

/*Table structure for table `ms_property` */

DROP TABLE IF EXISTS `ms_property`;

CREATE TABLE `ms_property` (
  `label` varchar(32) NOT NULL COMMENT '属性显示名',
  `name` varchar(32) NOT NULL COMMENT '属性名',
  `value` varchar(32) NOT NULL COMMENT '属性值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_property` */

insert  into `ms_property`(`label`,`name`,`value`) values ('未确认订单','order.action.noconfirm','1');
insert  into `ms_property`(`label`,`name`,`value`) values ('已确认订单','order.action.confirm','2');
insert  into `ms_property`(`label`,`name`,`value`) values ('已出库发货','order.action.shipment','3');
insert  into `ms_property`(`label`,`name`,`value`) values ('用户已收货','order.action.receipt','4');
insert  into `ms_property`(`label`,`name`,`value`) values ('用户未收货，重新确认','order.action.reconfirm','5');
insert  into `ms_property`(`label`,`name`,`value`) values ('货到付款','payment.model.cod','货到付款');
insert  into `ms_property`(`label`,`name`,`value`) values ('余额支付','payment.model.amount','余额支付');
insert  into `ms_property`(`label`,`name`,`value`) values ('微信支付','payment.model.weixin','微信支付');
insert  into `ms_property`(`label`,`name`,`value`) values ('积分支付','payment.model.score','积分兑换');

/*Table structure for table `ms_user` */

DROP TABLE IF EXISTS `ms_user`;

CREATE TABLE `ms_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `account` varchar(32) DEFAULT NULL COMMENT '登录帐号',
  `password` varchar(64) DEFAULT NULL COMMENT '登录密码',
  `mobile` varchar(32) DEFAULT NULL COMMENT '手机号',
  `email` varchar(32) DEFAULT NULL COMMENT 'email',
  `amount` float DEFAULT '0' COMMENT '用户账户金额',
  `score` int(10) NOT NULL DEFAULT '0' COMMENT '用户积分',
  `wx_openid` varchar(64) NOT NULL COMMENT '用户openid',
  `nickname` varchar(32) NOT NULL COMMENT '用户昵称',
  `sex` int(2) NOT NULL COMMENT '用户性别，1=男，2=女',
  `city` varchar(20) DEFAULT NULL COMMENT '用户所在城市',
  `province` varchar(20) DEFAULT NULL COMMENT '用户所在省份',
  `country` varchar(20) DEFAULT NULL COMMENT '用户所在国家',
  `headimgurl` varchar(256) DEFAULT NULL COMMENT '用户头像',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `modify_time` datetime DEFAULT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

/*Data for the table `ms_user` */

insert  into `ms_user`(`user_id`,`account`,`password`,`mobile`,`email`,`amount`,`score`,`wx_openid`,`nickname`,`sex`,`city`,`province`,`country`,`headimgurl`,`create_time`,`modify_time`) values (1,NULL,NULL,NULL,NULL,0,1659,'oVvBOs0D0iEhQ7AFUlatpNC8QYgc','吴桐',1,'','北京','中国','http://wx.qlogo.cn/mmopen/ichSre3n3PfyIYk9MVsib94V4vvHGOWibwIlT5ELOsHFiaUbmZoyV6XNJsa35Vmee56zZp6J9ON1u5OvH3G6NClBPbpqiaicibthbVX/0','2015-07-02 11:10:57','2015-07-05 07:49:42');
insert  into `ms_user`(`user_id`,`account`,`password`,`mobile`,`email`,`amount`,`score`,`wx_openid`,`nickname`,`sex`,`city`,`province`,`country`,`headimgurl`,`create_time`,`modify_time`) values (20,NULL,NULL,NULL,NULL,0,20,'oVvBOs6nPz95VuTMZ78kyHKuSeWU','李坚',1,'朝阳','北京','中国','http://wx.qlogo.cn/mmopen/ny3O7jtkDkJqldpQ9cXyR2ZicB9mOZibyj0FFoAfXNx9XFn9lGDkhhQmuabfviagZpgPDkBiaQAib5LVqtMkeAHJ998zHKLEgqBdq/0','2015-07-03 14:25:40','2015-07-03 20:29:35');
insert  into `ms_user`(`user_id`,`account`,`password`,`mobile`,`email`,`amount`,`score`,`wx_openid`,`nickname`,`sex`,`city`,`province`,`country`,`headimgurl`,`create_time`,`modify_time`) values (22,NULL,NULL,NULL,NULL,0,50,'oVvBOs2MT3qMafFpvkm7icmAM_X4','贺总',1,'昌平','北京','中国','http://wx.qlogo.cn/mmopen/ibDRic4Cogo7iaVQTqDVV5aJlj9ZQs4cujVEIT4zWUb9AqxEwOqfXTJPyqQtWOavb5UpiciaTnmw56tUbeJStxdRkkZkUVBGBZZN1/0','2015-07-03 14:37:09','2015-07-03 14:43:09');
insert  into `ms_user`(`user_id`,`account`,`password`,`mobile`,`email`,`amount`,`score`,`wx_openid`,`nickname`,`sex`,`city`,`province`,`country`,`headimgurl`,`create_time`,`modify_time`) values (23,NULL,NULL,NULL,NULL,0,0,'oVvBOs5A-_2UaqZN3bWY520SI2H8','金梧桐',2,'西城','北京','中国','http://wx.qlogo.cn/mmopen/ny3O7jtkDkJqldpQ9cXyRibCoeMZtHdiaiciannaDGG2eMJjzU0ZEPd3JyXLdicTn5ZmTmYibvXcJpA5tjjwjAaZsdxISScBFkyty4/0','2015-07-04 19:16:34','2015-07-04 19:16:34');

/*Table structure for table `ms_user_account` */

DROP TABLE IF EXISTS `ms_user_account`;

CREATE TABLE `ms_user_account` (
  `user_id` int(11) NOT NULL COMMENT '系统编号',
  `action` varchar(32) DEFAULT NULL COMMENT '操作编号',
  `action_name` varchar(32) DEFAULT NULL COMMENT '操作名称，充值、消费等',
  `amount` float DEFAULT '0' COMMENT '操作用户账户金额',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_user_account` */

insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (20,'user.score.add','增加积分10元',10,'2015-07-02 19:57:35');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (20,'user.score.add','增加积分10元',10,'2015-07-02 19:57:42');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (21,'user.score.add','增加积分10元',10,'2015-07-03 14:26:43');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (22,'user.score.add','增加积分10元',10,'2015-07-03 14:37:50');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (22,'user.score.add','增加积分10元',10,'2015-07-03 14:38:47');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (22,'user.score.add','增加积分10元',10,'2015-07-03 14:38:49');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (22,'user.score.add','增加积分10元',10,'2015-07-03 14:43:07');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (22,'user.score.add','增加积分10元',10,'2015-07-03 14:43:09');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (21,'user.score.add','增加积分10元',10,'2015-07-03 20:29:35');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.lose','订单支付积分200',200,'2015-07-04 15:29:49');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.lose','订单支付积分340',340,'2015-07-04 18:39:54');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10元',10,'2015-07-04 19:17:00');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分20',20,'2015-07-04 22:38:15');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:40:28');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分20',20,'2015-07-04 22:42:52');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:43:05');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:43:16');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分2',2,'2015-07-04 22:43:28');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分5',5,'2015-07-04 22:43:39');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:43:50');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分2',2,'2015-07-04 22:45:16');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:45:28');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分10',10,'2015-07-04 22:45:40');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分30',30,'2015-07-04 22:45:49');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分30',30,'2015-07-05 05:06:05');
insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (1,'user.score.add','增加积分20',20,'2015-07-05 07:49:42');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
