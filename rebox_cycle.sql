/*
Navicat MySQL Data Transfer

Source Server         : fyd
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : rebox_cycle

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-06-12 14:04:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for rebox_admin
-- ----------------------------
DROP TABLE IF EXISTS `rebox_admin`;
CREATE TABLE `rebox_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `admin_name` varchar(100) NOT NULL DEFAULT '' COMMENT '管理员名',
  `admin_password` varchar(100) NOT NULL DEFAULT '' COMMENT '管理员密码',
  `admin_authority` char(1) NOT NULL DEFAULT '2' COMMENT '管理员权限',
  `admin_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '逻辑删除',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_awardrecord
-- ----------------------------
DROP TABLE IF EXISTS `rebox_awardrecord`;
CREATE TABLE `rebox_awardrecord` (
  `awardRecord_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `awardRecord_userId` int(11) NOT NULL COMMENT '用户id',
  `awardRecord_curCredit` varchar(10) NOT NULL DEFAULT '' COMMENT '抽奖时积分',
  `awardRecord_state` char(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `awardRecord_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`awardRecord_id`),
  KEY `userid` (`awardRecord_userId`),
  CONSTRAINT `userid` FOREIGN KEY (`awardRecord_userId`) REFERENCES `rebox_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_box
-- ----------------------------
DROP TABLE IF EXISTS `rebox_box`;
CREATE TABLE `rebox_box` (
  `box_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `box_isUsing` char(1) NOT NULL DEFAULT '0' COMMENT '是否在使用',
  `box_cartonId` varchar(100) NOT NULL DEFAULT '' COMMENT '对应箱面id',
  `box_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`box_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_carton
-- ----------------------------
DROP TABLE IF EXISTS `rebox_carton`;
CREATE TABLE `rebox_carton` (
  `carton_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `carton_isUseful` char(1) NOT NULL DEFAULT '0' COMMENT '箱面是否可用',
  `carton_isUsing` char(1) NOT NULL DEFAULT '0' COMMENT '是否在使用',
  `carton_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`carton_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_info
-- ----------------------------
DROP TABLE IF EXISTS `rebox_info`;
CREATE TABLE `rebox_info` (
  `info_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `info_name` varchar(100) NOT NULL DEFAULT '' COMMENT '常量名称',
  `info_value` varchar(255) NOT NULL DEFAULT '' COMMENT '常量值',
  PRIMARY KEY (`info_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_recycle
-- ----------------------------
DROP TABLE IF EXISTS `rebox_recycle`;
CREATE TABLE `rebox_recycle` (
  `recycle_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `recycle_capacity` varchar(5) NOT NULL DEFAULT '50' COMMENT '回收柜容量',
  `recycle_position` varchar(100) NOT NULL DEFAULT '' COMMENT '回收柜位置',
  `recycle_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`recycle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_reward
-- ----------------------------
DROP TABLE IF EXISTS `rebox_reward`;
CREATE TABLE `rebox_reward` (
  `reward_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `reward_phone` varchar(20) NOT NULL COMMENT '手机号',
  `reward_boxId` int(11) NOT NULL COMMENT '箱子id',
  `reward_state` char(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `reward_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`reward_id`),
  KEY `boxid` (`reward_boxId`),
  CONSTRAINT `boxid` FOREIGN KEY (`reward_boxId`) REFERENCES `rebox_box` (`box_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_user
-- ----------------------------
DROP TABLE IF EXISTS `rebox_user`;
CREATE TABLE `rebox_user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户表主键',
  `user_name` varchar(100) NOT NULL DEFAULT '匿名用户' COMMENT '姓名',
  `user_username` varchar(100) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_phone` varchar(100) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `user_password` varchar(255) NOT NULL DEFAULT '' COMMENT '用户密码',
  `user_credit` varchar(10) NOT NULL DEFAULT '100' COMMENT '用户信誉积分',
  `user_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '逻辑删除字段',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for rebox_userecord
-- ----------------------------
DROP TABLE IF EXISTS `rebox_userecord`;
CREATE TABLE `rebox_userecord` (
  `useRecord_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `useRecord_userId` int(11) NOT NULL COMMENT '用户id',
  `useRecord_boxId` int(11) NOT NULL COMMENT '箱子id',
  `useRecord_state` char(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `useRecord_isdelete` char(1) NOT NULL DEFAULT '0' COMMENT '是否删除',
  PRIMARY KEY (`useRecord_id`),
  KEY `use_userid` (`useRecord_userId`),
  KEY `use_boxid` (`useRecord_boxId`),
  CONSTRAINT `use_boxid` FOREIGN KEY (`useRecord_boxId`) REFERENCES `rebox_box` (`box_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `use_userid` FOREIGN KEY (`useRecord_userId`) REFERENCES `rebox_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
