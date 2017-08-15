/*
Navicat MySQL Data Transfer

Source Server         : phpstudy
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : inventory_db

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-08-15 08:11:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ivt_dep
-- ----------------------------
DROP TABLE IF EXISTS `ivt_dep`;
CREATE TABLE `ivt_dep` (
  `id` int(11) NOT NULL,
  `dep_code` varchar(15) NOT NULL COMMENT '部门编码',
  `dep_name` varchar(50) NOT NULL COMMENT '部门名称',
  `dep_addr` varchar(200) DEFAULT NULL COMMENT '部门地址',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='部门';

-- ----------------------------
-- Records of ivt_dep
-- ----------------------------
INSERT INTO `ivt_dep` VALUES ('0', '0001', '区公司', '广西南宁市');

-- ----------------------------
-- Table structure for ivt_dep_res_link
-- ----------------------------
DROP TABLE IF EXISTS `ivt_dep_res_link`;
CREATE TABLE `ivt_dep_res_link` (
  `id` int(11) NOT NULL,
  `dep_code` varchar(15) NOT NULL COMMENT '部门编码',
  `res_code` varchar(15) NOT NULL COMMENT '资产编码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='部门资产';

-- ----------------------------
-- Records of ivt_dep_res_link
-- ----------------------------

-- ----------------------------
-- Table structure for ivt_inventory
-- ----------------------------
DROP TABLE IF EXISTS `ivt_inventory`;
CREATE TABLE `ivt_inventory` (
  `id` int(20) NOT NULL,
  `dep_code` varchar(15) NOT NULL COMMENT '部门编码',
  `res_code` varchar(15) NOT NULL COMMENT '资产编码',
  `time` int(11) NOT NULL COMMENT '盘点时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='盘点';

-- ----------------------------
-- Records of ivt_inventory
-- ----------------------------

-- ----------------------------
-- Table structure for ivt_menu
-- ----------------------------
DROP TABLE IF EXISTS `ivt_menu`;
CREATE TABLE `ivt_menu` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(20) NOT NULL COMMENT '菜单名称',
  `controller` varchar(40) NOT NULL COMMENT '控制器',
  `method` varchar(40) NOT NULL COMMENT '排序',
  `icon` varchar(40) NOT NULL COMMENT '图标',
  `level` varchar(255) NOT NULL DEFAULT '2' COMMENT '菜单等级，2级',
  `parent_id` int(4) NOT NULL COMMENT '上级菜单',
  `sort` int(4) DEFAULT NULL COMMENT '排序',
  `status` int(2) DEFAULT '1' COMMENT '状态1：可用0：禁用',
  `view` int(2) DEFAULT '1' COMMENT '是否可见',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='菜单';

-- ----------------------------
-- Records of ivt_menu
-- ----------------------------
INSERT INTO `ivt_menu` VALUES ('1', '权限管理', '', '', '', '1', '0', '0', '1', '1');
INSERT INTO `ivt_menu` VALUES ('11', '用户管理', 'user', 'index', '', '1', '0', '0', '1', '1');
INSERT INTO `ivt_menu` VALUES ('4', '菜单管理', 'menu', 'index', '', '2', '1', '0', '1', '1');
INSERT INTO `ivt_menu` VALUES ('6', '角色管理', 'role', 'index', '', '2', '1', '0', '1', '1');
INSERT INTO `ivt_menu` VALUES ('12', '部门管理', 'org', 'index', '', '1', '0', '0', '1', '1');
INSERT INTO `ivt_menu` VALUES ('13', '密码修改', 'password', 'index', '', '1', '0', '0', '1', '1');

-- ----------------------------
-- Table structure for ivt_menu_role
-- ----------------------------
DROP TABLE IF EXISTS `ivt_menu_role`;
CREATE TABLE `ivt_menu_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_id` int(4) NOT NULL COMMENT '菜单id',
  `role_id` int(4) NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8 COMMENT='角色菜单表';

-- ----------------------------
-- Records of ivt_menu_role
-- ----------------------------
INSERT INTO `ivt_menu_role` VALUES ('21', '4', '1');
INSERT INTO `ivt_menu_role` VALUES ('20', '13', '1');
INSERT INTO `ivt_menu_role` VALUES ('19', '12', '1');
INSERT INTO `ivt_menu_role` VALUES ('18', '11', '1');
INSERT INTO `ivt_menu_role` VALUES ('17', '1', '1');
INSERT INTO `ivt_menu_role` VALUES ('22', '6', '1');

-- ----------------------------
-- Table structure for ivt_org
-- ----------------------------
DROP TABLE IF EXISTS `ivt_org`;
CREATE TABLE `ivt_org` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `org_name` varchar(50) NOT NULL COMMENT '组织名称',
  `lft` int(11) NOT NULL COMMENT '左值',
  `rgt` int(11) NOT NULL COMMENT '右值',
  `parent_org_id` int(11) NOT NULL COMMENT '父id',
  `level` int(2) DEFAULT NULL COMMENT '层级',
  `sort` int(11) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='组织结构';

-- ----------------------------
-- Records of ivt_org
-- ----------------------------
INSERT INTO `ivt_org` VALUES ('1', '区公司', '1', '14', '-1', '1', null);
INSERT INTO `ivt_org` VALUES ('2', '南宁市', '4', '13', '1', '2', '1');
INSERT INTO `ivt_org` VALUES ('3', '北海市', '2', '3', '1', '2', '1');
INSERT INTO `ivt_org` VALUES ('4', '西乡塘区', '11', '12', '2', '3', '1');
INSERT INTO `ivt_org` VALUES ('5', '兴宁区', '9', '10', '2', '3', '1');
INSERT INTO `ivt_org` VALUES ('6', '良庆区', '7', '8', '2', '3', '1');
INSERT INTO `ivt_org` VALUES ('7', '江南区', '5', '6', '2', '3', '1');

-- ----------------------------
-- Table structure for ivt_org_user
-- ----------------------------
DROP TABLE IF EXISTS `ivt_org_user`;
CREATE TABLE `ivt_org_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `org_id` int(11) NOT NULL COMMENT '组织id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='组织用户表';

-- ----------------------------
-- Records of ivt_org_user
-- ----------------------------
INSERT INTO `ivt_org_user` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for ivt_res
-- ----------------------------
DROP TABLE IF EXISTS `ivt_res`;
CREATE TABLE `ivt_res` (
  `id` int(11) NOT NULL,
  `res_code` varchar(15) NOT NULL COMMENT '资产编码',
  `res_name` varchar(50) NOT NULL COMMENT '资产名称',
  `res_detail` text COMMENT '资产详细信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='资产';

-- ----------------------------
-- Records of ivt_res
-- ----------------------------

-- ----------------------------
-- Table structure for ivt_role
-- ----------------------------
DROP TABLE IF EXISTS `ivt_role`;
CREATE TABLE `ivt_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) NOT NULL COMMENT '角色名称',
  `describe` varchar(100) NOT NULL COMMENT '描述',
  `sort` int(4) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of ivt_role
-- ----------------------------
INSERT INTO `ivt_role` VALUES ('1', '超级管理员', '超级管理员', null);

-- ----------------------------
-- Table structure for ivt_role_user
-- ----------------------------
DROP TABLE IF EXISTS `ivt_role_user`;
CREATE TABLE `ivt_role_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL COMMENT '用户id',
  `role_id` int(4) NOT NULL COMMENT '角色id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of ivt_role_user
-- ----------------------------
INSERT INTO `ivt_role_user` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for ivt_user
-- ----------------------------
DROP TABLE IF EXISTS `ivt_user`;
CREATE TABLE `ivt_user` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL COMMENT '姓名',
  `mobile` varchar(11) DEFAULT NULL COMMENT '手机号',
  `account` varchar(20) NOT NULL COMMENT '帐号',
  `password` varchar(100) NOT NULL COMMENT '密码',
  `sex` int(2) DEFAULT NULL COMMENT '性别1：男0：女',
  `sort` int(8) DEFAULT NULL COMMENT '排序，数字越大越靠前',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '帐号状态1：启用0：禁用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of ivt_user
-- ----------------------------
INSERT INTO `ivt_user` VALUES ('1', '超级管理员', null, 'admin', '$2y$10$ZirngzAaEb4L4qLtr6iPX.BlHdHvLpqKCdtbqcCvdq8gPFeSPqoxq', null, null, '1');
