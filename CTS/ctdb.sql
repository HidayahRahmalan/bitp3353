-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2024 at 08:59 AM
-- Server version: 5.5.39
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ctdb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `add_course` (IN `p_course_code` VARCHAR(50), IN `p_title` VARCHAR(100), IN `p_credit_hour` INT(11), IN `p_type` VARCHAR(20), IN `p_tpfile` VARCHAR(100), IN `p_int_id` INT(11))   BEGIN
INSERT INTO course (course_code, title, credit_hour, type, tpfile, int_id) 
VALUES (p_course_code, p_title, p_credit_hour, p_type, p_tpfile, p_int_id);
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `add_institution` (IN `p_int_name` VARCHAR(200), IN `p_branch` VARCHAR(200))   BEGIN
INSERT INTO institution (int_name,branch) 
VALUES (p_int_name,p_branch);
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `add_lecturer` (IN `p_lect_name` VARCHAR(100), IN `p_phoneno` VARCHAR(20), IN `p_email` VARCHAR(50), IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(50), IN `p_role` VARCHAR(20))   BEGIN
INSERT INTO lecturer (lect_name,phoneno,email,username,password,role) 
VALUES (p_lect_name,p_phoneno,p_email,p_username,p_password,p_role);
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `add_programme` (IN `p_prog_name` VARCHAR(100), IN `p_prog_code` VARCHAR(50))   BEGIN
INSERT INTO programme (prog_name,prog_code) 
VALUES (p_prog_name,p_prog_code);
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `add_student` (IN `p_name` VARCHAR(100), IN `p_icno` VARCHAR(20), IN `p_faculty` VARCHAR(200), IN `p_prog_id` INT(11), IN `p_session` VARCHAR(11), IN `p_email` VARCHAR(50), IN `p_phone` VARCHAR(20), IN `p_int_id` INT(11), IN `p_latest_int` VARCHAR(100), IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(50), IN `p_lect_id` INT(11), IN `p_admin_id` INT(11))   BEGIN
INSERT INTO student (name, icno, faculty, prog_id, session, email, phone, int_id,latest_int, username, password,lect_id,admin_id) 
VALUES (p_name, p_icno, p_faculty, p_prog_id, p_session, p_email, p_phone, p_int_id, p_latest_int , p_username, p_password,p_lect_id,p_admin_id);
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `delete_course` (IN `p_course_id` INT(11))   BEGIN
	DELETE FROM course WHERE course_id=p_course_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `delete_institution` (IN `p_int_id` INT(11))   BEGIN
	DELETE FROM institution WHERE int_id=p_int_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `delete_lecturer` (IN `p_lect_id` INT(11))   BEGIN
	DELETE FROM lecturer WHERE lect_id=p_lect_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `delete_programme` (IN `p_prog_id` INT(11))   BEGIN
	DELETE FROM programme WHERE prog_id=p_prog_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `delete_student` (IN `p_stud_id` INT(11))   BEGIN
	DELETE FROM student WHERE stud_id=p_stud_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `update_institution` (IN `p_int_id` INT(11), IN `p_int_name` VARCHAR(200), IN `p_branch` VARCHAR(200))   BEGIN
	UPDATE institution 
    SET int_name=p_int_name, branch=p_branch
    WHERE int_id=p_int_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `update_lecturer` (IN `p_lect_id` INT(11), IN `p_lect_name` VARCHAR(100), IN `p_phoneno` VARCHAR(20), IN `p_email` VARCHAR(50), IN `p_username` VARCHAR(50), IN `p_role` VARCHAR(20))   BEGIN
	UPDATE lecturer 
    SET lect_name=p_lect_name,  phoneno=p_phoneno, email=p_email ,username=p_username, role=p_role
    WHERE lect_id=p_lect_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `update_programme` (IN `p_prog_id` INT(11), IN `p_prog_name` VARCHAR(100), IN `p_prog_code` VARCHAR(50))   BEGIN
    UPDATE programme 
    SET prog_name=p_prog_name, prog_code=p_prog_code
    WHERE prog_id=p_prog_id;
END$$

CREATE DEFINER=`ctdb`@`localhost` PROCEDURE `update_student` (IN `p_stud_id` INT(11), IN `p_name` VARCHAR(100), IN `p_icno` VARCHAR(20), IN `p_prog_id` INT(11), IN `p_email` VARCHAR(50), IN `p_phone` VARCHAR(20), IN `p_int_id` INT(11), IN `p_username` VARCHAR(50), IN `p_lect_id` INT(11))   BEGIN
	UPDATE student 
    SET name=p_name, 
    icno=p_icno, 
    prog_id= p_prog_id, 
    email=p_email, 
    phone=p_phone,
    int_id=p_int_id, 
    username=p_username, 
    lect_id=p_lect_id 
    WHERE stud_id=p_stud_id;
END$$

--
-- Functions
--
CREATE DEFINER=`ctdb`@`localhost` FUNCTION `insert_grade` (`p_stud_id` INT(11), `p_course_id` INT(11), `p_similar` VARCHAR(255), `p_grade` VARCHAR(11)) RETURNS VARCHAR(100) CHARSET utf8mb4  BEGIN
    DECLARE status VARCHAR(100);
    
    INSERT INTO grade (stud_id, course_id, similar, grade)
    VALUES (p_stud_id, p_course_id, p_similar, p_grade);
    
    RETURN status;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `accepted_new_tp_view`
-- (See below for the actual view)
--
CREATE TABLE `accepted_new_tp_view` (
`code` varchar(50)
,`title` varchar(100)
,`credit_hour` int(11)
,`status` varchar(50)
,`tplink` varchar(100)
,`date` timestamp
,`review_date` date
,`int_name` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `accepted_requests_view`
-- (See below for the actual view)
--
CREATE TABLE `accepted_requests_view` (
`req_id` int(11)
,`course_code` varchar(50)
,`title` varchar(100)
,`message` varchar(100)
,`status` varchar(20)
,`link` varchar(100)
,`request_date` timestamp
);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `username`, `password`) VALUES
(1, 'ADMIN', 'admin', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Stand-in structure for view `bachelor_courses_view`
-- (See below for the actual view)
--
CREATE TABLE `bachelor_courses_view` (
`course_id` int(11)
,`course_code` varchar(50)
,`title` varchar(100)
,`credit_hour` int(11)
,`type` varchar(20)
,`tpfile` varchar(100)
,`int_name` varchar(200)
,`branch` varchar(200)
);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `course_code` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `credit_hour` int(11) NOT NULL,
  `type` varchar(20) NOT NULL,
  `tpfile` varchar(100) NOT NULL,
  `int_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_code`, `title`, `credit_hour`, `type`, `tpfile`, `int_id`) VALUES
(1, 'DITI 1243', 'LINEAR ALGEBRA AND NUMERICAL METHODS', 3, 'Diploma', '5c5ee2ca62a42c849846f243ba644cac.pdf', 1),
(2, 'DITP 1113', 'PROGRAMMING 1', 3, 'Diploma', '4ff61d2ad7b4de6f47ba06b374d0d839.pdf', 1),
(3, 'DITP 1333', 'DATABASE', 3, 'Diploma', '5d2d7565b028bba89b3166cf115675b4.pdf', 1),
(4, 'DITS 1133', 'COMPUTER ORGANISATION AND ARCHITECTURE', 3, 'Diploma', '894ddb1d668288cb46b2c2fef27085b5.pdf', 1),
(5, 'DITM 2113 ', 'MULTIMEDIA SYSTEM', 3, 'Diploma', 'f672ab3d53152161603a2bfc6ceb61ab.pdf', 1),
(6, 'DITI 1233', 'CALCULUS', 3, 'Diploma', '6890549a6be042647f8f0e6aaa4689f2.pdf', 1),
(7, 'DITP 1123 ', 'PROGRAMMING 2', 3, 'Diploma', '9fbd01dc44e136d2ffc0cf75a4b92bcd.pdf', 1),
(8, 'DITS 2213', 'OPERATING SYSTEM', 3, 'Diploma', '529ab6375a416e17a22b81b983001675.pdf', 1),
(9, 'DITI 2233 ', 'STATISTIC AND PROBABILITY', 3, 'Diploma', 'c96b06c442898f7a82f0599a786a5956.pdf', 1),
(10, 'DITP 2113 ', 'DATA STRUCTURE AND ALGORITHM', 3, 'Diploma', '0fa9408ceebdbc548050de9ffe083489.pdf', 1),
(11, 'DITP 3113 ', 'OBJECT-ORIENTED PROGRAMMING', 3, 'Diploma', 'b059f1818ad1f685adf69f945aaea79e.pdf', 1),
(12, 'DITS 2313', 'DATA COMMUNICATION AND NETWORKING', 3, 'Diploma', 'd39deaa11a3415c1e8c2d1f98c83e20c.pdf', 1),
(13, 'DITM 2123 ', 'WEB PROGRAMMING', 3, 'Diploma', 'b52d2e01ba0bf250da146b8210657386.pdf', 1),
(14, 'DITU 3964 ', 'DIPLOMA PROJECT', 3, 'Diploma', '90feb16c1e9f88b502259133bafb14de.pdf', 1),
(15, 'BITI 1213 ', 'LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 3, 'Bachelor', 'c7daecb96b9e3465889df7f45afabb0c.pdf', 1),
(16, 'BITP 1113', 'PROGRAMMING TECHNIQUES', 3, 'Bachelor', '81cc21475eb8a7c1dabb1f006b38504e.pdf', 1),
(17, 'BITM 1113', 'MULTIMEDIA SYSTEM', 3, 'Bachelor', '3aef01bafca8c7a0b4b7afba10030852.pdf', 1),
(18, 'BITS 1123', 'COMPUTER ORGANISATION AND ARCHITECTURE', 3, 'Bachelor', '95d4eeae8738da0795b1b62458b8b029.pdf', 1),
(19, 'BITI 1223', 'CALCULUS AND NUMERICAL METHODS', 3, 'Bachelor', '7fa9416a166758c2152c6f60a6ad4c40.pdf', 1),
(20, 'BITP 1123', 'DATA STRUCTURE AND ALGORITHM', 3, 'Bachelor', 'f8cf4efc01fd3ad11d9c69419aaa5a84.pdf', 1),
(21, 'BITP 1323', 'DATABASE', 3, 'Bachelor', '7a00d878b0ba774ca612a18e8009888b.pdf', 1),
(22, 'BITP 2213', 'SOFTWARE ENGINEERING', 3, 'Bachelor', 'c2633586339afbb6a8452ad219a36330.pdf', 1),
(23, 'BITU 2913', 'WORKSHOP 1', 3, 'Bachelor', '4b4b98e462a214673189f7075da26270.pdf', 1),
(24, 'BITI 2233', 'STATISTICS AND PROBABILITY', 3, 'Bachelor', 'd998f956d366654876db05995057cf08.pdf', 1),
(25, 'BITM 2313 ', 'HUMAN COMPUTER INTERACTION', 3, 'Bachelor', '4e09a96688af3dc42903e9614a3fec22.pdf', 1),
(26, 'BITS 1213', 'OPERATING SYSTEM', 3, 'Bachelor', '80460ef949a03a139d027c18416fcaa5.pdf', 1),
(27, 'BITP 2303 ', 'DATABASE PROGRAMMING', 3, 'Bachelor', '37056e327c046d50c5335c2618da95f4.pdf', 1),
(28, 'BITP 2313', 'DATABASE DESIGN', 3, 'Bachelor', '5a475fe23db1c7bc97a020cb2c6e2e71.pdf', 1),
(29, 'BITI 1113 ', 'ARTIFICAL INTELLIGENCE', 3, 'Bachelor', 'acdda2ade14d411f29384a28f99c5a7a.pdf', 1),
(30, 'BITP 3113', 'OBJECT-ORIENTED PROGRAMMING', 3, 'Bachelor', '3cb89842037fe7bf0b0fbd2899c930e3.pdf', 1),
(31, 'BITS 1313 ', 'DATA COMMUNICATION AND NETWORKING', 3, 'Bachelor', 'a537d150e0fc0701fb35c483a4466ebb.pdf', 1),
(33, 'BITP 2323', 'DATABASE ADMINISTRATION', 3, 'Bachelor', '48e2e2afb7e0a790b52ed0dbcfee59e0.pdf', 1),
(46, 'DITP 2313', 'DATABASE PROGRAMMING', 3, 'Diploma', 'a26f77606b3e6484172d60c8cfb459f2.pdf', 1),
(47, 'DITP 2213', 'SOFTWARE ENGINEERING', 3, 'Diploma', 'b81af90b89192ece75f82eb39f502732.pdf', 1),
(48, 'DITM 2313', 'HUMAN COMPUTER INTERACTION', 3, 'Diploma', 'dcbfb64c3db4826612d9ce487e4d930e.pdf', 1),
(50, 'DITI1213', 'DISCRETE MATHEMATICS', 3, 'Diploma', '5c5ee2ca62a42c849846f243ba644cac.pdf', 1);

--
-- Triggers `course`
--
DELIMITER $$
CREATE TRIGGER `before_insert_course` BEFORE INSERT ON `course` FOR EACH ROW BEGIN
    DECLARE course_count INT;

    -- Check if the course code already exists
    SELECT COUNT(*) INTO course_count
    FROM course
    WHERE course_code = NEW.course_code;

    -- If course code already exists, raise an error
    IF course_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Course code already exists. Please use another course code.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `course_details_view`
-- (See below for the actual view)
--
CREATE TABLE `course_details_view` (
`course_id` int(11)
,`course_code` varchar(50)
,`title` varchar(100)
,`credit_hour` int(11)
,`type` varchar(20)
,`tpfile` varchar(100)
,`int_name` varchar(200)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `diploma_courses`
-- (See below for the actual view)
--
CREATE TABLE `diploma_courses` (
`course_id` int(11)
,`course_code` varchar(50)
,`title` varchar(100)
,`credit_hour` int(11)
,`type` varchar(20)
,`tpfile` varchar(100)
,`int_name` varchar(200)
,`branch` varchar(200)
);

-- --------------------------------------------------------

--
-- Table structure for table `grade`
--

CREATE TABLE `grade` (
  `grade_id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `similar` varchar(255) NOT NULL,
  `grade` varchar(255) NOT NULL,
  `similar2` varchar(255) NOT NULL,
  `grade2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `grade`
--

INSERT INTO `grade` (`grade_id`, `stud_id`, `course_id`, `similar`, `grade`, `similar2`, `grade2`) VALUES
(582, 9, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(583, 9, 16, 'DITP 1113 Programming 1 UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(584, 9, 17, 'DITM 2113  Multimedia System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(585, 9, 18, 'DITS 1133 Computer Organisation and Architecture UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(586, 9, 19, 'DITI 1233 Calculus and Numerical Methods UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(587, 9, 20, 'DITP 2113  Data Structure and Algorithm UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(588, 9, 21, 'DITP 1333 Database UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(589, 9, 22, 'N/A', 'N/A', '', ''),
(590, 9, 23, 'DITU 3964  Diploma Project UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(591, 9, 24, 'DITI 2233  Statistics and Probability UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(592, 9, 25, 'N/A', 'N/A', '', ''),
(593, 9, 26, 'DITS 2213 Operating System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'C-', '', ''),
(594, 9, 27, 'N/A', 'N/A', '', ''),
(595, 9, 28, 'N/A', 'N/A', '', ''),
(596, 9, 29, 'N/A', 'N/A', '', ''),
(597, 9, 30, 'DITP 3113  Object-oriented Programming UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'D', '', ''),
(598, 9, 31, 'DITS 2313 Data Communication and Networking UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(600, 9, 33, 'N/A', 'N/A', '', ''),
(607, 4, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B', '', ''),
(608, 4, 16, 'DITP 1113 Programming 1 UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(609, 4, 17, 'DITM 2113  Multimedia System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(610, 4, 18, 'DITS 1133 Computer Organisation and Architecture UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(611, 4, 19, 'DITI 1233 Calculus and Numerical Methods UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(612, 4, 20, 'DITP 2113  Data Structure and Algorithm UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(613, 4, 21, 'DITP 1333 Database UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(614, 4, 22, 'N/A', 'N/A', '', ''),
(615, 4, 23, 'DITU 3964  Diploma Project UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(616, 4, 24, 'DITI 2233  Statistics and Probability UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(617, 4, 25, 'N/A', 'N/A', '', ''),
(618, 4, 26, 'DITS 2213 Operating System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A-', '', ''),
(619, 4, 27, 'N/A', 'N/A', '', ''),
(620, 4, 28, 'N/A', 'N/A', '', ''),
(621, 4, 29, 'N/A', 'N/A', '', ''),
(622, 4, 30, 'DITP 3113  Object-oriented Programming UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'N/A', '', ''),
(623, 4, 31, 'DITS 2313 Data Communication and Networking UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B', '', ''),
(625, 4, 33, 'N/A', 'N/A', '', ''),
(632, 10, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(633, 10, 16, 'DITP 1113 Programming 1 UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(634, 10, 17, 'DITM 2113  Multimedia System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(635, 10, 18, 'DITS 1133 Computer Organisation and Architecture UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(636, 10, 19, 'DITI 1233 Calculus and Numerical Methods UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(637, 10, 20, 'DITP 2113  Data Structure and Algorithm UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(638, 10, 21, 'DITP 1333 Database UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(639, 10, 22, 'N/A', 'N/A', '', ''),
(640, 10, 23, 'DITU 3964  Diploma Project UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(641, 10, 24, 'DITI 2233  Statistics and Probability UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(642, 10, 25, 'N/A', 'N/A', '', ''),
(643, 10, 26, 'DITS 2213 Operating System UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(644, 10, 27, 'N/A', 'N/A', '', ''),
(645, 10, 28, 'N/A', 'N/A', '', ''),
(646, 10, 29, 'N/A', 'N/A', '', ''),
(647, 10, 30, 'DITP 3113  Object-oriented Programming UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(648, 10, 31, 'DITS 2313 Data Communication and Networking UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(650, 10, 33, 'N/A', 'N/A', '', ''),
(657, 11, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'B', '', ''),
(658, 11, 16, 'DITP 1113 Programming 1', 'A-', '', ''),
(659, 11, 17, 'DITM 2113  Multimedia System', 'A-', '', ''),
(660, 11, 18, 'DITS 1133 Computer Organisation and Architecture', 'A-', '', ''),
(661, 11, 19, 'DITI 1233 Calculus and Numerical Methods', 'A-', '', ''),
(662, 11, 20, 'DITP 2113  Data Structure and Algorithm', 'A-', '', ''),
(663, 11, 21, 'DITP 1333 Database', 'A-', '', ''),
(664, 11, 22, 'N/A', 'N/A', '', ''),
(665, 11, 23, 'DITU 3964  Diploma Project', 'A', '', ''),
(666, 11, 24, 'DITI 2233  Statistics and Probability', 'A', '', ''),
(667, 11, 25, 'N/A', 'N/A', '', ''),
(668, 11, 26, 'DITS 2213 Operating System', 'A-', '', ''),
(669, 11, 27, 'N/A', 'N/A', '', ''),
(670, 11, 28, 'N/A', 'N/A', '', ''),
(671, 11, 29, 'N/A', 'N/A', '', ''),
(672, 11, 30, 'DITP 3113  Object-oriented Programming', 'A-', '', ''),
(673, 11, 31, 'DITS 2313 Data Communication and Networking', 'B+', '', ''),
(675, 11, 33, 'N/A', 'N/A', '', ''),
(682, 12, 15, 'N/A', 'N/A', '', ''),
(683, 12, 16, 'N/A', 'N/A', '', ''),
(684, 12, 17, 'N/A', 'N/A', '', ''),
(685, 12, 18, 'N/A', 'N/A', '', ''),
(686, 12, 19, 'UTMA 2468 FUNDEMNTAL OF CLACULUS', 'A', '', ''),
(687, 12, 20, 'N/A', 'N/A', '', ''),
(688, 12, 21, 'N/A', 'N/A', '', ''),
(689, 12, 22, 'N/A', 'N/A', '', ''),
(690, 12, 23, 'N/A', 'N/A', '', ''),
(691, 12, 24, 'N/A', 'N/A', '', ''),
(692, 12, 25, 'N/A', 'N/A', '', ''),
(693, 12, 26, 'N/A', 'N/A', '', ''),
(694, 12, 27, 'N/A', 'N/A', '', ''),
(695, 12, 28, 'N/A', 'N/A', '', ''),
(696, 12, 29, 'N/A', 'N/A', '', ''),
(697, 12, 30, 'N/A', 'N/A', '', ''),
(698, 12, 31, 'N/A', 'N/A', '', ''),
(700, 12, 33, 'N/A', 'N/A', '', ''),
(732, 16, 15, 'N/A', 'N/A', '', ''),
(733, 16, 16, 'N/A', 'N/A', '', ''),
(734, 16, 17, 'N/A', 'N/A', '', ''),
(735, 16, 18, 'N/A', 'N/A', '', ''),
(736, 16, 19, 'N/A', 'N/A', '', ''),
(737, 16, 20, 'N/A', 'N/A', '', ''),
(738, 16, 21, 'N/A', 'N/A', '', ''),
(739, 16, 22, 'N/A', 'N/A', '', ''),
(740, 16, 23, 'N/A', 'N/A', '', ''),
(741, 16, 24, 'N/A', 'N/A', '', ''),
(742, 16, 25, 'N/A', 'N/A', '', ''),
(743, 16, 26, 'N/A', 'N/A', '', ''),
(744, 16, 27, 'N/A', 'N/A', '', ''),
(745, 16, 28, 'N/A', 'N/A', '', ''),
(746, 16, 29, 'N/A', 'N/A', '', ''),
(747, 16, 30, 'N/A', 'N/A', '', ''),
(748, 16, 31, 'N/A', 'N/A', '', ''),
(750, 16, 33, 'N/A', 'N/A', '', ''),
(761, 13, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(762, 13, 16, 'DITP 1113 Programming 1', 'A', '', ''),
(763, 13, 17, 'DITM 2113  Multimedia System', 'A', '', ''),
(764, 13, 18, 'DITS 1133 Computer Organisation and Architecture', 'A-', '', ''),
(765, 13, 19, 'DITI 1233 Calculus and Numerical Methods', 'B+', '', ''),
(766, 13, 20, 'DITP 2113  Data Structure and Algorithm', 'A-', '', ''),
(767, 13, 21, 'DITP 1333 Database', 'A', '', ''),
(768, 13, 22, 'N/A', 'N/A', '', ''),
(769, 13, 23, 'DITU 3964  Diploma Project', 'B', '', ''),
(770, 13, 24, 'DITI 2233  Statistics and Probability', 'A-', '', ''),
(771, 13, 25, 'N/A', 'N/A', '', ''),
(772, 13, 26, 'DITS 2213 Operating System', 'C-', '', ''),
(773, 13, 27, 'N/A', 'N/A', '', ''),
(774, 13, 28, 'N/A', 'N/A', '', ''),
(775, 13, 29, 'N/A', 'N/A', '', ''),
(776, 13, 30, 'DITP 3113  Object-oriented Programming', 'A-', '', ''),
(777, 13, 31, 'DITS 2313 Data Communication and Networking', 'A', '', ''),
(779, 13, 33, 'N/A', 'N/A', '', ''),
(786, 27, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(787, 27, 16, 'DITP 1113 Programming 1', 'A', '', ''),
(788, 27, 17, 'DITM 2113  Multimedia System', 'A', '', ''),
(789, 27, 18, 'DITS 1133 Computer Organisation and Architecture', 'A', '', ''),
(790, 27, 19, 'DITI 1233 Calculus and Numerical Methods', 'A', '', ''),
(791, 27, 20, 'DITP 2113  Data Structure and Algorithm', 'A', '', ''),
(792, 27, 21, 'DITP 1333 Database', 'A', '', ''),
(793, 27, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'N/A', '', ''),
(794, 27, 23, 'DITU 3964  Diploma Project', 'A', '', ''),
(795, 27, 24, 'DITI 2233  Statistics and Probability', 'A', '', ''),
(796, 27, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'B+', '', ''),
(797, 27, 26, 'DITS 2213 Operating System', 'A-', '', ''),
(798, 27, 27, 'DITP 2313 DATABASE PROGRAMMING', 'C+', '', ''),
(799, 27, 28, 'N/A', 'N/A', '', ''),
(800, 27, 29, 'DITI 1113 ARTIFICAL INTELLIGENCE', 'B-', '', ''),
(801, 27, 30, 'DITP 3113  Object-oriented Programming', 'B+', '', ''),
(802, 27, 31, 'DITS 2313 Data Communication and Networking', 'A', '', ''),
(804, 27, 33, 'N/A', 'N/A', '', ''),
(811, 28, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(812, 28, 16, 'DITP 1113 Programming 1', 'A', '', ''),
(813, 28, 17, 'DITM 2113  Multimedia System', 'A', '', ''),
(814, 28, 18, 'DITS 1133 Computer Organisation and Architecture', 'A', '', ''),
(815, 28, 19, 'DITI 1233 Calculus and Numerical Methods', 'A-', '', ''),
(816, 28, 20, 'DITP 2113  Data Structure and Algorithm', 'A', '', ''),
(817, 28, 21, 'DITP 1333 Database', 'A', '', ''),
(818, 28, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A', '', ''),
(819, 28, 23, 'DITU 3964  Diploma Project', 'A', '', ''),
(820, 28, 24, 'DITI 2233  Statistics and Probability', 'A', '', ''),
(821, 28, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'A', '', ''),
(822, 28, 26, 'DITS 2213 Operating System', 'A', '', ''),
(823, 28, 27, 'DITP 2313 DATABASE PROGRAMMING', 'A', '', ''),
(824, 28, 28, 'N/A', 'N/A', '', ''),
(825, 28, 29, 'N/A', 'N/A', '', ''),
(826, 28, 30, 'DITP 3113  Object-oriented Programming', 'A', '', ''),
(827, 28, 31, 'N/A', 'N/A', '', ''),
(829, 28, 33, 'N/A', 'N/A', '', ''),
(861, 24, 15, 'DITI1243 Linear Algebra and Numerical Methods', 'A', 'DITI1213 Discrete Mathematics', 'A'),
(862, 24, 16, 'DITP 1113 Programming 1', 'C', 'DITP 1113 Programming 1', 'C-'),
(863, 24, 17, 'DITM 2113  Multimedia System', 'A', 'N/A', 'N/A'),
(864, 24, 18, 'DITS 1133 Computer Organisation and Architecture', 'A', 'N/A', 'N/A'),
(865, 24, 19, 'DITI 1233 Calculus', 'D', 'DITI1213 Discrete Mathematics', 'A'),
(866, 24, 20, 'DITP 2113  Data Structure and Algorithm', 'A', 'N/A', 'N/A'),
(867, 24, 21, 'DITP 1333 Database', 'A', 'N/A', 'N/A'),
(868, 24, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A', 'N/A', 'N/A'),
(869, 24, 23, 'DITU 3964  Diploma Project', 'A', 'N/A', 'N/A'),
(870, 24, 24, 'DITI 2233  Statistics and Probability', 'A', 'N/A', 'N/A'),
(871, 24, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'A', 'N/A', 'N/A'),
(872, 24, 26, 'DITS 2213 Operating System', 'A', 'N/A', 'N/A'),
(873, 24, 27, 'DITP 2313 DATABASE PROGRAMMING', 'A', 'N/A', 'N/A'),
(874, 24, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(875, 24, 29, 'DITI 1113 ARTIFICAL INTELLIGENCE', 'A', 'N/A', 'N/A'),
(876, 24, 30, 'DITP 3113  Object-oriented Programming', 'A', 'N/A', 'N/A'),
(877, 24, 31, 'DITS 2313 Data Communication and Networking', 'A', 'N/A', 'N/A'),
(879, 24, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(886, 22, 15, 'DITI1243 Linear Algebra and Numerical Methods', 'A', 'DITI1213 Discrete Mathematics', 'A'),
(887, 22, 16, 'DITP 1113 Programming 1', 'C-', 'DITP 1123  Programming 2', 'B-'),
(888, 22, 17, 'DITM 2113  Multimedia System', 'A', 'N/A', 'N/A'),
(889, 22, 18, 'DITS 1133 Computer Organisation and Architecture', 'A', 'N/A', 'N/A'),
(890, 22, 19, 'DITI 1233 Calculus', 'A', 'DITI1243 Linear Algebra and Numerical Methods', 'A'),
(891, 22, 20, 'DITP 2113  Data Structure and Algorithm', 'A', 'DITP 2113  Data Structure and Algorithm', 'A'),
(892, 22, 21, 'DITP 1333 Database', 'A', 'N/A', 'N/A'),
(893, 22, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A', 'N/A', 'N/A'),
(894, 22, 23, 'DITU 3964  Diploma Project', 'A', 'N/A', 'N/A'),
(895, 22, 24, 'DITI 2233  Statistics and Probability', 'A', 'N/A', 'N/A'),
(896, 22, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(897, 22, 26, 'DITS 2213 Operating System', 'A', 'N/A', 'N/A'),
(898, 22, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(899, 22, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(900, 22, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(901, 22, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(902, 22, 31, 'DITS 2313 Data Communication and Networking', 'A', 'N/A', 'N/A'),
(904, 22, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(911, 29, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B', 'DITI1213 DISCRETE MATHEMATICS', 'B+'),
(912, 29, 16, 'DITP 1113 PROGRAMMING 1', 'B+', 'DITP 1123  PROGRAMMING 2', 'B'),
(913, 29, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'C-', 'N/A', 'N/A'),
(914, 29, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+', 'N/A', 'N/A'),
(915, 29, 19, 'DITI 1233 CALCULUS', 'B', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'C'),
(916, 29, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A-', 'N/A', 'N/A'),
(917, 29, 21, 'DITP 1333 DATABASE', 'A-', 'N/A', 'N/A'),
(918, 29, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(919, 29, 23, 'DITU 3964  DIPLOMA PROJECT', 'B+', 'N/A', 'N/A'),
(920, 29, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B', 'N/A', 'N/A'),
(921, 29, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(922, 29, 26, 'DITS 2213 OPERATING SYSTEM', 'A-', 'N/A', 'N/A'),
(923, 29, 27, 'DITP 2313 DATABASE PROGRAMMING', 'A-', 'N/A', 'N/A'),
(924, 29, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(925, 29, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(926, 29, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(927, 29, 31, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B+', 'N/A', 'N/A'),
(928, 29, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(929, 6, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'DITI1213 DISCRETE MATHEMATICS', 'B+'),
(930, 6, 16, 'DITP 1113 PROGRAMMING 1', 'C+', 'DITP 1123  PROGRAMMING 2', 'A-'),
(931, 6, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'A', 'N/A', 'N/A'),
(932, 6, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B', 'N/A', 'N/A'),
(933, 6, 19, 'DITI 1233 CALCULUS', 'A', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A-'),
(934, 6, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A', 'N/A', 'N/A'),
(935, 6, 21, 'DITP 1333 DATABASE', 'A', 'N/A', 'N/A'),
(936, 6, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'B+', 'N/A', 'N/A'),
(937, 6, 23, 'DITU 3964  DIPLOMA PROJECT', 'A', 'N/A', 'N/A'),
(938, 6, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B+', 'N/A', 'N/A'),
(939, 6, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'B+', 'N/A', 'N/A'),
(940, 6, 26, 'DITS 2213 OPERATING SYSTEM', 'B', 'N/A', 'N/A'),
(941, 6, 27, 'DITP 2313 DATABASE PROGRAMMING', 'B+', 'N/A', 'N/A'),
(942, 6, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(943, 6, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(944, 6, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A', 'N/A', 'N/A'),
(945, 6, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B+', 'N/A', 'N/A'),
(946, 6, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(947, 40, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'N/A', 'N/A'),
(948, 40, 16, 'DITP 1113 PROGRAMMING 1', 'B', 'N/A', 'N/A'),
(949, 40, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B', 'N/A', 'N/A'),
(950, 40, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+', 'N/A', 'N/A'),
(951, 40, 19, 'DITI 1233 CALCULUS', 'A-', 'N/A', 'N/A'),
(952, 40, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B+', 'N/A', 'N/A'),
(953, 40, 21, 'DITP 1333 DATABASE', 'B', 'N/A', 'N/A'),
(954, 40, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(955, 40, 23, 'DITU 3964  DIPLOMA PROJECT', 'B+', 'N/A', 'N/A'),
(956, 40, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B', 'N/A', 'N/A'),
(957, 40, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(958, 40, 26, 'DITS 2213 OPERATING SYSTEM', 'B', 'N/A', 'N/A'),
(959, 40, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(960, 40, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(961, 40, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(962, 40, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B', 'N/A', 'N/A'),
(963, 40, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B+', 'N/A', 'N/A'),
(964, 40, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(965, 53, 15, 'N/A', 'C', 'N/A', 'N/A'),
(966, 53, 16, 'N/A', 'C-', 'N/A', 'N/A'),
(967, 53, 17, 'N/A', 'A-', 'N/A', 'N/A'),
(968, 53, 18, 'N/A', 'B+', 'N/A', 'N/A'),
(969, 53, 19, 'N/A', 'C', 'N/A', 'N/A'),
(970, 53, 20, 'N/A', 'C+', 'N/A', 'N/A'),
(971, 53, 21, 'N/A', 'B+', 'N/A', 'N/A'),
(972, 53, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(973, 53, 23, 'N/A', 'B+', 'N/A', 'N/A'),
(974, 53, 24, 'N/A', 'C+', 'N/A', 'N/A'),
(975, 53, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(976, 53, 26, 'N/A', 'B', 'N/A', 'N/A'),
(977, 53, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(978, 53, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(979, 53, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(980, 53, 30, 'N/A', 'B+', 'N/A', 'N/A'),
(981, 53, 31, 'N/A', 'A-', 'N/A', 'N/A'),
(982, 53, 33, 'N/A', 'A-', 'N/A', 'N/A'),
(983, 39, 15, 'DITI1213 DISCRETE MATHEMATICS', 'A-', 'N/A', 'N/A'),
(984, 39, 16, 'N/A', 'N/A', 'N/A', 'N/A'),
(985, 39, 17, 'N/A', 'N/A', 'N/A', 'N/A'),
(986, 39, 18, 'N/A', 'N/A', 'N/A', 'N/A'),
(987, 39, 19, 'N/A', 'N/A', 'N/A', 'N/A'),
(988, 39, 20, 'N/A', 'N/A', 'N/A', 'N/A'),
(989, 39, 21, 'N/A', 'N/A', 'N/A', 'N/A'),
(990, 39, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(991, 39, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(992, 39, 24, 'N/A', 'N/A', 'N/A', 'N/A'),
(993, 39, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(994, 39, 26, 'N/A', 'N/A', 'N/A', 'N/A'),
(995, 39, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(996, 39, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(997, 39, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(998, 39, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(999, 39, 31, 'N/A', 'N/A', 'N/A', 'N/A'),
(1000, 39, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1001, 58, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'DITI1213 DISCRETE MATHEMATICS', 'B+'),
(1002, 58, 16, 'DITP 1113 PROGRAMMING 1', 'A-', 'DITP 1123  PROGRAMMING 2', 'N/A'),
(1003, 58, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'A-', 'N/A', 'N/A'),
(1004, 58, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+', 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+'),
(1005, 58, 19, 'DITI 1233 CALCULUS', 'A-', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A-'),
(1006, 58, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B+', 'N/A', 'N/A'),
(1007, 58, 21, 'DITP 1333 DATABASE', 'B+', 'N/A', 'N/A'),
(1008, 58, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1009, 58, 23, 'DITU 3964  DIPLOMA PROJECT', 'B+', 'N/A', 'N/A'),
(1010, 58, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'A-', 'N/A', 'N/A'),
(1011, 58, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'B+', 'N/A', 'N/A'),
(1012, 58, 26, 'DITS 2213 OPERATING SYSTEM', 'B+', 'N/A', 'N/A'),
(1013, 58, 27, 'DITP 2313 DATABASE PROGRAMMING', 'B', 'N/A', 'N/A'),
(1014, 58, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1015, 58, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1016, 58, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A-', 'N/A', 'N/A'),
(1017, 58, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'A-', 'N/A', 'N/A'),
(1018, 58, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1019, 32, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A', 'DITI1213 DISCRETE MATHEMATICS', 'A-'),
(1020, 32, 16, 'DITP 1113 PROGRAMMING 1', 'B+', 'DITP 1123  PROGRAMMING 2', 'A-'),
(1021, 32, 17, 'N/A', 'N/A', 'N/A', 'N/A'),
(1022, 32, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'A', 'N/A', 'N/A'),
(1023, 32, 19, 'DITI 1233 CALCULUS', 'B', 'DITI1213 DISCRETE MATHEMATICS', 'B+'),
(1024, 32, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A', 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B-'),
(1025, 32, 21, 'DITP 1333 DATABASE', 'A', 'N/A', 'N/A'),
(1026, 32, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A-', 'N/A', 'N/A'),
(1027, 32, 23, 'DITU 3964  DIPLOMA PROJECT', 'B+', 'N/A', 'N/A'),
(1028, 32, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B+', 'N/A', 'N/A'),
(1029, 32, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'B+', 'N/A', 'N/A'),
(1030, 32, 26, 'DITS 2213 OPERATING SYSTEM', 'B+', 'N/A', 'N/A'),
(1031, 32, 27, 'DITP 1113 PROGRAMMING 1', 'A', 'DITP 1333 DATABASE', 'B+'),
(1032, 32, 28, 'DITP 1333 DATABASE', 'A-', 'DITP 2313 DATABASE PROGRAMMING', 'B'),
(1033, 32, 29, 'DITI 2233  STATISTIC AND PROBABILITY', 'B+', 'DITI1213 DISCRETE MATHEMATICS', 'A'),
(1034, 32, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B+', 'N/A', 'N/A'),
(1035, 32, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'A-', 'N/A', 'N/A'),
(1036, 32, 33, 'DITP 1333 DATABASE', 'A', 'DITP 2313 DATABASE PROGRAMMING', 'B+'),
(1037, 37, 15, 'DITI1213 DISCRETE MATHEMATICS', 'B+', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A'),
(1038, 37, 16, 'DITP 1113 PROGRAMMING 1', 'B', 'DITP 1123  PROGRAMMING 2', 'B-'),
(1039, 37, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B-', 'N/A', 'N/A'),
(1040, 37, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'C+', 'N/A', 'N/A'),
(1041, 37, 19, 'DITI 1233 CALCULUS', 'C', 'N/A', 'N/A'),
(1042, 37, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'C-', 'N/A', 'N/A'),
(1043, 37, 21, 'DITP 1333 DATABASE', 'B-', 'N/A', 'N/A'),
(1044, 37, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1045, 37, 23, 'DITU 3964  DIPLOMA PROJECT', 'C+', 'N/A', 'N/A'),
(1046, 37, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B-', 'N/A', 'N/A'),
(1047, 37, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1048, 37, 26, 'DITS 2213 OPERATING SYSTEM', 'D', 'N/A', 'N/A'),
(1049, 37, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1050, 37, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1051, 37, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1052, 37, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'C+', 'N/A', 'N/A'),
(1053, 37, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'C-', 'N/A', 'N/A'),
(1054, 37, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1055, 35, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'DITI1213 DISCRETE MATHEMATICS', 'B'),
(1056, 35, 16, 'DITP 1113 PROGRAMMING 1', 'B', 'DITP 1123  PROGRAMMING 2', 'B+'),
(1057, 35, 17, 'N/A', 'A', 'N/A', 'N/A'),
(1058, 35, 18, 'N/A', 'B', 'N/A', 'N/A'),
(1059, 35, 19, 'N/A', 'D', 'N/A', 'N/A'),
(1060, 35, 20, 'N/A', 'B+', 'N/A', 'N/A'),
(1061, 35, 21, 'N/A', 'A', 'N/A', 'N/A'),
(1062, 35, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1063, 35, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1064, 35, 24, 'N/A', 'N/A', 'N/A', 'N/A'),
(1065, 35, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1066, 35, 26, 'N/A', 'N/A', 'N/A', 'N/A'),
(1067, 35, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1068, 35, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1069, 35, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1070, 35, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(1071, 35, 31, 'N/A', 'N/A', 'N/A', 'N/A'),
(1072, 35, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1073, 52, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'C-', 'DITI1213 DISCRETE MATHEMATICS', 'C-'),
(1074, 52, 16, 'DITP 1113 PROGRAMMING 1', 'B+', 'DITP 1123  PROGRAMMING 2', 'C-'),
(1075, 52, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B+', 'DITM 2123  WEB PROGRAMMING', 'B+'),
(1076, 52, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'C+', 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'C+'),
(1077, 52, 19, 'DITI 1233 CALCULUS', 'A', 'DITI 2233  STATISTIC AND PROBABILITY', 'A-'),
(1078, 52, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A-', 'N/A', 'N/A'),
(1079, 52, 21, 'DITP 1333 DATABASE', 'A', 'N/A', 'N/A'),
(1080, 52, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A', 'N/A', 'N/A'),
(1081, 52, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1082, 52, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'A-', 'N/A', 'N/A'),
(1083, 52, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1084, 52, 26, 'DITS 2213 OPERATING SYSTEM', 'B+', 'N/A', 'N/A'),
(1085, 52, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1086, 52, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1087, 52, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1088, 52, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B', 'N/A', 'N/A'),
(1089, 52, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'C-', 'N/A', 'N/A'),
(1090, 52, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1091, 33, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B', 'DITI1213 DISCRETE MATHEMATICS', 'B+'),
(1092, 33, 16, 'DITP 1113 PROGRAMMING 1', 'A', 'DITP 1113 PROGRAMMING 1', 'A'),
(1093, 33, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B+', 'N/A', 'N/A'),
(1094, 33, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+', 'N/A', 'N/A'),
(1095, 33, 19, 'DITI 1233 CALCULUS', 'B+', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+'),
(1096, 33, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A-', 'N/A', 'N/A'),
(1097, 33, 21, 'DITP 1333 DATABASE', 'A', 'N/A', 'N/A'),
(1098, 33, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1099, 33, 23, 'DITU 3964  DIPLOMA PROJECT', 'A', 'N/A', 'N/A'),
(1100, 33, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'A-', 'N/A', 'N/A'),
(1101, 33, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1102, 33, 26, 'DITS 2213 OPERATING SYSTEM', 'A-', 'N/A', 'N/A'),
(1103, 33, 27, 'DITP 2313 DATABASE PROGRAMMING', 'B+', 'N/A', 'N/A'),
(1104, 33, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1105, 33, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1106, 33, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A-', 'N/A', 'N/A'),
(1107, 33, 31, 'N/A', 'N/A', 'N/A', 'N/A'),
(1108, 33, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1109, 60, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'C+', 'DITI 1233 CALCULUS', 'C+'),
(1110, 60, 16, 'DITP 1113 PROGRAMMING 1', 'B', 'DITP 1123  PROGRAMMING 2', 'B'),
(1111, 60, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B', 'N/A', 'N/A'),
(1112, 60, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B-', 'N/A', 'N/A'),
(1113, 60, 19, 'DITI 1233 CALCULUS', 'C+', 'N/A', 'N/A'),
(1114, 60, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B', 'N/A', 'N/A'),
(1115, 60, 21, 'DITP 1333 DATABASE', 'B-', 'N/A', 'N/A'),
(1116, 60, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1117, 60, 23, 'DITU 3964  DIPLOMA PROJECT', 'A-', 'N/A', 'N/A'),
(1118, 60, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'C+', 'N/A', 'N/A'),
(1119, 60, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1120, 60, 26, 'DITS 2213 OPERATING SYSTEM', 'B+', 'N/A', 'N/A'),
(1121, 60, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1122, 60, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1123, 60, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1124, 60, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B', 'N/A', 'N/A'),
(1125, 60, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B', 'N/A', 'N/A'),
(1126, 60, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1127, 48, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'C', 'DITI1213 DISCRETE MATHEMATICS', 'B'),
(1128, 48, 16, 'N/A', 'N/A', 'N/A', 'N/A'),
(1129, 48, 17, 'N/A', 'N/A', 'N/A', 'N/A'),
(1130, 48, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'C-', 'N/A', 'N/A'),
(1131, 48, 19, 'DITI 1233 CALCULUS', 'C', 'N/A', 'N/A'),
(1132, 48, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B+', 'N/A', 'N/A'),
(1133, 48, 21, 'DITP 1333 DATABASE', 'N/A', 'N/A', 'N/A'),
(1134, 48, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1135, 48, 23, 'DITU 3964  DIPLOMA PROJECT', 'A-', 'N/A', 'N/A'),
(1136, 48, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B', 'N/A', 'N/A'),
(1137, 48, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1138, 48, 26, 'DITS 2213 OPERATING SYSTEM', 'B-', 'N/A', 'N/A'),
(1139, 48, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1140, 48, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1141, 48, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1142, 48, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A-', 'N/A', 'N/A'),
(1143, 48, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B', 'N/A', 'N/A'),
(1144, 48, 33, 'DITP 1333 DATABASE', 'B+', 'N/A', 'N/A'),
(1145, 49, 15, 'N/A', 'N/A', 'N/A', 'N/A'),
(1146, 49, 16, 'DITP 1113 PROGRAMMING 1', 'A-', 'DITP 1123  PROGRAMMING 2', 'C+'),
(1147, 49, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B-', 'N/A', 'N/A'),
(1148, 49, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B-', 'N/A', 'N/A'),
(1149, 49, 19, 'DITI 1233 CALCULUS', 'A-', 'N/A', 'N/A'),
(1150, 49, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B', 'N/A', 'N/A'),
(1151, 49, 21, 'DITP 1333 DATABASE', 'B-', 'N/A', 'N/A'),
(1152, 49, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1153, 49, 23, 'DITU 3964  DIPLOMA PROJECT', 'A-', 'N/A', 'N/A'),
(1154, 49, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B', 'N/A', 'N/A'),
(1155, 49, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1156, 49, 26, 'DITS 2213 OPERATING SYSTEM', 'C', 'N/A', 'N/A'),
(1157, 49, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1158, 49, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1159, 49, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1160, 49, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B-', 'N/A', 'N/A'),
(1161, 49, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B-', 'N/A', 'N/A'),
(1162, 49, 33, 'N/A', 'C', 'N/A', 'N/A'),
(1163, 43, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A-', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+'),
(1164, 43, 16, 'DITP 1113 PROGRAMMING 1', 'A-', 'DITP 1113 PROGRAMMING 1', 'B+'),
(1165, 43, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'A-', 'DITM 2113  MULTIMEDIA SYSTEM', 'A-'),
(1166, 43, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+', 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B+'),
(1167, 43, 19, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'DITI 1233 CALCULUS', 'B+'),
(1168, 43, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A-', 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A-'),
(1169, 43, 21, 'DITP 1333 DATABASE', 'A-', 'DITP 1333 DATABASE', 'B+'),
(1170, 43, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'B+', 'DITP 2213 SOFTWARE ENGINEERING', 'A-'),
(1171, 43, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1172, 43, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'B+', 'DITI 2233  STATISTIC AND PROBABILITY', 'A-'),
(1173, 43, 25, 'DITM 2313 HUMAN COMPUTER INTERACTION', 'A-', 'DITM 2313 HUMAN COMPUTER INTERACTION', 'B+'),
(1174, 43, 26, 'DITS 2213 OPERATING SYSTEM', 'A-', 'DITS 2213 OPERATING SYSTEM', 'B+'),
(1175, 43, 27, 'DITP 2313 DATABASE PROGRAMMING', 'A-', 'DITP 2313 DATABASE PROGRAMMING', 'A-'),
(1176, 43, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1177, 43, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1178, 43, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A-', 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B+'),
(1179, 43, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'A-', 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B+'),
(1180, 43, 33, 'N/A', 'N/A', 'DITP 1333 DATABASE', 'B+'),
(1181, 61, 15, 'DITI1213 DISCRETE MATHEMATICS', 'B', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B'),
(1182, 61, 16, 'DITP 1113 PROGRAMMING 1', 'B+', 'DITP 1123  PROGRAMMING 2', 'B'),
(1183, 61, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'B-', 'N/A', 'N/A'),
(1184, 61, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'B', 'N/A', 'N/A'),
(1185, 61, 19, 'DITI 1233 CALCULUS', 'B+', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B-'),
(1186, 61, 20, 'N/A', 'N/A', 'N/A', 'N/A'),
(1187, 61, 21, 'N/A', 'N/A', 'N/A', 'N/A'),
(1188, 61, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1189, 61, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1190, 61, 24, 'N/A', 'N/A', 'N/A', 'N/A'),
(1191, 61, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1192, 61, 26, 'N/A', 'N/A', 'N/A', 'N/A'),
(1193, 61, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1194, 61, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1195, 61, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1196, 61, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(1197, 61, 31, 'N/A', 'N/A', 'N/A', 'N/A'),
(1198, 61, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1199, 59, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'B+', 'N/A', 'N/A'),
(1200, 59, 16, 'N/A', 'N/A', 'N/A', 'N/A'),
(1201, 59, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'A-', 'N/A', 'N/A'),
(1202, 59, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'A-', 'N/A', 'N/A'),
(1203, 59, 19, 'DITI 1233 CALCULUS', 'A-', 'N/A', 'N/A'),
(1204, 59, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'B+', 'N/A', 'N/A'),
(1205, 59, 21, 'DITP 1333 DATABASE', 'A-', 'N/A', 'N/A'),
(1206, 59, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1207, 59, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1208, 59, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'A-', 'N/A', 'N/A'),
(1209, 59, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1210, 59, 26, 'DITS 2213 OPERATING SYSTEM', 'A-', 'N/A', 'N/A'),
(1211, 59, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1212, 59, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1213, 59, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1214, 59, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'B', 'N/A', 'N/A'),
(1215, 59, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'B+', 'N/A', 'N/A'),
(1216, 59, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1217, 62, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A', 'DITI1213 DISCRETE MATHEMATICS', 'A'),
(1218, 62, 16, 'DITP 1113 PROGRAMMING 1', 'A', 'DITP 1123  PROGRAMMING 2', 'A'),
(1219, 62, 17, 'DITM 2113  MULTIMEDIA SYSTEM', 'A', 'N/A', 'N/A'),
(1220, 62, 18, 'DITS 1133 COMPUTER ORGANISATION AND ARCHITECTURE', 'A', 'N/A', 'N/A'),
(1221, 62, 19, 'DITI 1233 CALCULUS', 'D', 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A'),
(1222, 62, 20, 'DITP 2113  DATA STRUCTURE AND ALGORITHM', 'A', 'N/A', 'N/A'),
(1223, 62, 21, 'DITP 1333 DATABASE', 'A', 'N/A', 'N/A'),
(1224, 62, 22, 'DITP 2213 SOFTWARE ENGINEERING', 'A', 'N/A', 'N/A'),
(1225, 62, 23, 'DITU 3964  DIPLOMA PROJECT', 'A', 'N/A', 'N/A'),
(1226, 62, 24, 'DITI 2233  STATISTIC AND PROBABILITY', 'A', 'N/A', 'N/A'),
(1227, 62, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1228, 62, 26, 'DITS 2213 OPERATING SYSTEM', 'A', 'N/A', 'N/A'),
(1229, 62, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1230, 62, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1231, 62, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1232, 62, 30, 'DITP 3113  OBJECT-ORIENTED PROGRAMMING', 'A', 'N/A', 'N/A'),
(1233, 62, 31, 'DITS 2313 DATA COMMUNICATION AND NETWORKING', 'A', 'N/A', 'N/A'),
(1234, 62, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(1235, 31, 15, 'DITI 1243 LINEAR ALGEBRA AND NUMERICAL METHODS', 'A', 'N/A', 'N/A'),
(1236, 31, 16, 'N/A', 'N/A', 'N/A', 'N/A'),
(1237, 31, 17, 'N/A', 'N/A', 'N/A', 'N/A'),
(1238, 31, 18, 'N/A', 'N/A', 'N/A', 'N/A'),
(1239, 31, 19, 'N/A', 'N/A', 'N/A', 'N/A'),
(1240, 31, 20, 'N/A', 'N/A', 'N/A', 'N/A'),
(1241, 31, 21, 'N/A', 'N/A', 'N/A', 'N/A'),
(1242, 31, 22, 'N/A', 'N/A', 'N/A', 'N/A'),
(1243, 31, 23, 'N/A', 'N/A', 'N/A', 'N/A'),
(1244, 31, 24, 'N/A', 'N/A', 'N/A', 'N/A'),
(1245, 31, 25, 'N/A', 'N/A', 'N/A', 'N/A'),
(1246, 31, 26, 'N/A', 'N/A', 'N/A', 'N/A'),
(1247, 31, 27, 'N/A', 'N/A', 'N/A', 'N/A'),
(1248, 31, 28, 'N/A', 'N/A', 'N/A', 'N/A'),
(1249, 31, 29, 'N/A', 'N/A', 'N/A', 'N/A'),
(1250, 31, 30, 'N/A', 'N/A', 'N/A', 'N/A'),
(1251, 31, 31, 'N/A', 'N/A', 'N/A', 'N/A'),
(1252, 31, 33, 'N/A', 'N/A', 'N/A', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `institution`
--

CREATE TABLE `institution` (
  `int_id` int(11) NOT NULL,
  `int_name` varchar(200) NOT NULL,
  `branch` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `institution`
--

INSERT INTO `institution` (`int_id`, `int_name`, `branch`) VALUES
(1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'KAMPUS INDUK'),
(2, 'UNIVERSITI TUN HUSEIN ONN MALAYSIA', 'BATU PAHAT'),
(3, 'UNIVERSITI TEKNOLOGI MALAYSIA', 'UTM'),
(4, 'UNIVERSITI MALAYSIA PAHANG AL-SULTAN ABDULLAH', 'PEKAN'),
(7, 'UNIVERSITI MALAYA', ''),
(8, 'UNIVERSITI SAINS MALAYSIA', ''),
(9, 'UNIVERSITI KEBANGSAAN MALAYSIA', ''),
(10, 'UNIVERSITI PUTRA MALAYSIA ', ''),
(11, 'UNIVERSITI ISLAM ANTARABANGSA MALAYSIA', ''),
(12, 'UNIVERSITI UTARA MALAYSIA ', ''),
(13, 'UNIVERSITI MALAYSIA SARAWAK', ''),
(14, 'UNIVERSITI MALAYSIA SABAH', ''),
(15, 'UNIVERSITI PENDIDIKAN SULTAN IDRIS', ''),
(16, 'UNIVERSITI SAINS ISLAM MALAYSIA', ''),
(17, 'UNIVERSITI TEKNOLOGI MARA', ''),
(18, 'UNIVERSITI MALAYSIA TERENGGANU', ''),
(19, 'UNIVERSITI MALAYSIA PERLIS ', ''),
(20, 'UNIVERSITI SULTAN ZAINAL ABIDIN', ''),
(21, 'UNIVERSITI MALAYSIA KELANTAN', ''),
(22, 'UNIVERSITI PERTAHANAN NASIONAL MALAYSIA', ''),
(23, 'POLITEKNIK UNGKU OMAR', 'IPOH PERAK'),
(24, 'POLITEKNIK SULTAN HAJI AHMAD SHAH ', 'KUANTAN PAHANG'),
(25, 'POLITEKNIK SULTAN ABDUL HALIM MUADZAM SHAH', 'JITRA KEDAH'),
(26, 'POLITEKNIK KOTA BHARU', 'KETEREH KELANTAN'),
(27, 'POLITEKNIK KUCHING SARAWAK ', 'KUCHING SARAWAK'),
(28, 'POLITEKNIK PORT DICKSON', 'SI RUSA NEGERI SEMBILAN'),
(29, 'POLITEKNIK KOTA KINABALU', 'KOTA KINABALU SABAH'),
(30, 'POLITEKNIK SULTAN SALAHUDDIN ABDUL AZIZ SHAH', 'SHAH ALAM SELANGOR'),
(31, 'POLITEKNIK IBRAHIM SULTAN', 'PASIR GUDANG JOHOR'),
(32, 'POLITEKNIK SEBERANG PERA', 'PERMATANG PAUH PULAU PINANG'),
(33, 'POLITEKNIK MELAKA', 'BALAI PANJANG MELAKA'),
(34, 'POLITEKNIK KUALA TERENGGANU ', 'KUALA TERENGGANU TERENGGANU'),
(35, 'POLITEKNIK SULTAN MIZAN ZAINAL ABIDIN', 'DUNGUN TERENGGANU'),
(36, 'POLITEKNIK MERLIMAU', 'MERLIMAU MELAKA'),
(37, 'POLITEKNIK SULTAN AZLAN SHAH ', 'BEHRANG PERAK'),
(38, 'POLITEKNIK TUANKU SULTANAH BAHIYAH', 'KULIM KEDAH'),
(39, 'POLITEKNIK SULTAN IDRIS SHAH', 'SUNGAI AIR TAWAR SELANGOR'),
(40, 'POLITEKNIK TUANKU SYED SIRAJUDDIN', 'ARAU PERLIS'),
(41, 'POLITEKNIK MUADZAM SHAH', 'MUADZAM SHAH PAHANG'),
(42, 'POLITEKNIK MUKAH SARAWAK', 'MUKAH SARAWAK'),
(43, 'POLITEKNIK BALIK PULAU', 'BALIK PULAU PULAU PINANG'),
(44, 'POLITEKNIK JELI', 'JELI KELANTAN'),
(45, 'POLITEKNIK NILAI', 'BANDAR ENSTEK NEGERI SEMBILAN'),
(46, 'POLITEKNIK BANTING', 'BANTING SELANGOR'),
(47, 'POLITEKNIK MERSING', 'MERSING JOHOR'),
(48, 'POLITEKNIK HULU TERENGGANU', 'KUALA BERANG TERENGGANU'),
(49, 'POLITEKNIK SANDAKAN', 'SANDAKAN SABAH'),
(50, 'POLITEKNIK METRO KUALA LUMPUR ', 'KUALA LUMPUR'),
(51, 'POLITEKNIK METRO KUANTAN', 'KUANTAN PAHANG'),
(52, 'POLITEKNIK METRO JOHOR BAHRU', 'JOHOR BAHRU JOHOR'),
(53, 'POLITEKNIK METRO BETONG', 'BENTONG SARAWAK'),
(54, 'POLITEKNIK METRO TASEK GELUGOR', 'TASEK GELUGOR PULAU PINANG'),
(55, 'POLITEKNIK TUN SYED NASIR SYED ISMAIL', 'PAGOH JOHOR'),
(56, 'POLITEKNIK BESUT', 'BESUT TERENGGANU'),
(57, 'POLITEKNIK BAGAN DATUK', 'HUTAN MELINTANG PERAK'),
(58, 'POLITEKNIK TAWAU ', 'TAWAU SABAH');

-- --------------------------------------------------------

--
-- Table structure for table `lecturer`
--

CREATE TABLE `lecturer` (
  `lect_id` int(11) NOT NULL,
  `lect_name` varchar(100) NOT NULL,
  `phoneno` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturer`
--

INSERT INTO `lecturer` (`lect_id`, `lect_name`, `phoneno`, `email`, `username`, `password`, `role`) VALUES
(1, 'DR ZAHRIAH', '062702460', 'zahriah@utem.edu.my', '12345', 'af33c635ffa1a0d7708f3db70b30496c', 'Academic Advisor'),
(2, 'NURFAKHIRAH', '019182736494', 'fakhirah@utem.edu.my', '246810', '11871f78dc9f4236a6896a0a74f1760c', 'Academic Advisor'),
(3, 'TS DR AHMAD SHAARIZAN', '6062704527', 'shaarizan@utem.edu.my', '36912', 'af33c635ffa1a0d7708f3db70b30496c', 'Academic Deputy Dean'),
(4, 'PM DR MOHD SANUSI', '6062702472', 'sanusi@utem.edu.my', '48121', 'af33c635ffa1a0d7708f3db70b30496c', 'Dean'),
(5, 'DR NAIM', '06563365', 'naim@utem.edu.my', '51015', 'af33c635ffa1a0d7708f3db70b30496c', 'Academic Advisor'),
(6, 'DR ANIS', '06544322', 'anis@utem.edu.my', '67891', 'af33c635ffa1a0d7708f3db70b30496c', 'Academic Advisor'),
(9, 'DR ALI BIN ABU', '0146748895', 'ali@utem.edu.my', '71421', 'af33c635ffa1a0d7708f3db70b30496c', 'Academic Advisor');

--
-- Triggers `lecturer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_lecturer` BEFORE INSERT ON `lecturer` FOR EACH ROW BEGIN
    DECLARE username_count INT;

    -- Check if username already exists
    SELECT COUNT(*) INTO username_count FROM lecturer WHERE username = NEW.username;

    IF username_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Staff ID already exists';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `new_tp`
--

CREATE TABLE `new_tp` (
  `tp_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(100) NOT NULL,
  `credit_hour` int(11) NOT NULL,
  `tplink` varchar(100) NOT NULL,
  `status` varchar(50) NOT NULL,
  `int_id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `review_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `new_tp`
--

INSERT INTO `new_tp` (`tp_id`, `code`, `title`, `credit_hour`, `tplink`, `status`, `int_id`, `stud_id`, `date`, `review_date`) VALUES
(2, 'Bitp 2324', 'Mathematics', 3, 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', '', 1, 4, '2024-08-16 16:00:00', '2024-08-18'),
(3, 'BKMW1234', 'COMPUTER ARCHITECTURE', 3, 'GROUP18_ASSIGNMENT1_RESUBMIT.pdf', '', 2, 9, '2024-08-16 16:00:00', '2024-08-18'),
(4, 'BBMW1245', 'DATABASE MANAGEMENT', 3, 'PD Drarft Proposal by D032010140.pdf', '', 2, 9, '2024-08-17 16:00:00', '2024-08-18'),
(5, 'DBAC5643', 'SYSTEM SECURITY', 3, 'CASE STUDY.pdf', '', 1, 4, '2024-08-18 16:00:00', '2024-08-19'),
(6, 'DITP 3323', 'DATABASE ADMINISTRATION', 3, 'Teaching Plan DITP 3323 Databse Admininstration.pdf', 'Pending', 1, 4, '2024-08-22 03:14:43', NULL),
(7, 'DITI1141', 'ARTIFICIAL INTELLIGENCE', 3, 'Bank Islam IB.pdf', 'Pending', 1, 6, '2024-08-22 04:28:00', NULL),
(8, 'BIT123', 'TEST', 2, 'Week 3 4 5- Dynamic Techniques slide only v2.pdf', 'Pending', 1, 32, '2024-08-23 16:26:50', NULL),
(9, 'BIT123', 'TEST', 2, 'Week 3 4 5- Dynamic Techniques slide only v2.pdf', 'Pending', 1, 32, '2024-08-23 16:27:33', NULL),
(10, 'DITI0000', 'TESTING', 3, 'FTMK CTS USER MANUAL - DE.pdf', 'Pending', 1, 48, '2024-08-24 16:19:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `stud_id`, `message`, `status`) VALUES
(5, 22, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(7, 21, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(8, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(9, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(10, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(11, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(12, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(13, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(14, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(15, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(16, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(17, 38, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(18, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(19, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(20, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(21, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(22, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(23, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(24, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(25, 62, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(26, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(27, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(28, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read'),
(29, 54, 'You have been poked by your advisor. Please do credit transfer now!', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `programme`
--

CREATE TABLE `programme` (
  `prog_id` int(11) NOT NULL,
  `prog_name` varchar(100) NOT NULL,
  `prog_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `programme`
--

INSERT INTO `programme` (`prog_id`, `prog_name`, `prog_code`) VALUES
(1, 'DIPLOMA IN INFORMATION TECHNOLOGY', 'DIT'),
(2, 'DIPLOMA COMPUTER SCIENCE', 'DCS'),
(3, 'BACHELOR OF COMPUTER SCIENCE (SOFTWARE DEVELOPMENT) WITH HONOURS', 'BITS'),
(4, 'BACHELOR OF COMPUTER SCIENCE (INTERACTIVE MEDIA) WITH HONOURS', 'BITM'),
(5, 'BACHELOR OF COMPUTER SCIENCE (DATABASE MANAGEMENT) WITH HONOURS', 'BITD'),
(6, 'BACHELOR OF COMPUTER SCIENCE (COMPUTER SECURITY) WITH HONOURS', 'BITZ'),
(7, 'BACHELOR OF COMPUTER SCIENCE (ARTIFICAL INTELLIGENCE) WITH HONOURS', 'BITI'),
(8, 'BACHELOR OF INFORMATION TECHNOLOGY (GAME TECHNOLOGY) WITH HONOURS', 'BITE');

-- --------------------------------------------------------

--
-- Table structure for table `reject_transfer`
--

CREATE TABLE `reject_transfer` (
  `reject_id` int(11) NOT NULL,
  `stud_id` int(20) DEFAULT NULL,
  `course_code` varchar(20) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `credit_hour` int(11) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `similar` varchar(255) DEFAULT NULL,
  `similar2` varchar(255) DEFAULT NULL,
  `grade2` varchar(10) DEFAULT NULL,
  `aa_comment` text,
  `rejection_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reject_transfer`
--

INSERT INTO `reject_transfer` (`reject_id`, `stud_id`, `course_code`, `title`, `credit_hour`, `grade`, `similar`, `similar2`, `grade2`, `aa_comment`, `rejection_date`) VALUES
(1, 30, 'BITP 2213', 'SOFTWARE ENGINEERING', 3, 'A', 'DITP 2213 SOFTWARE ENGINEERING', 'N/A', 'N/A', 'Cannot transfer, the curriculum change', '2024-08-27 12:57:56'),
(10, 30, 'BITI 2233', 'STATISTICS AND PROBABILITY', 3, 'A', 'DITI 2233  STATISTIC AND PROBABILITY', 'N/A', 'N/A', 'changing of syllabus', '2024-08-27 13:20:43');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `req_id` int(11) NOT NULL,
  `course` varchar(50) NOT NULL,
  `message` varchar(100) NOT NULL,
  `link` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`req_id`, `course`, `message`, `link`, `status`, `stud_id`, `request_date`) VALUES
(19, '4', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-08-18 16:00:10'),
(20, '6', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 4, '2024-08-18 15:58:44'),
(21, '10', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 4, '2024-08-18 16:00:20'),
(23, '3', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-08-18 16:02:12'),
(24, '10', 'Update TP to the latest version, Update course code, Update course name, Wrong Teaching Plan, Other:', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 10, '2024-08-25 13:26:40'),
(26, '42', 'Update TP to the latest version', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=sharing', 'Accepted', 12, '2024-06-18 14:19:00'),
(29, '6', 'Update TP to the latest version, Update course code, Update course name, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 21, '2024-08-18 16:02:04'),
(30, '10', 'Update course code', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-06-24 03:40:44'),
(31, '52', 'Update course code, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=sharing', 'Pending', 4, '2024-08-18 16:23:08'),
(32, '48', 'Wrong Teaching Plan', 'https://drive.google.com/file/d/1FPB64oqQxEmo7j39KnB04W8EeCTDFwU7/view?usp=drive_link', 'Pending', 4, '2024-08-22 03:24:29'),
(33, '12', 'Other:', 'https://drive.google.com/file/d/1FPB64oqQxEmo7j39KnB04W8EeCTDFwU7/view?usp=drive_link', 'Pending', 37, '2024-08-23 20:35:17'),
(34, '6', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/drive/folders/1HKzYY0i10Z2OBjjxvX9tki6JS2U89MhZ', 'Pending', 48, '2024-08-24 16:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `stud_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `icno` varchar(20) NOT NULL,
  `faculty` varchar(200) NOT NULL,
  `prog_id` int(11) NOT NULL,
  `session` varchar(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `int_id` int(11) NOT NULL,
  `latest_int` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `lect_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stud_id`, `name`, `icno`, `faculty`, `prog_id`, `session`, `email`, `phone`, `int_id`, `latest_int`, `username`, `password`, `lect_id`, `admin_id`) VALUES
(4, 'HIDAYAH BINTI BURHANNUDIN', '0210060301116', 'FTMK', 5, '23/24', 'B032220009@student.utem.edu.my', '01110664992', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220009', '07efe027f3de377b221c62a8ab29cb55', 1, 1),
(6, 'NUR AQILAH HUMAIRA BINTI IMRAN', '021017010002', 'FTMK', 5, '23/24', 'B032220016@student.utem.edu.my', '0127216104', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220016', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(9, 'SITI NAJWA', '0202180000000', 'FTMK', 7, '23/24', 'B032220025@student.utem.edu.my', '0125677858', 2, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220025', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(10, 'NABILA SOFEA', '021006030000', 'FTMK', 6, '23/24', 'B032220000@student.utem.edu.my', '012345678', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220000', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(11, 'BATRISYA', '020818030978', 'FTMK', 7, '23/24', 'B03220001@student.utem.edu.my', '012345656', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220001', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(12, 'NABIL', '020982753467', 'FTMK', 4, '23/24', 'B032220081@student.utem.edu.my', '01234567', 3, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220081', 'e9713c995b07e49d34cc60bab9011e97', 5, 1),
(13, 'NOR AZWANI', '010203045678', 'FTMK', 3, '23/24', 'B032220099@student.utem.edu.my', '012345679', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220099', '7f74ced51e7a81bc50898c6415c41d5a', 2, 1),
(14, 'HUMAIRAH QAIREENA', '040831030000', 'FTMK', 8, '23/24', 'B032310004@student.utem.edu.my', '01678944789', 4, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310004', 'e9713c995b07e49d34cc60bab9011e97', 6, 1),
(16, 'KIM MINGYU', '0234356547659', 'FTMK', 5, '23/24', 'B032210998@student.utem.edu.my', '0134343543', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032210998', 'e9713c995b07e49d34cc60bab9011e97', 6, 1),
(21, 'NURUL FARZANA BINTI AHMAD MUSLIM', '020506020000', 'FTMK', 5, '23/24', 'B032310043@student.utem.edu.my', '0177993245', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310043', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(22, 'NURUL AIN', '020202020000', 'FTMK', 3, '23/24', 'B032220088@student.utem.edu.my', '0198765432', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220088', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(24, 'BELLA', '0304950384564', 'FTMK', 7, '23/24', 'B0322200077@student.utem.edu.my', '021324356', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B0322200077', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(26, 'NURIN NISHA', '010304050678', 'FTMK', 5, '23/24', 'B032104563@student.utem.edu.my', '01234567891', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032104563', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(27, 'NURIN', '0201030405066', 'FTMK', 5, '23/24', 'B032110100@student.utem.edu.my', '01112345778', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032110100', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(28, 'ADAM RAYYAN', '020121034567', 'FTMK', 8, '23/24', 'B032310127@student.utem.edu.my', '012345788768', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310127', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(29, 'NUR SALIHAH HUSNA BINTI ABDUL RAHIM', '021111070000', 'FTMK', 5, '23/24', 'B032220018@student.utem.edu.my', '0172149959', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220018', '69c4ae586639afb9f3a591c73f141892', 1, 1),
(30, 'NURNADHIRAH NATASYA BINTI AHMAD DAUD', '020101000000', 'FTMK', 3, '23/24', 'B032220021@student.utem.edu.my', '0192458390', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220021', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(31, 'NORSHAFIQAH BINTI NORHISHAM', '020102000000', 'FTMK', 5, '23/24', 'B032310786@student.utem.edu.my', '0194500911', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310786', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(32, 'ERINA SONIA', '020103000000', 'FTMK', 3, '23/24', 'B032310005@student.utem.edu.my', '012345779', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310005', '52d4d577066670caf2a663765044c70f', 2, 1),
(33, 'NURUL ANIS BINTI MAHADI', '020104000000', 'FTMK', 5, '23/24', 'B032310042@student.utem.edu.my', '012345686', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310042', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(34, 'NUR ATHIRAH AMANI', '020106000000', 'FTMK', 5, '23/24', 'B032310036@student.utem.edu.my', '012345678', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310036', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(35, 'NUR BALQIS MUNIRAH', '020107000000', 'FTMK', 6, '23/24', 'B032310037@student.utem.edu.my', '012346688', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310037', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(36, 'NURUL NATASHA', '020108000000', 'FTMK', 4, '23/24', 'B032310044@student.utem.edu.my', '0124578998', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310044', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(37, 'NUR ALEEYA AMIRAH', '020201000000', 'FTMK', 5, '23/24', 'B032310035@student.utem.edu.my', '021324254', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310035', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(38, 'FITRAH ATHIRA', '020202000000', 'FTMK', 6, '23/24', 'B032310008@student.utem.edu.my', '012334678', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310008', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(39, 'BAHII ADDAWIYAH', '020203000000', 'FTMK', 6, '23/24', 'B032310245@student.utem.edu.my', '0132423534', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310245', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(40, 'SITI HAWA', '020204000000', 'FTMK', 6, '23/24', 'B032310268@student.utem.edu.my', '01345780', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310268', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(41, 'NOR SYAHIRA', '020205000000', 'FTMK', 6, '23/24', 'B032310290@student.utem.edu.my', '0122567688', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310290', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(43, 'FAIQ IJLAL', '020207000000', 'FTMK', 6, '23/24', 'B032310223@student.utem.edu.my', '012243546', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310223', '62593a37b7cd8c8b56a0405c9ab61f98', 2, 1),
(45, 'MUHAMMAD SYAFIQ', '0202008000000', 'FTMK', 6, '23/24', 'B032310030@student.utem.edu.my', '012345657', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310030', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(46, 'YUSZREY DANISH', '020209000000', 'FTMK', 6, '23/24', 'B032310053@student.utem.edu.my', '012233546', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310053', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(47, 'MUHAMMAD KAMARULREZZA', '020301000000', 'FTMK', 6, '23/24', 'B032310189@student.utem.edu.my', '01232458', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310189', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(48, 'QISTINA WONG SAU HANN', '0203020000000', 'FTMK', 6, '23/24', 'B032310046@student.utem.edu.my', '012346788', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310046', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(49, 'NUR HARYANI', '020304000000', 'FTMK', 5, '23/24', 'B032310038@student.utem.edu.my', '012357890', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310038', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(50, 'SITI NOR HARLINA', '020306000000', 'FTMK', 5, '23/24', 'B032310048@student.utem.edu.my', '0123346578', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310048', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(51, 'NUR AIN NABILAH', '020307000000', 'FTMK', 5, '23/24', 'B032310033@student.utem.edu.my', '0121324356', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310033', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(52, 'BALQIS BINTI ABDUL AZIZ', '030101000000', 'FTMK', 5, '23/24', 'B032320021@student.utem.edu.my', '012345678', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320021', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(53, 'SITI NURSYAHIRAH BINTIÂ YUSRI', '030102000000', 'FTMK', 4, '23/24', 'B032320113@student.utem.edu.my', '012356889', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320113', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(54, 'AKMAL HARITH BIN IBRAHIM', '030103000000', 'FTMK', 5, '23/24', 'B032320017@student.utem.edu.my', '012345676', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320017', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(55, 'HUMAIRA NAZRI', '030104000000', 'FTMK', 3, '23/24', 'B032320077@student.utem.edu.my', '012356789', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320077', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(56, 'NUR AIDA AFIZAH BINTI ZAINONDIN', '030106000000', 'FTMK', 4, '23/24', 'B032320071@student.utem.edu.my', '012356889', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320071', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(57, 'NURUL SYAHZANANIÂ ZAINI', '030107000000', 'FTMK', 4, '23/24', 'B032320112@student.utem.edu.my', '012356789', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032320112', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(58, 'NURFAKHIRAH BINTI ABDUL RAHMAN', '020209060000', 'FTMK', 4, '23/24', 'B032220020@student.utem.edu.my', '01169955848', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220020', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(59, 'NUR SYAMEERA BINTI SULAIMAN', '020501000000', 'FTMK', 8, '23/24', 'B032220019@student.utem.edu.my', '0121231231', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220019', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(60, 'FARHA AZUWANA BINTI ADNAN', '020502000000', 'FTMK', 4, '23/24', 'B032310006@student.utem.edu.my', '01234567891', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310006', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(61, 'HUSNA ARIFAH BINTI YUZAIMI', '020130030000', 'FTMK', 4, '23/24', 'B032220010@student.utem.edu.my', '01234567892', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220010', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
(62, 'AJIT KUMAR', '020127010000', 'FTMK', 6, '23/24', 'B032220034@student.utem.edu.my', '01234567893', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220034', 'e9713c995b07e49d34cc60bab9011e97', 2, 1);

--
-- Triggers `student`
--
DELIMITER $$
CREATE TRIGGER `before_insert_student` BEFORE INSERT ON `student` FOR EACH ROW BEGIN
    DECLARE username_count INT;
    DECLARE icno_count INT;

    -- Check if username already exists
    SELECT COUNT(*) INTO username_count FROM student WHERE username = NEW.username;

    IF username_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Matric No already exists';
    END IF;

    -- Check if icno already exists
    SELECT COUNT(*) INTO icno_count FROM student WHERE icno = NEW.icno;

    IF icno_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IC Number already exists';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `students_in_progress_view`
-- (See below for the actual view)
--
CREATE TABLE `students_in_progress_view` (
`stud_id` int(11)
,`name` varchar(100)
,`username` varchar(50)
,`prog_code` varchar(50)
,`lect_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_courses`
-- (See below for the actual view)
--
CREATE TABLE `student_courses` (
`course_code` varchar(50)
,`title` varchar(100)
,`credit_hour` int(11)
,`stud_id` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_details_view`
-- (See below for the actual view)
--
CREATE TABLE `student_details_view` (
`stud_id` int(11)
,`name` varchar(100)
,`icno` varchar(20)
,`faculty` varchar(200)
,`prog_code` varchar(50)
,`session` varchar(11)
,`email` varchar(50)
,`phone` varchar(20)
,`int_name` varchar(200)
,`username` varchar(50)
,`lect_name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_finish_dean_view`
-- (See below for the actual view)
--
CREATE TABLE `student_finish_dean_view` (
`stud_id` int(11)
,`name` varchar(100)
,`username` varchar(50)
,`transfer_id` int(11)
,`aa_status` varchar(10)
,`tda_status` varchar(10)
,`dean_status` varchar(10)
,`int_id` int(11)
,`int_name` varchar(200)
,`prog_id` int(11)
,`prog_code` varchar(50)
,`lect_id` int(11)
,`lect_name` varchar(100)
,`transfer_date` timestamp
,`total` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_pending_dean_view`
-- (See below for the actual view)
--
CREATE TABLE `student_pending_dean_view` (
`stud_id` int(11)
,`name` varchar(100)
,`username` varchar(50)
,`transfer_id` int(11)
,`aa_status` varchar(10)
,`tda_status` varchar(10)
,`dean_status` varchar(10)
,`transfer_date` timestamp
,`total` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_total_credits`
-- (See below for the actual view)
--
CREATE TABLE `student_total_credits` (
`stud_id` int(11)
,`totalCredit` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `student_transfer_dean_view`
-- (See below for the actual view)
--
CREATE TABLE `student_transfer_dean_view` (
`stud_id` int(11)
,`name` varchar(100)
,`username` varchar(50)
,`transfer_id` int(11)
,`aa_status` varchar(10)
,`tda_status` varchar(10)
,`dean_status` varchar(10)
,`transfer_date` timestamp
,`total` decimal(32,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `transfer`
--

CREATE TABLE `transfer` (
  `transfer_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `transfer_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `aa_status` varchar(10) NOT NULL,
  `aa_comment` varchar(50) NOT NULL,
  `tda_status` varchar(10) NOT NULL,
  `tda_comment` varchar(50) NOT NULL,
  `dean_status` varchar(10) NOT NULL,
  `dean_comment` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transfer`
--

INSERT INTO `transfer` (`transfer_id`, `grade_id`, `transfer_date`, `aa_status`, `aa_comment`, `tda_status`, `tda_comment`, `dean_status`, `dean_comment`) VALUES
(301, 582, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(302, 583, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(303, 584, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(304, 585, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(305, 586, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(306, 587, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(307, 588, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(308, 590, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(309, 591, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(310, 598, '2024-06-21 00:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(311, 607, '2024-06-11 07:15:30', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(312, 608, '2024-06-11 07:15:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(313, 609, '2024-06-11 07:15:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(314, 610, '2024-06-11 07:15:36', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(315, 611, '2024-06-11 07:15:39', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(316, 612, '2024-06-11 07:15:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(317, 613, '2024-06-11 07:15:45', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(318, 615, '2024-06-11 07:15:47', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(319, 616, '2024-06-11 07:15:50', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(320, 618, '2024-06-11 07:15:52', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(321, 623, '2024-06-11 07:15:54', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(322, 632, '2024-06-11 19:23:03', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(323, 633, '2024-06-11 19:23:06', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(324, 634, '2024-06-11 19:23:08', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(325, 635, '2024-06-11 19:23:09', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(326, 636, '2024-06-11 19:23:12', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(327, 637, '2024-06-11 19:23:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(329, 640, '2024-06-11 19:23:16', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(330, 641, '2024-06-21 00:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(331, 643, '2024-06-21 00:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(332, 647, '2024-06-21 00:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(346, 686, '2024-06-21 00:52:06', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(359, 657, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(360, 658, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(361, 659, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(362, 660, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(363, 661, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(364, 662, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(365, 663, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(366, 665, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(367, 666, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(368, 668, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(369, 672, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(370, 673, '2024-06-22 23:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(382, 764, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(383, 765, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(384, 766, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(385, 767, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(386, 770, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(387, 776, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(388, 777, '2024-06-25 07:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(389, 786, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(390, 787, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(391, 788, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(392, 789, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(393, 790, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(394, 791, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(395, 792, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(396, 794, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(397, 795, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(398, 796, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(399, 797, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(400, 801, '2024-06-23 22:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(401, 811, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(402, 812, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(403, 813, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(404, 814, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(405, 815, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(406, 816, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(407, 817, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(408, 820, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(409, 821, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(410, 822, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(411, 823, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(412, 826, '2024-08-21 18:27:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(413, 861, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(414, 864, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(415, 866, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(416, 868, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(417, 869, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(418, 870, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(419, 871, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(420, 872, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(421, 873, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(422, 875, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(423, 876, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(424, 877, '2024-08-18 09:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(425, 886, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(426, 888, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(427, 889, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(428, 890, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(429, 891, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(430, 892, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(431, 893, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(432, 894, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(433, 895, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(434, 897, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(435, 902, '2024-08-21 05:49:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(436, 911, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(437, 912, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(438, 914, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(439, 915, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(440, 916, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(441, 917, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(442, 919, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(443, 920, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(444, 922, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(445, 923, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(446, 927, '2024-08-21 22:07:05', 'Pending', '', 'Pending', '', 'Pending', ''),
(447, 929, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(448, 932, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(449, 933, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(450, 934, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(451, 936, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(452, 937, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(453, 938, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(454, 939, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(455, 940, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(456, 941, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(457, 944, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(458, 945, '2024-08-21 22:30:30', 'Pending', '', 'Pending', '', 'Pending', ''),
(459, 947, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(460, 948, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(461, 949, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(462, 950, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(463, 951, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(464, 952, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(465, 953, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(466, 955, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(467, 956, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(468, 958, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(469, 962, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(470, 963, '2024-08-23 09:24:44', 'Pending', '', 'Pending', '', 'Pending', ''),
(471, 965, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(472, 967, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(473, 968, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(474, 969, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(475, 971, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(476, 976, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(477, 980, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(478, 981, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(479, 982, '2024-08-23 09:29:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(480, 983, '2024-08-23 09:32:22', 'Pending', '', 'Pending', '', 'Pending', ''),
(481, 1001, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(482, 1002, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(483, 1003, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(484, 1004, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(485, 1005, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(486, 1006, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(487, 1009, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(488, 1010, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(489, 1011, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(490, 1012, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(491, 1016, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(492, 1017, '2024-08-23 09:39:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(493, 1019, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(494, 1020, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(495, 1022, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(496, 1023, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(497, 1024, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(498, 1025, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(499, 1026, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(500, 1027, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(501, 1028, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(502, 1029, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(503, 1030, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(504, 1031, '2024-08-23 10:22:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(505, 1037, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(506, 1038, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(507, 1039, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(508, 1041, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(509, 1043, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(510, 1046, '2024-08-23 14:27:18', 'Pending', '', 'Pending', '', 'Pending', ''),
(511, 1055, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(512, 1056, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(513, 1057, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(514, 1058, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(515, 1060, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(516, 1061, '2024-08-23 15:16:36', 'Pending', '', 'Pending', '', 'Pending', ''),
(517, 1075, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(518, 1077, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(519, 1078, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(520, 1079, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(521, 1080, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(522, 1082, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(523, 1084, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(524, 1088, '2024-08-24 06:18:25', 'Pending', '', 'Pending', '', 'Pending', ''),
(525, 1091, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(526, 1092, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(527, 1093, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(528, 1094, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(529, 1095, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(530, 1096, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(531, 1097, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(532, 1099, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(533, 1100, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(534, 1102, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(535, 1103, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(536, 1106, '2024-08-24 07:43:03', 'Pending', '', 'Pending', '', 'Pending', ''),
(537, 1110, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(538, 1111, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(539, 1112, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(540, 1114, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(541, 1115, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(542, 1117, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(543, 1120, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(544, 1124, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(545, 1125, '2024-08-24 08:56:54', 'Pending', '', 'Pending', '', 'Pending', ''),
(546, 1127, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(547, 1131, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(548, 1132, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(549, 1135, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(550, 1136, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(551, 1138, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(552, 1142, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(553, 1143, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(554, 1144, '2024-08-24 10:01:31', 'Pending', '', 'Pending', '', 'Pending', ''),
(555, 1147, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(556, 1148, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(557, 1149, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(558, 1150, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(559, 1151, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(560, 1153, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(561, 1154, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(562, 1156, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(563, 1160, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(564, 1161, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(565, 1162, '2024-08-24 19:10:55', 'Pending', '', 'Pending', '', 'Pending', ''),
(566, 1163, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(567, 1164, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(568, 1165, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(569, 1166, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(570, 1167, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(571, 1168, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(572, 1169, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(573, 1170, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(574, 1172, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(575, 1174, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(576, 1175, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(577, 1178, '2024-08-24 20:27:46', 'Pending', '', 'Pending', '', 'Pending', ''),
(578, 1173, '2024-08-24 20:29:04', 'Pending', '', 'Pending', '', 'Pending', ''),
(579, 1181, '2024-08-25 07:18:26', 'Pending', '', 'Pending', '', 'Pending', ''),
(580, 1182, '2024-08-25 07:18:26', 'Pending', '', 'Pending', '', 'Pending', ''),
(581, 1183, '2024-08-25 07:18:26', 'Pending', '', 'Pending', '', 'Pending', ''),
(582, 1184, '2024-08-25 07:18:26', 'Pending', '', 'Pending', '', 'Pending', ''),
(583, 1185, '2024-08-25 07:18:26', 'Pending', '', 'Pending', '', 'Pending', ''),
(584, 1199, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(585, 1201, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(586, 1202, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(587, 1203, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(588, 1204, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(589, 1205, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(590, 1208, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(591, 1210, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(592, 1214, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(593, 1215, '2024-08-25 18:06:51', 'Pending', '', 'Pending', '', 'Pending', ''),
(594, 863, '2024-08-26 12:30:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(595, 867, '2024-08-26 12:30:06', 'Pending', '', 'Pending', '', 'Pending', ''),
(596, 1217, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(597, 1218, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(598, 1219, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(599, 1220, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(600, 1222, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(601, 1223, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(602, 1224, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(603, 1225, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(604, 1226, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(605, 1228, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(606, 1232, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(607, 1233, '2024-08-27 01:04:14', 'Pending', '', 'Pending', '', 'Pending', ''),
(608, 1235, '2024-08-27 08:28:24', 'Pending', '', 'Pending', '', 'Pending', '');

-- --------------------------------------------------------

--
-- Structure for view `accepted_new_tp_view`
--
DROP TABLE IF EXISTS `accepted_new_tp_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `accepted_new_tp_view`  AS SELECT `r`.`code` AS `code`, `r`.`title` AS `title`, `r`.`credit_hour` AS `credit_hour`, `r`.`status` AS `status`, `r`.`tplink` AS `tplink`, `r`.`date` AS `date`, `r`.`review_date` AS `review_date`, `i`.`int_name` AS `int_name` FROM (`new_tp` `r` join `institution` `i` on((`r`.`int_id` = `i`.`int_id`))) ORDER BY `r`.`date` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `accepted_requests_view`
--
DROP TABLE IF EXISTS `accepted_requests_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `accepted_requests_view`  AS SELECT `r`.`req_id` AS `req_id`, `c`.`course_code` AS `course_code`, `c`.`title` AS `title`, `r`.`message` AS `message`, `r`.`status` AS `status`, `r`.`link` AS `link`, `r`.`request_date` AS `request_date` FROM (`request` `r` join `course` `c` on((`r`.`course` = `c`.`course_id`))) WHERE (`r`.`status` = 'Accepted') ORDER BY `r`.`request_date` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `bachelor_courses_view`
--
DROP TABLE IF EXISTS `bachelor_courses_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `bachelor_courses_view`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name`, `i`.`branch` AS `branch` FROM (`course` `d` join `institution` `i` on((`d`.`int_id` = `i`.`int_id`))) WHERE (`d`.`type` like 'Bachelor') ORDER BY `d`.`title` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `course_details_view`
--
DROP TABLE IF EXISTS `course_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `course_details_view`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name` FROM (`course` `d` join `institution` `i` on((`d`.`int_id` = `i`.`int_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `diploma_courses`
--
DROP TABLE IF EXISTS `diploma_courses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `diploma_courses`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name`, `i`.`branch` AS `branch` FROM (`course` `d` join `institution` `i` on((`d`.`int_id` = `i`.`int_id`))) WHERE (`d`.`type` like 'Diploma') ORDER BY `d`.`title` ASC ;

-- --------------------------------------------------------

--
-- Structure for view `students_in_progress_view`
--
DROP TABLE IF EXISTS `students_in_progress_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `students_in_progress_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, `p`.`prog_code` AS `prog_code`, `l`.`lect_name` AS `lect_name` FROM (((((`student` `s` left join `grade` `g` on((`s`.`stud_id` = `g`.`stud_id`))) left join `lecturer` `l` on((`s`.`lect_id` = `l`.`lect_id`))) left join `programme` `p` on((`s`.`prog_id` = `p`.`prog_id`))) left join `transfer` `t` on((`g`.`grade_id` = `t`.`grade_id`))) left join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) WHERE isnull(`g`.`grade_id`) GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username` ;

-- --------------------------------------------------------

--
-- Structure for view `student_courses`
--
DROP TABLE IF EXISTS `student_courses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_courses`  AS SELECT DISTINCT `c`.`course_code` AS `course_code`, `c`.`title` AS `title`, `c`.`credit_hour` AS `credit_hour`, `s`.`stud_id` AS `stud_id` FROM (((`transfer` `t` join `grade` `g` on((`t`.`grade_id` = `g`.`grade_id`))) join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) join `student` `s` on((`g`.`stud_id` = `s`.`stud_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `student_details_view`
--
DROP TABLE IF EXISTS `student_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_details_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`icno` AS `icno`, `s`.`faculty` AS `faculty`, `p`.`prog_code` AS `prog_code`, `s`.`session` AS `session`, `s`.`email` AS `email`, `s`.`phone` AS `phone`, `i`.`int_name` AS `int_name`, `s`.`username` AS `username`, `l`.`lect_name` AS `lect_name` FROM (((`student` `s` join `programme` `p` on((`s`.`prog_id` = `p`.`prog_id`))) join `institution` `i` on((`s`.`int_id` = `i`.`int_id`))) join `lecturer` `l` on((`s`.`lect_id` = `l`.`lect_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `student_finish_dean_view`
--
DROP TABLE IF EXISTS `student_finish_dean_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_finish_dean_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, max(`t`.`transfer_id`) AS `transfer_id`, `t`.`aa_status` AS `aa_status`, `t`.`tda_status` AS `tda_status`, `t`.`dean_status` AS `dean_status`, `i`.`int_id` AS `int_id`, `i`.`int_name` AS `int_name`, `p`.`prog_id` AS `prog_id`, `p`.`prog_code` AS `prog_code`, `l`.`lect_id` AS `lect_id`, `l`.`lect_name` AS `lect_name`, max(`t`.`transfer_date`) AS `transfer_date`, sum(`c`.`credit_hour`) AS `total` FROM ((((((`transfer` `t` join `grade` `g` on((`t`.`grade_id` = `g`.`grade_id`))) join `student` `s` on((`g`.`stud_id` = `s`.`stud_id`))) join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) join `institution` `i` on((`s`.`int_id` = `i`.`int_id`))) join `programme` `p` on((`s`.`prog_id` = `p`.`prog_id`))) join `lecturer` `l` on((`s`.`lect_id` = `l`.`lect_id`))) WHERE ((`t`.`aa_status` = 'Accepted') AND (`t`.`tda_status` = 'Accepted') AND (`t`.`dean_status` = 'Accepted')) GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username`, `t`.`aa_status`, `t`.`tda_status`, `t`.`dean_status` ;

-- --------------------------------------------------------

--
-- Structure for view `student_pending_dean_view`
--
DROP TABLE IF EXISTS `student_pending_dean_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_pending_dean_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, max(`t`.`transfer_id`) AS `transfer_id`, `t`.`aa_status` AS `aa_status`, `t`.`tda_status` AS `tda_status`, `t`.`dean_status` AS `dean_status`, max(`t`.`transfer_date`) AS `transfer_date`, sum(`c`.`credit_hour`) AS `total` FROM (((`transfer` `t` join `grade` `g` on((`t`.`grade_id` = `g`.`grade_id`))) join `student` `s` on((`g`.`stud_id` = `s`.`stud_id`))) join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) WHERE ((`t`.`aa_status` = 'Accepted') AND (`t`.`tda_status` = 'Accepted') AND (`t`.`dean_status` = 'Pending')) GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username`, `t`.`aa_status`, `t`.`tda_status`, `t`.`dean_status` ;

-- --------------------------------------------------------

--
-- Structure for view `student_total_credits`
--
DROP TABLE IF EXISTS `student_total_credits`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_total_credits`  AS SELECT `s`.`stud_id` AS `stud_id`, sum(`c`.`credit_hour`) AS `totalCredit` FROM (((`transfer` `t` join `grade` `g` on((`t`.`grade_id` = `g`.`grade_id`))) join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) join `student` `s` on((`g`.`stud_id` = `s`.`stud_id`))) GROUP BY `s`.`stud_id` ;

-- --------------------------------------------------------

--
-- Structure for view `student_transfer_dean_view`
--
DROP TABLE IF EXISTS `student_transfer_dean_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`ctdb`@`localhost` SQL SECURITY DEFINER VIEW `student_transfer_dean_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, max(`t`.`transfer_id`) AS `transfer_id`, `t`.`aa_status` AS `aa_status`, `t`.`tda_status` AS `tda_status`, `t`.`dean_status` AS `dean_status`, max(`t`.`transfer_date`) AS `transfer_date`, sum(`c`.`credit_hour`) AS `total` FROM ((((`transfer` `t` join `grade` `g` on((`t`.`grade_id` = `g`.`grade_id`))) join `student` `s` on((`g`.`stud_id` = `s`.`stud_id`))) join `lecturer` `r` on((`s`.`lect_id` = `r`.`lect_id`))) join `course` `c` on((`g`.`course_id` = `c`.`course_id`))) WHERE ((`t`.`aa_status` = 'Accepted') AND (`t`.`tda_status` = 'Accepted') AND (`t`.`dean_status` = 'Accepted')) GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username`, `t`.`aa_status`, `t`.`tda_status`, `t`.`dean_status` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`),
  ADD KEY `int_id` (`int_id`);

--
-- Indexes for table `grade`
--
ALTER TABLE `grade`
  ADD PRIMARY KEY (`grade_id`),
  ADD KEY `stud_id` (`stud_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `institution`
--
ALTER TABLE `institution`
  ADD PRIMARY KEY (`int_id`);

--
-- Indexes for table `lecturer`
--
ALTER TABLE `lecturer`
  ADD PRIMARY KEY (`lect_id`);

--
-- Indexes for table `new_tp`
--
ALTER TABLE `new_tp`
  ADD PRIMARY KEY (`tp_id`),
  ADD KEY `int_id` (`int_id`),
  ADD KEY `stud_id` (`stud_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stud_id` (`stud_id`);

--
-- Indexes for table `programme`
--
ALTER TABLE `programme`
  ADD PRIMARY KEY (`prog_id`);

--
-- Indexes for table `reject_transfer`
--
ALTER TABLE `reject_transfer`
  ADD PRIMARY KEY (`reject_id`),
  ADD KEY `stud_id` (`stud_id`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`req_id`),
  ADD KEY `stud_id` (`stud_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`stud_id`),
  ADD KEY `prog_id` (`prog_id`),
  ADD KEY `int_id` (`int_id`),
  ADD KEY `lect_id` (`lect_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `transfer`
--
ALTER TABLE `transfer`
  ADD PRIMARY KEY (`transfer_id`),
  ADD KEY `grade_id` (`grade_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1253;

--
-- AUTO_INCREMENT for table `institution`
--
ALTER TABLE `institution`
  MODIFY `int_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `lecturer`
--
ALTER TABLE `lecturer`
  MODIFY `lect_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `new_tp`
--
ALTER TABLE `new_tp`
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `programme`
--
ALTER TABLE `programme`
  MODIFY `prog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `reject_transfer`
--
ALTER TABLE `reject_transfer`
  MODIFY `reject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=609;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `course_ibfk_1` FOREIGN KEY (`int_id`) REFERENCES `institution` (`int_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grade`
--
ALTER TABLE `grade`
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `new_tp`
--
ALTER TABLE `new_tp`
  ADD CONSTRAINT `new_tp_ibfk_2` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `new_tp_ibfk_1` FOREIGN KEY (`int_id`) REFERENCES `institution` (`int_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reject_transfer`
--
ALTER TABLE `reject_transfer`
  ADD CONSTRAINT `reject_transfer_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`prog_id`) REFERENCES `programme` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`int_id`) REFERENCES `institution` (`int_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`lect_id`) REFERENCES `lecturer` (`lect_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `transfer_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
