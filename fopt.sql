-- phpMyAdmin SQL Dump
-- version 4.2.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 14-08-09 04:40
-- 서버 버전: 5.5.39
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fopt`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `ci_sessions`
--

CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` varchar(40) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `ip_address` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `user_agent` varchar(120) CHARACTER SET utf8 NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 테이블 구조 `code`
--

CREATE TABLE IF NOT EXISTS `code` (
`code_idx` int(11) NOT NULL COMMENT '일련번호',
  `reg_user_idx` int(11) NOT NULL COMMENT '등록자 idx',
  `state_id` enum('wait','compiling','compile error','simulating','runtime error','complete') CHARACTER SET utf8 NOT NULL COMMENT '상태',
  `open_level` int(11) NOT NULL COMMENT '공개정도',
  `reg_time` datetime NOT NULL COMMENT '등록시각',
  `update_time` datetime NOT NULL COMMENT '수정시각',
  `encode_rate` double DEFAULT NULL,
  `correct_ratio` double DEFAULT NULL COMMENT 'CorrectRatio(Avg)',
  `correct_packet_ratio` double DEFAULT NULL COMMENT 'Correct Packet Ratio(Avg)',
  `diff_ratio` double DEFAULT NULL COMMENT 'Ratio 변화량',
  `result_ce` double DEFAULT NULL COMMENT 'Correct Error Rate (Avg)',
  `result_ce_std` double DEFAULT NULL COMMENT 'Correct Error Rate (Std)',
  `result_cpe` double DEFAULT NULL COMMENT 'Correct Packet Error Rate (Avg)',
  `result_cpe_std` double DEFAULT NULL COMMENT 'Correct Packet Error Rate (Std)'
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=388 ;


-- --------------------------------------------------------

--
-- 테이블 구조 `mutex`
--

CREATE TABLE IF NOT EXISTS `mutex` (
  `semaphore` int(11) NOT NULL,
  `idx` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 테이블의 덤프 데이터 `mutex`
--

INSERT INTO `mutex` (`semaphore`, `idx`) VALUES
(2, 1);

-- --------------------------------------------------------

--
-- 테이블 구조 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_idx` int(11) NOT NULL,
  `user_id` varchar(50) CHARACTER SET utf8 NOT NULL,
  `user_pw` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(50) CHARACTER SET utf8 NOT NULL,
  `reg_time` datetime NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;


--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `ci_sessions`
--
ALTER TABLE `ci_sessions`
 ADD PRIMARY KEY (`session_id`), ADD KEY `last_activity_idx` (`last_activity`);

--
-- 테이블의 인덱스 `code`
--
ALTER TABLE `code`
 ADD PRIMARY KEY (`code_idx`);

--
-- 테이블의 인덱스 `mutex`
--
ALTER TABLE `mutex`
 ADD PRIMARY KEY (`idx`);

--
-- 테이블의 인덱스 `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_idx`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `code`
--
ALTER TABLE `code`
MODIFY `code_idx` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',AUTO_INCREMENT=388;
--
-- 테이블의 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
MODIFY `user_idx` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=31;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
