/*
 Navicat MySQL Data Transfer

 Source Server         : localhostdb
 Source Server Type    : MySQL
 Source Server Version : 100417
 Source Host           : localhost:3306
 Source Schema         : yaleng

 Target Server Type    : MySQL
 Target Server Version : 100417
 File Encoding         : 65001

 Date: 14/03/2021 14:48:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for procedure_node
-- ----------------------------
DROP TABLE IF EXISTS `procedure_node`;
CREATE TABLE `procedure_node`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT '主键',
  `p_id` int NOT NULL COMMENT '流程id',
  `parent_id` int NOT NULL DEFAULT 0 COMMENT '父节点id',
  `branch_id` int NOT NULL COMMENT '分支id',
  `parent_branch_id` int NOT NULL COMMENT '外层分支id',
  `priority_level` tinyint NOT NULL DEFAULT 1 COMMENT '优先级',
  `type` tinyint NOT NULL DEFAULT 1 COMMENT '节点类型1条件,2审批人',
  `content` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文案',
  `create_time` timestamp NOT NULL DEFAULT current_timestamp COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT current_timestamp ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
