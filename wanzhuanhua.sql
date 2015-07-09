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

/*Table structure for table `ms_activity` */

DROP TABLE IF EXISTS `ms_activity`;

CREATE TABLE `ms_activity` (
  `activity_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '活动ID',
  `activity_code` varchar(30) NOT NULL COMMENT '活动编号',
  `activity_name` varchar(50) NOT NULL COMMENT '活动名称',
  `activity_img` varchar(200) NOT NULL DEFAULT '' COMMENT '活动图片',
  `activity_score` smallint(5) NOT NULL DEFAULT '0' COMMENT '兑换金币',
  `start_time` datetime NOT NULL COMMENT '有效期，开始时间',
  `end_time` datetime NOT NULL COMMENT '有效期，结束时间',
  `detail` text COMMENT '详情',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`activity_id`),
  KEY `code_start_end_time` (`activity_code`,`start_time`,`end_time`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_activity` */

insert  into `ms_activity`(`activity_id`,`activity_code`,`activity_name`,`activity_img`,`activity_score`,`start_time`,`end_time`,`detail`,`create_time`) values (1,'4201411266745','物美云活动测试单,优惠1分钱。','http://42.62.73.239:8080/pic/cloudserver/3201408226425.bmp',1,'2015-05-01 06:00:00','2015-07-28 23:59:59','便利店不参加活动。','2015-07-08 07:41:09'),(2,'3201408016361','云活动测试单','http://42.62.73.239:8080/pic/cloudserver/3201408016361.bmp',2,'2014-10-20 06:00:00','2015-07-31 23:59:59','测试活动','2015-07-08 07:41:09');

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

insert  into `ms_admin`(`admin_id`,`account`,`password`,`last_login_ip`,`last_login_time`,`modify_time`,`create_time`) values (1,'admin','21232f297a57a5a743894a0e4a801fc3','127.0.0.1','2015-07-09 12:29:07','2015-07-09 12:29:07',NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `ms_cart` */

insert  into `ms_cart`(`cart_id`,`user_id`,`session_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`,`create_time`) values (7,'1','cer3gua724pu5k5fvug3rt7053',15,0,100,1,'2015-07-04 19:28:30'),(8,'1','adbcri6s553a34i9jk251lu836',14,0,120,1,'2015-07-05 12:38:04'),(9,'1','9d708rkgi2fc94gvmtp8gv88o3',14,0,120,9,'2015-07-05 21:36:16'),(10,'1','vdnfr9aibpfeds464ekqor6oh3',14,0,120,1,'2015-07-06 09:12:48'),(15,'1','d29pep7uq92ghl5p1mpd365bv7',15,0,100,1,'2015-07-07 10:08:34'),(16,'1','1e56dm5se2tkkvmmj65lm0bqa1',14,0,120,1,'2015-07-07 10:09:30'),(17,'1','4crflu36jf6flmhvohsgt98821',14,0,120,1,'2015-07-07 10:25:13'),(18,'1','3mfjmvgu5mcdlhfrta33h9qpu7',14,0,120,1,'2015-07-07 14:45:05'),(22,'1','iqj8m4828tlnjsiij34eoukop7',15,0,100,1,'2015-07-07 23:03:30'),(23,'1','iqj8m4828tlnjsiij34eoukop7',17,53,100,1,'2015-07-07 23:03:30'),(24,'1','iqj8m4828tlnjsiij34eoukop7',18,20,100,1,'2015-07-07 23:03:30'),(25,'1','1k7ficrc3pf01j2aq1bqearts1',16,0,100,1,'2015-07-07 23:13:20'),(26,'1','1k7ficrc3pf01j2aq1bqearts1',17,53,100,1,'2015-07-07 23:13:20'),(27,'1','1k7ficrc3pf01j2aq1bqearts1',18,20,100,1,'2015-07-07 23:13:20'),(29,'1','ao5mp6utdehmnnfft147akafg3',16,0,100,1,'2015-07-09 00:07:49');

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

insert  into `ms_category`(`category_id`,`category_name`,`location`,`status`) values (1,'礼品专区','1',1),(2,'爆款专区','2',1);

/*Table structure for table `ms_favorable` */

DROP TABLE IF EXISTS `ms_favorable`;

CREATE TABLE `ms_favorable` (
  `favorable_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `activity_code` varchar(30) NOT NULL COMMENT '活动编码',
  `favorable_code` varchar(30) NOT NULL COMMENT '优惠码',
  `user_id` int(10) NOT NULL COMMENT '用户ID',
  `create_time` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`favorable_id`),
  KEY `code_user` (`activity_code`,`user_id`),
  KEY `favorable_code` (`favorable_code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `ms_favorable` */

insert  into `ms_favorable`(`favorable_id`,`activity_code`,`favorable_code`,`user_id`,`create_time`) values (1,'4201411266745','7674500000735',1,'2015-07-08 08:06:36'),(2,'3201408016361','7636100000669',1,'2015-07-08 09:03:34');

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

insert  into `ms_feedback`(`id`,`content`,`username`,`mobile`,`user_id`,`create_time`) values (1,'继续努力啊','吴桐','18510381580','1','2014-09-06 00:34:20'),(2,'我都买这么多菜，咋还不送来','123','13611341796','4','2014-09-10 20:57:27');

/*Table structure for table `ms_focus` */

DROP TABLE IF EXISTS `ms_focus`;

CREATE TABLE `ms_focus` (
  `focus_id` int(10) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `name` varchar(220) NOT NULL DEFAULT '' COMMENT '标题',
  `icon` varchar(100) NOT NULL DEFAULT '' COMMENT 'icon图标',
  `url` varchar(100) NOT NULL DEFAULT '' COMMENT '跳转地址',
  `content` text COMMENT '内容',
  `sort` smallint(10) NOT NULL DEFAULT '99' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '删除,0:删除 1:正常',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`focus_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `ms_focus` */

insert  into `ms_focus`(`focus_id`,`name`,`icon`,`url`,`content`,`sort`,`status`,`create_time`) values (1,'didid','fafa5efeaf3cbe3b23b2748d13e629a1.jpg','http://www.baidu.com',NULL,1,0,'0000-00-00 00:00:00'),(2,'didi','','http://www.baidu.com',NULL,2,1,'2015-07-06 19:15:38'),(3,'kuaidi','350f4574ae0667391ae01b0019c9dbe9.png','http://www.kuaidadi.com/',NULL,3,1,'2015-07-06 19:17:10');

/*Table structure for table `ms_focus_user` */

DROP TABLE IF EXISTS `ms_focus_user`;

CREATE TABLE `ms_focus_user` (
  `code` int(32) NOT NULL COMMENT '兑换码',
  `user_id` int(20) NOT NULL COMMENT '用户ID',
  `create_time` datetime DEFAULT NULL COMMENT '添加时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_focus_user` */

insert  into `ms_focus_user`(`code`,`user_id`,`create_time`) values (2,1,'0000-00-00 00:00:00'),(3,1,'2015-07-06 19:25:38');

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

/*Data for the table `ms_goods` */

insert  into `ms_goods`(`goods_id`,`goods_name`,`goods_sn`,`image`,`thumb`,`price`,`discount`,`score`,`category_id`,`order_number`,`status`,`desc`,`modify_time`,`create_time`) values (14,'莲藕排骨汤','20140903064357','b7cf2340572c0d979fff5bcf9ce85fd2.jpg','facfb3654cffacfa6ab671f5c0dca862.jpg',0,0,120,1,14,2,'<h3>\n	食材\n</h3>\n主料：排骨500g、莲藕600g<br />\n辅料：清水900ml、蜜枣1粒<br />\n<br />\n<h3>\n	步骤\n</h3>\n<p>\n	<img src=\"/wutong/wzh/upload/data/bbb24c54043345eee31785d478c6b68f.png\" alt=\"\" /> \n</p>\n<div class=\"intro\" style=\"margin:0px;padding:0px;color:#333333;font-family:Tahoma, Arial, Helvetica, 宋体, \'Arial Narrow\', Geneva, sans-serif;\">\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">1.</span>食材：<a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>和<a href=\"http://www.haodou.com/recipe/all/8\" target=\"_blank\">排骨</a>。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">2.</span><a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>洗净，去皮，切块。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">3.</span><a href=\"http://www.haodou.com/recipe/all/8\" target=\"_blank\">排骨</a>去血水，用大火烧开，转小火煮30分钟。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">4.</span>放入<a href=\"http://www.haodou.com/recipe/all/1983\" target=\"_blank\">莲藕</a>。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">5.</span>盖锅盖，大火烧开，转小火煲25分钟。\n	</p>\n	<div class=\"imit_m\" style=\"margin:0px;padding:0px;\">\n		<br />\n	</div>\n	<p class=\"sstep\" style=\"margin-left:20px;font-size:14px;\">\n		<span style=\"font-size:20px;line-height:normal;font-family:Tahoma, sans-serif;color:#BBBBBB;\">6.</span>熄火盛出。\n	</p>\n</div>\n<span style=\"background-color:#FF9900;\">小贴士</span><span id=\"stips\" style=\"background-color:#FF9900;\">莲藕与菊花同食，很可能会导致肠胃不适。</span><span style=\"background-color:#FF9900;\"></span> \n<p>\n	<br />\n</p>','2015-07-07 23:32:45','2014-09-03 06:43:57'),(15,'冬瓜玉米排骨汤','20140903064753','b7cf2340572c0d979fff5bcf9ce85fd2.jpg','facfb3654cffacfa6ab671f5c0dca862.jpg',0,0,100,2,17,2,'<h3>\n	<p>\n		33333333333333333333\n	</p>\n	<p>\n		<span style=\"line-height:25px;font-family:微软雅黑;color:#000000;\">产品信息</span><span class=\"s2\" style=\"font-family:微软雅黑;font-size:12px;color:#000000;\">Product Information</span> \n	</p>\n	<p>\n		<span class=\"s2\" style=\"font-family:微软雅黑;font-size:12px;color:#000000;\"><br />\n</span> \n	</p>\n	<p>\n		<img src=\"http://115.28.80.161/wutong/wzh/upload/data/b7cf2340572c0d979fff5bcf9ce85fd2.jpg\" alt=\"\" width=\"350\" height=\"350\" title=\"\" align=\"\" /> \n	</p>\n	<p>\n		5555555555555555555\n	</p>\n</h3>','2015-07-07 23:32:42','2014-09-03 06:47:53'),(16,'领导者 包','20150707160033','d01ebdd933ebd7183f3f941e65fd7fa4.jpg','bb317425f5dccca9c0e8ddf605384d28.jpg',0,0,100,1,4,1,'<p>\n	<span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\">如果你是一个只有黑白两个色彩的个性女孩的话，那么现在你已经全部掌握了茵曼包包的搭配方法，如果你是一个彩色的时尚女孩的话，那么不妨在接下来的文章中一起来学一下其它色彩的搭配方法吧！一款好的茵曼包包不仅是你出行的好伴侣，而且还可以给你的整体着装起到锦上添花的作用，也许没有茵曼包包你不会觉得怎样，但如果拥有之后再失去的话，想必你定会怅然若失的。想要买到一款正宗的茵曼包包那也不是一件简单的事情哦，需要做的事情还有很多，那么接下来我们就从以下三个方面和大家分享一下购买在正品</span><a target=\"_self\" href=\"http://brand.vip.com/inman/\">茵曼</a><span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\">包</span>\n</p>\n<p>\n	<span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\"><img src=\"http://115.28.80.161/wutong/wzh/upload/data/4043cb8263ab60ba50e3725337177486.jpg\" alt=\"\" /><img src=\"http://115.28.80.161/wutong/wzh/upload/data/d01ebdd933ebd7183f3f941e65fd7fa4.jpg\" alt=\"\" /><br />\n</span>\n</p>\n<p>\n	<span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\"><br />\n</span>\n</p>\n<p>\n	<span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\"><span style=\"color:#666666;font-family:arial, 宋体, sans-serif;line-height:24px;background-color:#FFFFFF;\">都需要怎样去做！看茵曼包包看就是看外观，有条件的朋友可以对花对版，或者找可以清楚区别的网站图片或书籍，这里说明下最好不要找一些国外的二手包包杂志来比，那样不准确，以后 我会说明原因。另看手工及线迹，具体标准只能靠你来判断了，除非你能找到专家或行家在旁做讲解。越顺眼的卡思乐包包的质量就越好。摸——感觉茵曼包包的材料的手感，一般手感越好的材料就越好，（这里并不是说越软就越好哦）有的款式，是硬质感的用料的，看款式的，正品也一样，但是只要是正版的，或比较好的超A货，它们的革是越用越软的，还会出现很好看的油亮的光泽每个女人都至少应该拥有一款自己喜欢的包包，那么茵曼包包会不会是你最睿智的选择呢？如果你不知道该如何去挑选一款好的卡思乐女包，那么今天就让茵曼包包和你一起聊一聊选择女包的那点事儿</span><br />\n</span>\n</p>','2015-07-09 00:05:27','2015-07-07 16:00:33'),(17,'外交官 ','20150707160807','f39e04af3a65e46efea1163000244459.jpg','13452ed2ec41f43e7e8f637e7733dfa1.jpg',53,0,100,2,0,1,'<p>\n	&nbsp;阿斯蒂芬啊&nbsp;\n</p>\n<p>\n	<br />\n</p>\n<p>\n	水电费等双方打阿斯蒂芬阿斯蒂芬俺的沙发&nbsp;<img src=\"http://115.28.80.161/wutong/wzh/upload/data/609c3ae3bfd89549c49316dee70f5164.jpg\" alt=\"\" />\n</p>','2015-07-07 16:10:47','2015-07-07 16:08:07'),(18,'领导者2  ','20150707162157','56313b61ddb05949ebac4225d6c04e2a.jpg','a69579de3f20f2fb2f593434c455d0e8.jpg',20,0,100,2,0,1,'<img src=\"http://115.28.80.161/wutong/wzh/upload/data/d148d4f4f6026ae4120da0f87a325c36.jpg\" alt=\"\" /><img src=\"http://115.28.80.161/wutong/wzh/upload/data/546e5ae80e052877473f283f9530b3e4.jpg\" alt=\"\" /><img src=\"http://115.28.80.161/wutong/wzh/upload/data/7c501913b9e4322472529924834e7784.jpg\" alt=\"\" /><img src=\"http://115.28.80.161/wutong/wzh/upload/data/d01ebdd933ebd7183f3f941e65fd7fa4.jpg\" alt=\"\" /><img src=\"http://115.28.80.161/wutong/wzh/upload/data/b4fbc031be624012ba6bec62425a4775.jpg\" alt=\"\" />','2015-07-07 16:21:57','2015-07-07 16:21:57');

/*Table structure for table `ms_logs` */

DROP TABLE IF EXISTS `ms_logs`;

CREATE TABLE `ms_logs` (
  `logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '系统编号',
  `username` varchar(32) DEFAULT NULL COMMENT '操作者',
  `content` varchar(256) DEFAULT NULL COMMENT '操作内容',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`logs_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

/*Data for the table `ms_logs` */

insert  into `ms_logs`(`logs_id`,`username`,`content`,`create_time`) values (1,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 10:57:22'),(2,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 10:57:29'),(3,'admin','登陆系统','2015-07-04 11:49:34'),(4,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:49:54'),(5,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:50:28'),(6,'admin','修改商品：冬瓜玉米排骨汤','2015-07-04 11:52:29'),(7,'admin','登陆系统','2015-07-04 14:28:54'),(8,'admin','修改商品：莲藕排骨汤','2015-07-04 14:29:02'),(9,'admin','登陆系统','2015-07-04 15:11:40'),(10,'admin','修改订单[编号：1]状态：已确认订单','2015-07-04 16:02:25'),(11,'admin','修改订单[编号：1]状态：已出库发货','2015-07-04 16:02:32'),(12,'admin','修改订单[编号：1]状态：用户未收货，重新确认','2015-07-04 16:02:38'),(13,'admin','修改订单[编号：1]状态：已确认订单','2015-07-04 16:02:43'),(14,'admin','修改订单[编号：1]状态：已出库发货','2015-07-04 16:02:45'),(15,'admin','修改订单[编号：1]状态：用户已收货','2015-07-04 16:02:47'),(16,'admin','登陆系统','2015-07-04 18:21:08'),(17,'admin','修改商品：莲藕排骨汤','2015-07-04 18:21:24'),(18,'admin','登陆系统','2015-07-05 10:20:42'),(19,'admin','登陆系统','2015-07-05 18:59:46'),(20,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:19:38'),(21,'admin','修改商品：莲藕排骨汤','2015-07-05 19:20:03'),(22,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:24:05'),(23,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:30:50'),(24,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:35:28'),(25,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:36:09'),(26,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:36:18'),(27,'admin','修改商品：莲藕排骨汤','2015-07-05 19:50:43'),(28,'admin','修改商品：莲藕排骨汤','2015-07-05 19:52:57'),(29,'admin','修改商品：冬瓜玉米排骨汤','2015-07-05 19:53:03'),(30,'admin','修改商品：莲藕排骨汤','2015-07-05 19:54:44'),(31,'admin','登陆系统','2015-07-06 11:02:07'),(32,'admin','登陆系统','2015-07-06 11:02:09'),(33,'admin','登陆系统','2015-07-06 13:36:51'),(34,'admin','登陆系统','2015-07-06 14:15:53'),(35,'admin','登陆系统','2015-07-06 15:28:37'),(36,'admin','登陆系统','2015-07-06 16:15:53'),(37,'admin','登陆系统','2015-07-06 17:19:28'),(38,'admin','添加关注： 滴滴','2015-07-06 17:24:26'),(39,'admin','添加关注： didid','2015-07-06 17:28:52'),(40,'admin','删除关注：1','2015-07-06 17:45:14'),(41,'admin','登陆系统','2015-07-06 19:14:12'),(42,'admin','添加关注： didi','2015-07-06 19:15:38'),(43,'admin','添加关注： kuaidi','2015-07-06 19:17:10'),(44,'admin','登陆系统','2015-07-07 08:25:14'),(45,'admin','登陆系统','2015-07-07 09:21:13'),(46,'admin','登陆系统','2015-07-07 15:50:14'),(47,'admin','登陆系统','2015-07-07 15:51:46'),(48,'admin','登陆系统','2015-07-07 15:57:16'),(49,'admin','添加商品： [领导者 包]','2015-07-07 16:00:33'),(50,'admin','修改订单[编号：5]状态：已确认订单','2015-07-07 16:03:50'),(51,'admin','修改订单[编号：5]状态：已出库发货','2015-07-07 16:03:55'),(52,'admin','修改订单[编号：5]状态：用户未收货，重新确认','2015-07-07 16:04:02'),(53,'admin','修改订单[编号：5]状态：已确认订单','2015-07-07 16:04:09'),(54,'admin','修改订单[编号：5]状态：已出库发货','2015-07-07 16:04:17'),(55,'admin','添加商品： [外交官 ]','2015-07-07 16:08:07'),(56,'admin','修改商品：外交官 ','2015-07-07 16:10:47'),(57,'admin','添加商品： [领导者2  ]','2015-07-07 16:21:57'),(58,'admin','登陆系统','2015-07-07 16:33:26'),(59,'admin','登陆系统','2015-07-07 16:50:49'),(60,'admin','登陆系统','2015-07-07 16:50:51'),(61,'admin','登陆系统','2015-07-07 16:52:21'),(62,'admin','登陆系统','2015-07-07 17:04:06'),(63,'admin','修改商品：领导者 包','2015-07-07 17:05:19'),(64,'admin','登陆系统','2015-07-07 18:58:13'),(65,'admin','登陆系统','2015-07-07 20:49:40'),(66,'admin','登陆系统','2015-07-07 23:32:29'),(67,'admin','下架商品：15','2015-07-07 23:32:42'),(68,'admin','下架商品：14','2015-07-07 23:32:45'),(69,'admin','登陆系统','2015-07-08 23:16:28'),(70,'admin','登陆系统','2015-07-09 12:14:12'),(71,'admin','登陆系统','2015-07-09 12:28:01'),(72,'admin','登陆系统','2015-07-09 12:29:07');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Data for the table `ms_order` */

insert  into `ms_order`(`order_id`,`order_sn`,`user_id`,`amount`,`score`,`is_pay`,`invoice`,`remark`,`username`,`mobile`,`city`,`area`,`address`,`delivery_times`,`payment_model`,`status`,`create_time`,`modify_time`) values (1,'20150704152949540','1',0,200,1,NULL,'','吴桐','15120026623','北京市','清华科技园','123123','15:30 - 18:00','积分支付','4','2015-07-04 15:29:49','2015-07-04 16:02:47'),(2,'20150704183954804','1',0,340,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','1','2015-07-04 18:39:54','2015-07-04 18:39:54'),(3,'20150707100353109','1',0,240,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','1','2015-07-07 10:03:53','2015-07-07 10:03:53'),(4,'20150707100426716','1',0,200,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','1','2015-07-07 10:04:26','2015-07-07 10:04:26'),(5,'20150707160226707','1',0,100,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','3','2015-07-07 16:02:26','2015-07-07 16:04:17'),(6,'20150707160808404','1',0,100,1,NULL,'','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','积分支付','1','2015-07-07 16:08:08','2015-07-07 16:08:08'),(7,'20150709000527738','1',0,200,1,NULL,'事实上','吴桐','15120026623','北京市','清华科技园','北京市海淀区信息路甲9号','15:30 - 18:00','金币支付','1','2015-07-09 00:05:27','2015-07-09 00:05:27');

/*Table structure for table `ms_order_action` */

DROP TABLE IF EXISTS `ms_order_action`;

CREATE TABLE `ms_order_action` (
  `order_id` int(11) NOT NULL COMMENT '关联的订单号',
  `action` varchar(32) NOT NULL COMMENT '订单操作编号，1=确认订单, 2=出库发货, 3=用户已收货, 4=用户未收货，重新确认',
  `action_name` varchar(32) NOT NULL COMMENT '订单操作名称',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_order_action` */

insert  into `ms_order_action`(`order_id`,`action`,`action_name`,`create_time`) values (1,'order.action.confirm','已确认订单','2015-07-04 16:02:25'),(1,'order.action.shipment','已出库发货','2015-07-04 16:02:32'),(1,'order.action.reconfirm','用户未收货，重新确认','2015-07-04 16:02:38'),(1,'order.action.confirm','已确认订单','2015-07-04 16:02:43'),(1,'order.action.shipment','已出库发货','2015-07-04 16:02:45'),(1,'order.action.receipt','用户已收货','2015-07-04 16:02:47'),(5,'order.action.confirm','已确认订单','2015-07-07 16:03:50'),(5,'order.action.shipment','已出库发货','2015-07-07 16:03:55'),(5,'order.action.reconfirm','用户未收货，重新确认','2015-07-07 16:04:02'),(5,'order.action.confirm','已确认订单','2015-07-07 16:04:09'),(5,'order.action.shipment','已出库发货','2015-07-07 16:04:17');

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

insert  into `ms_order_info`(`order_id`,`goods_id`,`goods_price`,`goods_score`,`cart_num`) values (1,15,0,100,2),(2,14,0,120,2),(2,15,0,100,1),(3,14,0,120,2),(4,15,0,100,2),(5,16,0,100,1),(6,16,0,100,1),(7,16,0,100,2);

/*Table structure for table `ms_property` */

DROP TABLE IF EXISTS `ms_property`;

CREATE TABLE `ms_property` (
  `label` varchar(32) NOT NULL COMMENT '属性显示名',
  `name` varchar(32) NOT NULL COMMENT '属性名',
  `value` varchar(32) NOT NULL COMMENT '属性值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `ms_property` */

insert  into `ms_property`(`label`,`name`,`value`) values ('未确认订单','order.action.noconfirm','1'),('已确认订单','order.action.confirm','2'),('已出库发货','order.action.shipment','3'),('用户已收货','order.action.receipt','4'),('用户未收货，重新确认','order.action.reconfirm','5'),('货到付款','payment.model.cod','货到付款'),('余额支付','payment.model.amount','余额支付'),('微信支付','payment.model.weixin','微信支付'),('积分支付','payment.model.score','积分兑换');

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
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

/*Data for the table `ms_user` */

insert  into `ms_user`(`user_id`,`account`,`password`,`mobile`,`email`,`amount`,`score`,`wx_openid`,`nickname`,`sex`,`city`,`province`,`country`,`headimgurl`,`create_time`,`modify_time`) values (1,NULL,NULL,NULL,'dayphosphor@163.com',0,1851,'oVvBOs0D0iEhQ7AFUlatpNC8QYgc','吴桐',1,'','北京','中国','http://wx.qlogo.cn/mmopen/ichSre3n3PfyIYk9MVsib94V4vvHGOWibwIlT5ELOsHFiaUbmZoyV6XNJsa35Vmee56zZp6J9ON1u5OvH3G6NClBPbpqiaicibthbVX/0','2015-07-02 11:10:57','2015-07-09 10:25:56'),(20,NULL,NULL,NULL,NULL,0,20,'oVvBOs6nPz95VuTMZ78kyHKuSeWU','李坚',1,'朝阳','北京','中国','http://wx.qlogo.cn/mmopen/ny3O7jtkDkJqldpQ9cXyR2ZicB9mOZibyj0FFoAfXNx9XFn9lGDkhhQmuabfviagZpgPDkBiaQAib5LVqtMkeAHJ998zHKLEgqBdq/0','2015-07-03 14:25:40','2015-07-03 20:29:35'),(22,NULL,NULL,NULL,NULL,0,50,'oVvBOs2MT3qMafFpvkm7icmAM_X4','贺总',1,'昌平','北京','中国','http://wx.qlogo.cn/mmopen/ibDRic4Cogo7iaVQTqDVV5aJlj9ZQs4cujVEIT4zWUb9AqxEwOqfXTJPyqQtWOavb5UpiciaTnmw56tUbeJStxdRkkZkUVBGBZZN1/0','2015-07-03 14:37:09','2015-07-03 14:43:09'),(23,NULL,NULL,NULL,NULL,0,0,'oVvBOs5A-_2UaqZN3bWY520SI2H8','金梧桐',2,'西城','北京','中国','http://wx.qlogo.cn/mmopen/ny3O7jtkDkJqldpQ9cXyRibCoeMZtHdiaiciannaDGG2eMJjzU0ZEPd3JyXLdicTn5ZmTmYibvXcJpA5tjjwjAaZsdxISScBFkyty4/0','2015-07-04 19:16:34','2015-07-04 19:16:34'),(24,NULL,NULL,NULL,NULL,0,0,'oVvBOs8CofS5QirNaN4J5TItcK2E','Jerry',1,'昌平','北京','中国','http://wx.qlogo.cn/mmopen/Q3auHgzwzM6icHB8bkO261n5CPaEWLzTehHtjiaWn2PStWlcmY2uLMxLceVvT9JRH6hGvoh4n7Sr54aWLzvJHCrlmYe4zhZuKwe3PuGxibX1ic4/0','2015-07-06 11:38:30','2015-07-06 11:38:30'),(25,NULL,NULL,NULL,NULL,0,0,'oVvBOs4dQnaAduX93cYxpB2iCOrg','旭 ',2,'海淀','北京','中国','http://wx.qlogo.cn/mmopen/PiajxSqBRaEJITXFoXy1sbBxjEUdicH6tSbxN4icDFvsib97GpOkbIoAF1tX1MFD7KeIkKJyRnQPjsvbYDXf4LCiciag/0','2015-07-09 10:48:20','2015-07-09 10:48:20'),(26,NULL,NULL,NULL,NULL,0,0,'oVvBOs3qy3FJw2fXR0sBwAwzBX6U','阿杜',1,'朝阳','北京','中国','http://wx.qlogo.cn/mmopen/ajNVdqHZLLBxkKzUXbg8T8a9RMLRuEuBKPoOTI9QucxJT07X1mTeicj0QlTV2tsj3XJ6dOaibTdneia6BRAAvwySA/0','2015-07-09 12:33:06','2015-07-09 12:33:06');

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

insert  into `ms_user_account`(`user_id`,`action`,`action_name`,`amount`,`create_time`) values (20,'user.score.add','增加积分10元',10,'2015-07-02 19:57:35'),(20,'user.score.add','增加积分10元',10,'2015-07-02 19:57:42'),(21,'user.score.add','增加积分10元',10,'2015-07-03 14:26:43'),(22,'user.score.add','增加积分10元',10,'2015-07-03 14:37:50'),(22,'user.score.add','增加积分10元',10,'2015-07-03 14:38:47'),(22,'user.score.add','增加积分10元',10,'2015-07-03 14:38:49'),(22,'user.score.add','增加积分10元',10,'2015-07-03 14:43:07'),(22,'user.score.add','增加积分10元',10,'2015-07-03 14:43:09'),(21,'user.score.add','增加积分10元',10,'2015-07-03 20:29:35'),(1,'user.score.lose','订单支付积分200',200,'2015-07-04 15:29:49'),(1,'user.score.lose','订单支付积分340',340,'2015-07-04 18:39:54'),(1,'user.score.add','增加积分10元',10,'2015-07-04 19:17:00'),(1,'user.score.add','增加积分20',20,'2015-07-04 22:38:15'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:40:28'),(1,'user.score.add','增加积分20',20,'2015-07-04 22:42:52'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:43:05'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:43:16'),(1,'user.score.add','增加积分2',2,'2015-07-04 22:43:28'),(1,'user.score.add','增加积分5',5,'2015-07-04 22:43:39'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:43:50'),(1,'user.score.add','增加积分2',2,'2015-07-04 22:45:16'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:45:28'),(1,'user.score.add','增加积分10',10,'2015-07-04 22:45:40'),(1,'user.score.add','增加积分30',30,'2015-07-04 22:45:49'),(1,'user.score.add','增加积分30',30,'2015-07-05 05:06:05'),(1,'user.score.add','增加积分20',20,'2015-07-05 07:49:42'),(1,'user.score.add','增加积分30',30,'2015-07-05 07:58:44'),(1,'user.score.add','增加积分20',20,'2015-07-05 08:00:30'),(1,'user.score.add','增加积分20',20,'2015-07-05 08:01:19'),(1,'user.score.add','增加积分5',5,'2015-07-05 12:30:46'),(1,'user.score.add','增加积分10',10,'2015-07-05 21:35:34'),(1,'user.score.add','增加积分10',10,'2015-07-06 09:14:24'),(1,'user.score.add','增加积分10',10,'2015-07-06 10:28:38'),(1,'user.score.add','增加积分10',10,'2015-07-06 10:29:06'),(1,'user.score.add','增加积分10',10,'2015-07-06 10:29:08'),(1,'user.score.add','增加积分10',10,'2015-07-06 10:29:12'),(1,'user.score.add','增加积分20',20,'2015-07-06 11:52:28'),(1,'user.score.add','增加积分5',5,'2015-07-06 11:52:42'),(1,'user.score.add','增加积分30',30,'2015-07-06 11:52:53'),(1,'user.score.add','增加积分5',5,'2015-07-06 12:03:18'),(1,'user.score.add','增加积分30',30,'2015-07-06 12:40:00'),(1,'user.score.add','增加积分10',10,'2015-07-06 19:25:38'),(1,'user.score.add','增加积分10',10,'2015-07-06 19:34:13'),(1,'user.score.add','增加积分10',10,'2015-07-06 20:52:59'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:02:35'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:03:46'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:06:13'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:07:17'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:19:13'),(1,'user.score.add','增加积分',NULL,'2015-07-07 08:25:36'),(1,'user.score.add','增加积分',NULL,'2015-07-07 09:24:25'),(1,'user.score.add','增加积分2',2,'2015-07-07 09:37:44'),(1,'user.score.add','增加积分50',50,'2015-07-07 10:00:55'),(1,'user.score.lose','订单支付积分240',240,'2015-07-07 10:03:53'),(1,'user.score.lose','订单支付积分200',200,'2015-07-07 10:04:26'),(1,'user.score.lose','订单支付积分100',100,'2015-07-07 16:02:26'),(1,'user.score.lose','订单支付积分100',100,'2015-07-07 16:08:08'),(1,'user.score.add','增加积分',NULL,'2015-07-07 23:28:00'),(1,'user.score.lose','减少积分1',1,'2015-07-08 08:06:36'),(1,'user.score.lose','减少积分2',2,'2015-07-08 09:03:34'),(1,'user.score.add','增加积分5',5,'2015-07-08 11:20:03'),(1,'user.score.add','增加积分10',10,'2015-07-08 14:19:54'),(1,'user.score.add','增加积分5',5,'2015-07-08 14:22:20'),(1,'user.score.add','增加积分5',5,'2015-07-08 15:40:12'),(1,'user.score.add','增加积分50',50,'2015-07-08 19:56:05'),(1,'user.score.add','增加积分56',56,'2015-07-08 20:57:44'),(1,'user.score.add','增加积分59',59,'2015-07-08 20:58:05'),(1,'user.score.add','增加积分51',51,'2015-07-08 20:59:33'),(1,'user.score.add','增加积分50',50,'2015-07-08 20:59:49'),(1,'user.score.add','增加积分33',33,'2015-07-08 21:00:27'),(1,'user.score.add','增加积分45',45,'2015-07-08 21:01:28'),(1,'user.score.add','增加积分5',5,'2015-07-08 21:14:10'),(1,'user.score.add','增加积分20',20,'2015-07-08 21:28:18'),(1,'user.score.add','增加积分5',5,'2015-07-08 22:19:20'),(1,'user.score.add','增加积分30',30,'2015-07-08 22:31:56'),(1,'user.score.add','增加积分61',61,'2015-07-08 22:43:23'),(1,'user.score.add','增加积分13',13,'2015-07-08 22:47:20'),(1,'user.score.add','增加积分20',20,'2015-07-08 22:51:45'),(1,'user.score.add','增加积分20',20,'2015-07-08 22:51:49'),(1,'user.score.add','增加金币50',50,'2015-07-09 00:03:26'),(1,'user.score.add','增加金币60',60,'2015-07-09 00:04:51'),(1,'user.score.lose','订单支付金币200',200,'2015-07-09 00:05:27'),(1,'user.score.add','增加金币2',2,'2015-07-09 00:29:57'),(1,'user.score.add','增加金币10',10,'2015-07-09 00:30:38'),(1,'user.score.add','增加金币20',20,'2015-07-09 09:50:23'),(1,'user.score.add','增加金币39',39,'2015-07-09 10:25:22'),(1,'user.score.add','增加金币1',1,'2015-07-09 10:25:56');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
