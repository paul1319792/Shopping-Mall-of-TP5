-- phpMyAdmin SQL Dump
-- version 4.7.0-beta1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2019-03-09 17:38:36
-- 服务器版本： 5.5.53-log
-- PHP Version: 7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `php19shop`
--

-- --------------------------------------------------------

--
-- 表的结构 `a`
--

CREATE TABLE `a` (
  `id` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `a`
--

INSERT INTO `a` (`id`) VALUES
(200);

-- --------------------------------------------------------

--
-- 表的结构 `it_attribute`
--

CREATE TABLE `it_attribute` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `type_id` tinyint(3) UNSIGNED NOT NULL COMMENT '商品的类型id',
  `attr_name` varchar(32) NOT NULL COMMENT '属性的名称',
  `attr_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '属性的类型，0表示唯一属性，1表示单选属性',
  `attr_input_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '属性值的录入方式，0表示手工录入，1表示列表选择',
  `attr_value` varchar(64) NOT NULL DEFAULT '' COMMENT '可选值列表',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '记录删除时间，假(逻辑)删除'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_attribute`
--

INSERT INTO `it_attribute` (`id`, `type_id`, `attr_name`, `attr_type`, `attr_input_type`, `attr_value`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 1, '长度', 0, 0, '', 1551255995, 1551255995, NULL),
(2, 1, '宽度', 0, 0, '', 1551256015, 1551256015, NULL),
(3, 1, '颜色', 1, 1, '白色,黑色,金色', 1551256043, 1551256043, NULL),
(4, 2, '作者', 0, 0, '', 1551257046, 1551257046, NULL),
(5, 2, '出版社', 0, 0, '', 1551257081, 1551257081, NULL),
(6, 2, '开本', 0, 1, '32K,16K,8K', 1551257135, 1551257135, NULL),
(7, 1, '内存', 1, 1, '64G,128G,256G', 1551257173, 1551257173, NULL),
(8, 1, '系统', 0, 1, '苹果,安卓', 1551257210, 1551257210, NULL),
(9, 2, '等级', 1, 1, '特级,一级,二级', 1551516564, 1551516564, NULL),
(10, 3, '甜度', 1, 1, '超甜,比较甜,一般甜', 1551748287, 1551748287, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_cart`
--

CREATE TABLE `it_cart` (
  `id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品的id',
  `uid` varchar(40) NOT NULL DEFAULT '' COMMENT '商品的属性序列化信息',
  `goods_attrs` varchar(255) NOT NULL DEFAULT '' COMMENT '商品的属性信息',
  `goods_buy_number` tinyint(4) NOT NULL COMMENT '购买数量',
  `user_id` int(11) NOT NULL COMMENT '登录用户的id'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `it_category`
--

CREATE TABLE `it_category` (
  `id` int(11) NOT NULL COMMENT '分类id',
  `parent_id` int(11) NOT NULL COMMENT '父级id',
  `cate_name` varchar(50) NOT NULL COMMENT '分类名称',
  `cate_desc` varchar(200) NOT NULL COMMENT '栏目描述',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_category`
--

INSERT INTO `it_category` (`id`, `parent_id`, `cate_name`, `cate_desc`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 0, '手机', '水电费的说法都是1111', 1551320679, 1551325691, NULL),
(2, 0, '电脑', '士大夫的身份', 1551320957, 1551320957, NULL),
(3, 0, '水果', '第三方士大夫', 1551320968, 1551342003, 1551342003),
(4, 1, '智能手机', '水电费第三方第三方', 1551321862, 1551417222, NULL),
(5, 1, '老年手机', '是对方答复111', 1551321875, 1551417256, NULL),
(6, 1, '魔幻手机', '士大夫的身份', 1551321889, 1551321889, NULL),
(7, 2, '组装电脑', '水电费水电费水电费', 1551322471, 1551322471, NULL),
(8, 2, '品牌电脑', '士大夫的身份', 1551322489, 1551326343, 1551326343),
(9, 4, '华为手机', '', 1551345934, 1551417352, 1551417352),
(10, 6, '新节点', '', 1551346708, 1551417277, 1551417277),
(11, 5, '新节点', '', 1551346716, 1551417282, 1551417282),
(12, 0, '水果', '的范德萨发生', 1551417246, 1551417246, NULL),
(13, 2, '品牌电脑', '师傅的说法都是', 1551417301, 1551417301, NULL),
(14, 12, '北方水果', '第三方第三方士大夫', 1551417320, 1551417320, NULL),
(15, 12, '南方水果', '师傅的说法', 1551417335, 1551417335, NULL),
(16, 5, '诺基亚', '是杜甫师傅的说法', 1551774019, 1551774019, NULL),
(17, 4, '华为手机', '水电费第三方第三方', 1551777006, 1551777006, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_consignee`
--

CREATE TABLE `it_consignee` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `user_id` int(11) NOT NULL COMMENT '会员id',
  `name` varchar(32) NOT NULL COMMENT '收货人名称',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '收货人地址',
  `tel` varchar(20) NOT NULL DEFAULT '' COMMENT '收货人电话',
  `code` char(6) NOT NULL DEFAULT '' COMMENT '邮编',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货人表';

--
-- 转存表中的数据 `it_consignee`
--

INSERT INTO `it_consignee` (`id`, `user_id`, `name`, `address`, `tel`, `code`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 1, '林朝英', '上海市徐汇区老沪闵路395号', '13566771298', '306810', NULL, NULL, NULL),
(2, 2, '李隆基', '江苏省南京市江宁区禄口街道谢村社区', '13126537865', '600981', NULL, NULL, NULL),
(3, 3, '李莫愁', '山东省济南市市中区经四路343号', '18902564321', '600214', NULL, NULL, NULL),
(4, 2, '小龙女', '山东省枣庄市市中区建华西路177号', '15765329087', '600983', NULL, NULL, NULL),
(5, 3, '胖大海', '湖南省衡阳市衡南县硫市镇富民路18号', '15028374375', '600912', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_goods`
--

CREATE TABLE `it_goods` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `goods_name` varchar(32) NOT NULL DEFAULT '' COMMENT '商品的名称',
  `cat_id` varchar(32) NOT NULL DEFAULT '0' COMMENT '商品的栏目id',
  `goods_sn` varchar(32) NOT NULL DEFAULT '' COMMENT '商品的货号',
  `shop_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '本店价格',
  `market_price` decimal(9,2) NOT NULL DEFAULT '0.00' COMMENT '市场价格',
  `is_new` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否新品,0不是 1是',
  `is_hot` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT ' 是否热卖,0不是 1是',
  `is_best` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否精品,0不是 1是',
  `is_sale` tinyint(3) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否销售,0不是 1是',
  `goods_ori` varchar(128) NOT NULL DEFAULT '' COMMENT '原图路径',
  `goods_thumb` varchar(128) NOT NULL DEFAULT '' COMMENT '小图路径',
  `goods_img` varchar(128) NOT NULL DEFAULT '' COMMENT '中图路径',
  `goods_desc` varchar(256) NOT NULL DEFAULT '' COMMENT '简短描述',
  `goods_type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '所属类型的id',
  `goods_number` int(11) NOT NULL DEFAULT '0' COMMENT '商品的库存',
  `goods_intro` text COMMENT '商品详情',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间',
  `goods_attr` varchar(200) NOT NULL DEFAULT '' COMMENT '属性信息'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_goods`
--

INSERT INTO `it_goods` (`id`, `goods_name`, `cat_id`, `goods_sn`, `shop_price`, `market_price`, `is_new`, `is_hot`, `is_best`, `is_sale`, `goods_ori`, `goods_thumb`, `goods_img`, `goods_desc`, `goods_type_id`, `goods_number`, `goods_intro`, `create_time`, `update_time`, `delete_time`, `goods_attr`) VALUES
(1, '诺基亚', '4', '', '1234.56', '0.00', 0, 0, 0, 1, '', '', '', '', 1, 0, NULL, NULL, NULL, NULL, ''),
(2, '波导机', '5', '', '1234.56', '0.00', 0, 0, 0, 1, '', '', '', '', 1, 0, NULL, NULL, NULL, NULL, ''),
(3, '中兴机', '6', '', '3234.56', '0.00', 0, 0, 0, 1, '', '', '', '', 1, 0, NULL, NULL, NULL, NULL, ''),
(4, '天语机', '4', '', '6234.56', '0.00', 0, 0, 0, 1, '', '', '', '', 1, 0, NULL, NULL, NULL, NULL, ''),
(5, '华为荣耀8', '6', '', '12.00', '33.00', 1, 1, 1, 1, '', '', '', '2222', 0, 0, NULL, NULL, NULL, NULL, ''),
(6, '小米9手机', '4', '', '1234.00', '1233.00', 1, 0, 1, 1, '', '', '', '水电费第三方的', 0, 12, NULL, 1551498965, 1551498965, NULL, ''),
(7, '你好<script>alert(123)</script>', '5', '', '11.00', '11.00', 1, 1, 1, 1, '', '', '', '111111', 0, 11, '', 1551517967, 1551517967, NULL, ''),
(8, 'demo&lt;script&gt;alert(456)&lt;', '5', '', '11.00', '11.00', 1, 1, 1, 1, '', '', '', 'dfdffdsffff', 0, 11, '', 1551518153, 1551518153, NULL, ''),
(9, '22', '4', '', '22.00', '22.00', 1, 1, 1, 1, '', '', '', '22法第三方士大夫', 0, 22, '&lt;p&gt;&lt;span style=&quot;text-decoration: underline; color: rgb(255, 0, 0); font-size: 36px;&quot;&gt;&lt;strong&gt;非常好的手机，&lt;/strong&gt;&lt;/span&gt;&lt;/p&gt;', 1551518289, 1551518289, NULL, ''),
(10, '33', '5', '', '333.00', '33.00', 1, 1, 1, 1, '', '', '', '333333333333333', 0, 33, '<p><span style=\"color: rgb(255, 0, 0); font-size: 36px;\"><strong><em>好手机</em></strong></span></p>', 1551518501, 1551518501, NULL, ''),
(11, 'abc&lt;script&gt;alert(111)&lt;/', '4', '', '4444.00', '4444.00', 1, 1, 1, 1, '', '', '', '44444444', 0, 4444, '<p><em><span style=\"font-size:36px;color:rgb(0,176,240);\">啊哈是的范德萨</span></em></p>', 1551519344, 1551519344, NULL, ''),
(12, '老诺基亚', '6', '', '12.00', '14.00', 1, 1, 1, 1, '/uploads/goods/2019/03/03/5c7b2f3b46923.jpg', '/uploads/goods/2019/03/03/thumb_5c7b2f3b46923.jpg', '/uploads/goods/2019/03/03/img_5c7b2f3b46923.jpg', '而我认为惹我', 1, 11, '<p>温热污染为尔尔</p>', 1551577048, 1551577048, NULL, 'a:5:{i:1;a:1:{i:0;s:1:\"5\";}i:2;a:1:{i:0;s:1:\"6\";}i:3;a:2:{i:0;s:6:\"白色\";i:1;s:6:\"黑色\";}i:7;a:2:{i:0;s:3:\"64G\";i:1;s:4:\"128G\";}i:8;a:1:{i:0;s:6:\"安卓\";}}'),
(13, '荣耀90', '17', '', '12.00', '23.00', 1, 1, 1, 1, '/uploads/goods/2019/03/05/5c7e41b177b70.jpg', '/uploads/goods/2019/03/05/thumb_5c7e41b177b70.jpg', '/uploads/goods/2019/03/05/img_5c7e41b177b70.jpg', '第三方士大夫的所属', 1, 23, '<p>师傅的说法都是浮点数放到是范德萨范德萨范德萨范德萨</p>', 1551778251, 1551778251, NULL, 'a:5:{i:1;a:1:{i:0;s:2:\"11\";}i:2;a:1:{i:0;s:2:\"22\";}i:3;a:2:{i:0;s:6:\"白色\";i:1;s:6:\"黑色\";}i:7;a:2:{i:0;s:3:\"64G\";i:1;s:4:\"256G\";}i:8;a:1:{i:0;s:6:\"安卓\";}}'),
(14, '小米手机', '4', '', '123.00', '34.00', 1, 1, 1, 1, '/uploads/goods/2019/03/08/5c81d0e2b9f6c.jpg', '/uploads/goods/2019/03/08/thumb_5c81d0e2b9f6c.jpg', '/uploads/goods/2019/03/08/img_5c81d0e2b9f6c.jpg', '水电费第三方第三方发士大夫的身份', 1, 45, '<p>的身份是的范德萨范德萨发生的</p>', 1552011566, 1552011566, NULL, 'a:5:{i:1;a:1:{i:0;s:2:\"12\";}i:2;a:1:{i:0;s:2:\"23\";}i:3;a:3:{i:0;s:6:\"白色\";i:1;s:6:\"黑色\";i:2;s:6:\"金色\";}i:7;a:3:{i:0;s:3:\"64G\";i:1;s:4:\"128G\";i:2;s:4:\"256G\";}i:8;a:1:{i:0;s:6:\"安卓\";}}');

-- --------------------------------------------------------

--
-- 表的结构 `it_goods_album`
--

CREATE TABLE `it_goods_album` (
  `id` int(10) UNSIGNED NOT NULL,
  `goods_id` int(11) NOT NULL COMMENT '商品的id',
  `album_ori` varchar(128) NOT NULL DEFAULT '' COMMENT '原图的路径',
  `album_thumb` varchar(128) NOT NULL DEFAULT '' COMMENT '小图的路径',
  `album_img` varchar(128) NOT NULL DEFAULT '' COMMENT '中图的路径',
  `album_desc` varchar(200) NOT NULL DEFAULT '' COMMENT '相册描述'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_goods_album`
--

INSERT INTO `it_goods_album` (`id`, `goods_id`, `album_ori`, `album_thumb`, `album_img`, `album_desc`) VALUES
(1, 11, '2019/03/02/5c7a96595da45.jpg', '2019/03/02/thumb_5c7a96595da45.jpg', '2019/03/02/img_5c7a96595da45.jpg', ''),
(2, 11, '2019/03/02/5c7a965972b6e.jpg', '2019/03/02/thumb_5c7a965972b6e.jpg', '2019/03/02/img_5c7a965972b6e.jpg', ''),
(3, 12, '/uploads/album/2019/03/03/5c7b3ec3d3856.jpg', '/uploads/album/2019/03/03/thumb_5c7b3ec3d3856.jpg', '/uploads/album/2019/03/03/img_5c7b3ec3d3856.jpg', ''),
(4, 12, '/uploads/album/2019/03/03/5c7b3ec3f2ed9.jpg', '/uploads/album/2019/03/03/thumb_5c7b3ec3f2ed9.jpg', '/uploads/album/2019/03/03/img_5c7b3ec3f2ed9.jpg', ''),
(5, 13, '/uploads/album/2019/03/06/5c7f7f31eba6e.jpg', '/uploads/album/2019/03/06/thumb_5c7f7f31eba6e.jpg', '/uploads/album/2019/03/06/img_5c7f7f31eba6e.jpg', ''),
(6, 13, '/uploads/album/2019/03/06/5c7f7f32318b4.jpg', '/uploads/album/2019/03/06/thumb_5c7f7f32318b4.jpg', '/uploads/album/2019/03/06/img_5c7f7f32318b4.jpg', ''),
(7, 13, '/uploads/album/2019/03/06/5c7f7f32b28ad.jpg', '/uploads/album/2019/03/06/thumb_5c7f7f32b28ad.jpg', '/uploads/album/2019/03/06/img_5c7f7f32b28ad.jpg', ''),
(8, 13, '/uploads/album/2019/03/06/5c7f7f32ca568.jpg', '/uploads/album/2019/03/06/thumb_5c7f7f32ca568.jpg', '/uploads/album/2019/03/06/img_5c7f7f32ca568.jpg', '');

-- --------------------------------------------------------

--
-- 表的结构 `it_manager`
--

CREATE TABLE `it_manager` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `password` varchar(80) NOT NULL,
  `role_id` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色id',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '记录删除时间，假(逻辑)删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_manager`
--

INSERT INTO `it_manager` (`id`, `name`, `password`, `role_id`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 'root', '$2y$10$oGj9MgyGlinGpYYGSTcM0uvK0/2grzQ5LfYyHYFhM8aS1pZlHecRW', 0, NULL, NULL, NULL),
(2, 'libai', '$2y$10$xmFeuH.cZyI01v03ETOp4O2lAcsqi.oIFnPN0ErvONgLgviyurzla', 1, NULL, NULL, NULL),
(3, 'songjiang', '$2y$10$yUHC.65bcS/DZiaoEtC5puwnwfVHsFo2WX5R8z7hix.RRwZ5HHCQm', 2, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_order`
--

CREATE TABLE `it_order` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `user_id` mediumint(8) UNSIGNED NOT NULL COMMENT '下订单会员id',
  `order_sn` varchar(32) NOT NULL COMMENT '订单编号',
  `order_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
  `order_pay` enum('0','1','2','3') NOT NULL DEFAULT '0' COMMENT '支付方式 0货到付款1支付宝2微信3银行卡',
  `order_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '订单状态： 0未付款、1已付款',
  `address` text COMMENT 'consignee收货人地址信息，名称+地址+手机+邮编 的序列化信息',
  `order_pay_money` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '支付总金额',
  `trade_no` char(32) NOT NULL DEFAULT '' COMMENT '支付交易流水号码',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '删除时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单表';

--
-- 转存表中的数据 `it_order`
--

INSERT INTO `it_order` (`id`, `user_id`, `order_sn`, `order_price`, `order_pay`, `order_status`, `address`, `order_pay_money`, `trade_no`, `create_time`, `update_time`, `delete_time`) VALUES
(1, 3, 'sn_5c832c48b6d4d', '1599.00', '', '0', '寄送至:湖南省衡阳市衡南县硫市镇富民路18号 收货人:胖大海  15028374375', '0.00', '', 1552100424, 1552100424, NULL),
(2, 3, 'sn_5c83724551aee', '123.00', '', '0', '寄送至:山东省济南市市中区经四路343号 收货人：李莫愁 18902564321', '0.00', '', 1552118341, 1552118341, NULL),
(3, 3, 'sn_5c8374b920392', '123.00', '', '1', '寄送至:山东省济南市市中区经四路343号 收货人：李莫愁 18902564321', '123.00', '2019030922001489360500545693', 1552118969, 1552118969, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_order_goods`
--

CREATE TABLE `it_order_goods` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键id',
  `order_id` int(10) UNSIGNED NOT NULL COMMENT '订单id',
  `goods_id` mediumint(8) UNSIGNED NOT NULL COMMENT '商品id',
  `goods_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品单价',
  `goods_number` tinyint(4) NOT NULL DEFAULT '1' COMMENT '购买单个商品数量',
  `goods_price_sum` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品小计价格',
  `goods_attrs` varchar(128) NOT NULL DEFAULT '' COMMENT '商品属性信息',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单关联表';

--
-- 转存表中的数据 `it_order_goods`
--

INSERT INTO `it_order_goods` (`id`, `order_id`, `goods_id`, `goods_price`, `goods_number`, `goods_price_sum`, `goods_attrs`, `create_time`, `update_time`) VALUES
(1, 1, 14, '123.00', 5, '615.00', 'a:2:{i:3;s:6:\"黑色\";i:7;s:4:\"256G\";}', NULL, NULL),
(2, 1, 14, '123.00', 6, '738.00', 'a:2:{i:3;s:6:\"黑色\";i:7;s:4:\"128G\";}', NULL, NULL),
(3, 1, 14, '123.00', 2, '246.00', 'a:2:{i:3;s:6:\"白色\";i:7;s:4:\"128G\";}', NULL, NULL),
(4, 2, 14, '123.00', 1, '123.00', 'a:2:{i:3;s:6:\"白色\";i:7;s:3:\"64G\";}', 1552118341, NULL),
(5, 3, 14, '123.00', 1, '123.00', 'a:2:{i:3;s:6:\"黑色\";i:7;s:4:\"256G\";}', 1552118969, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_privilege`
--

CREATE TABLE `it_privilege` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `priv_name` varchar(20) NOT NULL COMMENT '权限名称',
  `parent_id` smallint(6) UNSIGNED NOT NULL COMMENT '父id',
  `controller_name` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器',
  `action_name` varchar(32) NOT NULL DEFAULT '' COMMENT '操作方法',
  `level` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '权限等级',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '记录删除时间，假(逻辑)删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

--
-- 转存表中的数据 `it_privilege`
--

INSERT INTO `it_privilege` (`id`, `priv_name`, `parent_id`, `controller_name`, `action_name`, `level`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '商品管理', 0, '', '', '0', 1551583799, 1551583799, NULL),
(2, '订单管理', 0, '', '', '0', 1551583799, 1551583799, NULL),
(3, '权限管理', 0, '', '', '0', 1551583799, 1551583799, NULL),
(4, '商品类型', 1, 'Type', 'index', '1', 1551583799, 1551583799, NULL),
(5, '商品栏目', 1, 'Category', 'index', '1', 1551583799, 1551583799, NULL),
(6, '商品列表', 1, 'Goods', 'index', '1', 1551583799, 1551583799, NULL),
(7, '订单列表', 2, 'Order', 'index', '1', 1551583799, 1551583799, NULL),
(8, '订单打印', 2, 'Order', 'dayin', '1', 1551583799, 1551583799, NULL),
(9, '添加订单', 2, 'Order', 'add', '1', 1551583799, 1551583799, NULL),
(10, '管理员列表', 3, 'Manager', 'index', '1', 1551583799, 1551583799, NULL),
(11, '角色列表', 3, 'Role', 'index', '1', 1551583799, 1551583799, NULL),
(12, '权限列表', 3, 'Privilege', 'index', '1', 1551583802, 1551583802, NULL),
(15, '添加', 4, 'Type', 'add', '2', NULL, NULL, NULL),
(16, '删除', 4, 'Type', 'del', '2', NULL, NULL, NULL),
(17, '音乐管理', 0, '', '', '0', NULL, NULL, NULL),
(18, '音乐列表', 17, 'Music', 'index', '1', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_role`
--

CREATE TABLE `it_role` (
  `id` smallint(6) UNSIGNED NOT NULL,
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `priv_ids` varchar(128) NOT NULL DEFAULT '' COMMENT '权限ids,1,2,5',
  `priv_ac` text COMMENT '控制器-操作,控制器-操作,控制器-操作',
  `create_time` int(11) DEFAULT NULL COMMENT '添加时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '记录删除时间，假(逻辑)删除'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `it_role`
--

INSERT INTO `it_role` (`id`, `role_name`, `priv_ids`, `priv_ac`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '主管', '1,4,5,6', 'Type-index,Category-index,Goods-index', 1551583999, 1551583999, NULL),
(2, '经理', '1,4,15,16,5,6', 'Type-index,Category-index,Goods-index,Type-add,Type-del', 1551584000, 1551584000, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_type`
--

CREATE TABLE `it_type` (
  `id` smallint(5) UNSIGNED NOT NULL COMMENT '????id',
  `type_name` varchar(32) NOT NULL COMMENT '????????',
  `type_desc` varchar(200) NOT NULL COMMENT '????????',
  `create_time` int(11) DEFAULT NULL COMMENT '????ʱ??',
  `update_time` int(11) DEFAULT NULL COMMENT '?޸?ʱ??',
  `delete_time` int(11) DEFAULT NULL COMMENT '??¼ɾ??ʱ?䣬??(?߼?)ɾ??'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='???ͱ;

--
-- 转存表中的数据 `it_type`
--

INSERT INTO `it_type` (`id`, `type_name`, `type_desc`, `create_time`, `update_time`, `delete_time`) VALUES
(1, '手机', '一种手机啊', NULL, NULL, NULL),
(2, '书刊', '非常好的书刊，', NULL, NULL, NULL),
(3, '水果', '多吃水果，能减肥', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `it_user`
--

CREATE TABLE `it_user` (
  `id` int(11) NOT NULL COMMENT '主键自增id',
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '登录名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '登录密码',
  `user_email` varchar(64) NOT NULL DEFAULT '' COMMENT '邮箱',
  `openid` char(32) NOT NULL DEFAULT '' COMMENT '腾讯返回的标识当前登录qq的唯一信息',
  `verify_code` char(13) DEFAULT NULL COMMENT '新用户注册邮件激活唯一校验码',
  `is_active` enum('是','否') DEFAULT '否' COMMENT '新用户是否已经通过邮箱激活',
  `create_time` int(11) DEFAULT NULL COMMENT '创建时间',
  `update_time` int(11) DEFAULT NULL COMMENT '修改时间',
  `delete_time` int(11) DEFAULT NULL COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员表';

--
-- 转存表中的数据 `it_user`
--

INSERT INTO `it_user` (`id`, `username`, `password`, `user_email`, `openid`, `verify_code`, `is_active`, `create_time`, `update_time`, `delete_time`) VALUES
(3, '李黑', 'e10adc3949ba59abbe56e057f20f883e', '1973001898@qq.com', '', '5c7f34fdc6a61', '是', NULL, NULL, NULL),
(4, '行业精英', '', '', '4F6B6096FFBDE5164486026D9A5144FD', NULL, '否', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '',
  `age` tinyint(3) UNSIGNED NOT NULL DEFAULT '0',
  `email` varchar(32) NOT NULL DEFAULT '',
  `classid` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `name`, `age`, `email`, `classid`) VALUES
(1, 'xiaoxiao', 12, 'gang@sohu.com', 4),
(2, 'xiaohong', 13, 'hong@sohu.com', 2),
(3, 'xiaolong', 12, 'long@sohu.com', 2),
(4, 'xiaofeng', 22, 'feng@sohu.com', 3),
(5, 'xiaogui', 42, 'gui@sohu.com', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `it_attribute`
--
ALTER TABLE `it_attribute`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_cart`
--
ALTER TABLE `it_cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_id` (`goods_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `it_category`
--
ALTER TABLE `it_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_consignee`
--
ALTER TABLE `it_consignee`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `it_goods`
--
ALTER TABLE `it_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_goods_album`
--
ALTER TABLE `it_goods_album`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_manager`
--
ALTER TABLE `it_manager`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_order`
--
ALTER TABLE `it_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_order_goods`
--
ALTER TABLE `it_order_goods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_privilege`
--
ALTER TABLE `it_privilege`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_role`
--
ALTER TABLE `it_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_type`
--
ALTER TABLE `it_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `it_user`
--
ALTER TABLE `it_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `it_attribute`
--
ALTER TABLE `it_attribute`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `it_cart`
--
ALTER TABLE `it_cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `it_category`
--
ALTER TABLE `it_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类id', AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `it_consignee`
--
ALTER TABLE `it_consignee`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `it_goods`
--
ALTER TABLE `it_goods`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `it_goods_album`
--
ALTER TABLE `it_goods_album`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `it_manager`
--
ALTER TABLE `it_manager`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `it_order`
--
ALTER TABLE `it_order`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `it_order_goods`
--
ALTER TABLE `it_order_goods`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `it_privilege`
--
ALTER TABLE `it_privilege`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- 使用表AUTO_INCREMENT `it_role`
--
ALTER TABLE `it_role`
  MODIFY `id` smallint(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `it_type`
--
ALTER TABLE `it_type`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '????id', AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `it_user`
--
ALTER TABLE `it_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键自增id', AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
