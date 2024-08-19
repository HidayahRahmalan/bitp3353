-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 18, 2024 at 08:10 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `add_course` (IN `p_course_code` VARCHAR(50), IN `p_title` VARCHAR(100), IN `p_credit_hour` INT(11), IN `p_type` VARCHAR(20), IN `p_tpfile` VARCHAR(100), IN `p_int_id` INT(11))   BEGIN
INSERT INTO course (course_code, title, credit_hour, type, tpfile, int_id) 
VALUES (p_course_code, p_title, p_credit_hour, p_type, p_tpfile, p_int_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_institution` (IN `p_int_name` VARCHAR(200), IN `p_branch` VARCHAR(200))   BEGIN
INSERT INTO institution (int_name,branch) 
VALUES (p_int_name,p_branch);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_lecturer` (IN `p_lect_name` VARCHAR(100), IN `p_phoneno` VARCHAR(20), IN `p_email` VARCHAR(50), IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(50), IN `p_role` VARCHAR(20))   BEGIN
INSERT INTO lecturer (lect_name,phoneno,email,username,password,role) 
VALUES (p_lect_name,p_phoneno,p_email,p_username,p_password,p_role);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_programme` (IN `p_prog_name` VARCHAR(100), IN `p_prog_code` VARCHAR(50))   BEGIN
INSERT INTO programme (prog_name,prog_code) 
VALUES (p_prog_name,p_prog_code);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `add_student` (IN `p_name` VARCHAR(100), IN `p_icno` VARCHAR(20), IN `p_faculty` VARCHAR(200), IN `p_prog_id` INT(11), IN `p_session` VARCHAR(11), IN `p_email` VARCHAR(50), IN `p_phone` VARCHAR(20), IN `p_int_id` INT(11), IN `p_latest_int` VARCHAR(100), IN `p_username` VARCHAR(50), IN `p_password` VARCHAR(50), IN `p_lect_id` INT(11), IN `p_admin_id` INT(11))   BEGIN
INSERT INTO student (name, icno, faculty, prog_id, session, email, phone, int_id,latest_int, username, password,lect_id,admin_id) 
VALUES (p_name, p_icno, p_faculty, p_prog_id, p_session, p_email, p_phone, p_int_id, p_latest_int , p_username, p_password,p_lect_id,p_admin_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_course` (IN `p_course_id` INT(11))   BEGIN
	DELETE FROM course WHERE course_id=p_course_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_institution` (IN `p_int_id` INT(11))   BEGIN
	DELETE FROM institution WHERE int_id=p_int_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_lecturer` (IN `p_lect_id` INT(11))   BEGIN
	DELETE FROM lecturer WHERE lect_id=p_lect_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_programme` (IN `p_prog_id` INT(11))   BEGIN
	DELETE FROM programme WHERE prog_id=p_prog_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_student` (IN `p_stud_id` INT(11))   BEGIN
	DELETE FROM student WHERE stud_id=p_stud_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_institution` (IN `p_int_id` INT(11), IN `p_int_name` VARCHAR(200), IN `p_branch` VARCHAR(200))   BEGIN
	UPDATE institution 
    SET int_name=p_int_name, branch=p_branch
    WHERE int_id=p_int_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_lecturer` (IN `p_lect_id` INT(11), IN `p_lect_name` VARCHAR(100), IN `p_phoneno` VARCHAR(20), IN `p_email` VARCHAR(50), IN `p_username` VARCHAR(50), IN `p_role` VARCHAR(20))   BEGIN
	UPDATE lecturer 
    SET lect_name=p_lect_name,  phoneno=p_phoneno, email=p_email ,username=p_username, role=p_role
    WHERE lect_id=p_lect_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_programme` (IN `p_prog_id` INT(11), IN `p_prog_name` VARCHAR(100), IN `p_prog_code` VARCHAR(50))   BEGIN
    UPDATE programme 
    SET prog_name=p_prog_name, prog_code=p_prog_code
    WHERE prog_id=p_prog_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `update_student` (IN `p_stud_id` INT(11), IN `p_name` VARCHAR(100), IN `p_icno` VARCHAR(20), IN `p_prog_id` INT(11), IN `p_email` VARCHAR(50), IN `p_phone` VARCHAR(20), IN `p_int_id` INT(11), IN `p_username` VARCHAR(50), IN `p_lect_id` INT(11))   BEGIN
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
CREATE DEFINER=`root`@`localhost` FUNCTION `insert_grade` (`p_stud_id` INT(11), `p_course_id` INT(11), `p_similar` VARCHAR(255), `p_grade` VARCHAR(11)) RETURNS VARCHAR(100) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
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
,`date` date
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_code`, `title`, `credit_hour`, `type`, `tpfile`, `int_id`) VALUES
(1, 'DITI1243', 'Linear Algebra and Numerical Methods', 3, 'Diploma', '5c5ee2ca62a42c849846f243ba644cac.pdf', 1),
(2, 'DITP 1113', 'Programming 1', 3, 'Diploma', '4ff61d2ad7b4de6f47ba06b374d0d839.pdf', 1),
(3, 'DITP 1333', 'Database', 3, 'Diploma', '5d2d7565b028bba89b3166cf115675b4.pdf', 1),
(4, 'DITS 1133', 'Computer Organisation and Architecture', 3, 'Diploma', '894ddb1d668288cb46b2c2fef27085b5.pdf', 1),
(5, 'DITM 2113 ', 'Multimedia System', 3, 'Diploma', 'f672ab3d53152161603a2bfc6ceb61ab.pdf', 1),
(6, 'DITI 1233', 'Calculus', 3, 'Diploma', '6890549a6be042647f8f0e6aaa4689f2.pdf', 1),
(7, 'DITP 1123 ', 'Programming 2', 3, 'Diploma', '9fbd01dc44e136d2ffc0cf75a4b92bcd.pdf', 1),
(8, 'DITS 2213', 'Operating System', 3, 'Diploma', '529ab6375a416e17a22b81b983001675.pdf', 1),
(9, 'DITI 2233 ', 'Statistics and Probability', 3, 'Diploma', 'c96b06c442898f7a82f0599a786a5956.pdf', 1),
(10, 'DITP 2113 ', 'Data Structure and Algorithm', 3, 'Diploma', '0fa9408ceebdbc548050de9ffe083489.pdf', 1),
(11, 'DITP 3113 ', 'Object-oriented Programming', 3, 'Diploma', 'b059f1818ad1f685adf69f945aaea79e.pdf', 1),
(12, 'DITS 2313', 'Data Communication and Networking', 3, 'Diploma', 'd39deaa11a3415c1e8c2d1f98c83e20c.pdf', 1),
(13, 'DITM 2123 ', 'Web Programming', 3, 'Diploma', 'b52d2e01ba0bf250da146b8210657386.pdf', 1),
(14, 'DITU 3964 ', 'Diploma Project', 3, 'Diploma', '90feb16c1e9f88b502259133bafb14de.pdf', 1),
(15, 'BITI 1213 ', 'Linear Algebra and Discrete Mathematics', 3, 'Bachelor', 'c7daecb96b9e3465889df7f45afabb0c.pdf', 1),
(16, 'BITP 1113', 'Programming Technique', 3, 'Bachelor', '81cc21475eb8a7c1dabb1f006b38504e.pdf', 1),
(17, 'BITM 1113', 'Multimedia System', 3, 'Bachelor', '3aef01bafca8c7a0b4b7afba10030852.pdf', 1),
(18, 'BITS 1123', 'Computer Organisation and Architecture', 3, 'Bachelor', '95d4eeae8738da0795b1b62458b8b029.pdf', 1),
(19, 'BITI 1223', 'Calculus and Numerical Methods ', 3, 'Bachelor', '7fa9416a166758c2152c6f60a6ad4c40.pdf', 1),
(20, 'BITP 1123', 'Data Stucture and Algorithm', 3, 'Bachelor', 'f8cf4efc01fd3ad11d9c69419aaa5a84.pdf', 1),
(21, 'BITP 1323', 'Database', 3, 'Bachelor', '7a00d878b0ba774ca612a18e8009888b.pdf', 1),
(22, 'BITP 2213', 'Software Engineering', 3, 'Bachelor', 'c2633586339afbb6a8452ad219a36330.pdf', 1),
(23, 'BITU 2913', 'Workshop I ', 3, 'Bachelor', '4b4b98e462a214673189f7075da26270.pdf', 1),
(24, 'BITI 2233', 'Statistics and Probability', 3, 'Bachelor', 'd998f956d366654876db05995057cf08.pdf', 1),
(25, 'BITM 2313 ', 'Human Computer Interaction', 3, 'Bachelor', '4e09a96688af3dc42903e9614a3fec22.pdf', 1),
(26, 'BITS 1213', 'Operating System', 3, 'Bachelor', '80460ef949a03a139d027c18416fcaa5.pdf', 1),
(27, 'BITP 2303 ', 'Database Programming', 3, 'Bachelor', '37056e327c046d50c5335c2618da95f4.pdf', 1),
(28, 'BITP 2313', 'Database Design', 3, 'Bachelor', '5a475fe23db1c7bc97a020cb2c6e2e71.pdf', 1),
(29, 'BITI 1113 ', 'Artificial Intelligence', 3, 'Bachelor', 'acdda2ade14d411f29384a28f99c5a7a.pdf', 1),
(30, 'BITP 3113', 'Object-oriented Programming', 3, 'Bachelor', '3cb89842037fe7bf0b0fbd2899c930e3.pdf', 1),
(31, 'BITS 1313 ', 'Data Communication and Networking ', 3, 'Bachelor', 'a537d150e0fc0701fb35c483a4466ebb.pdf', 1),
(32, 'BITP 2223', 'Software Requirement and Design ', 3, 'Bachelor', '297adcba9fcf48f9fef4970dd19c71f9.pdf', 1),
(33, 'BITP 2323', 'Database Administration', 3, 'Bachelor', '48e2e2afb7e0a790b52ed0dbcfee59e0.pdf', 1),
(34, 'BITP 3433', 'Information Technology and Database Security', 3, 'Bachelor', '5e1c167834d71e165bfd2184795be9fc.pdf', 1),
(35, 'BITP 3223 ', 'Software Project Management', 3, 'Bachelor', 'a0cee413b7ce68131bc024ba5f3de31c.pdf', 1),
(36, 'BITP 3363', 'Data Warehousing and Business Intelligence', 3, 'Bachelor', 'd3424455588cd487f07307e04f5bb159.pdf', 1),
(37, 'BITP 3483', 'Geographic Information System', 3, 'Bachelor', '78b57a5405d01f4ba9e69c1661b1ecc5.pdf', 1),
(38, 'BITP 3353 ', 'Multimedia Database', 3, 'Bachelor', 'd4507d6a0dcbf1bd52149c5e6c1ce87e.pdf', 1),
(39, 'BITP 3513', 'ADVANCE DATABASE PROGRAMMING', 3, 'Bachelor', '52719c6c5216a7e949035c914e544197.pdf', 1),
(41, 'ABC 1234', 'Fundemental Programming', 3, 'Diploma', '1925b8c74870027c04997990564ef0d9.pdf', 2),
(46, 'DITP 2313', 'DATABASE PROGRAMMING', 3, 'Diploma', 'a26f77606b3e6484172d60c8cfb459f2.pdf', 1),
(47, 'DITP 2213', 'SOFTWARE ENGINEERING', 3, 'Diploma', 'b81af90b89192ece75f82eb39f502732.pdf', 1),
(48, 'DITM 2313', 'HUMAN COMPUTER INTERACTION', 3, 'Diploma', 'dcbfb64c3db4826612d9ce487e4d930e.pdf', 1),
(49, 'DITI 1113', 'ARTIFICAL INTELLIGENCE', 3, 'Diploma', '85f15ae7775596192f70d535a1471a07.pdf', 1),
(50, 'DITI1213', 'Discrete Mathematics', 3, 'Diploma', '5c5ee2ca62a42c849846f243ba644cac.pdf', 1),
(51, 'BKMW1234', 'COMPUTER ARCHITECTURE', 3, 'Diploma', 'GROUP18_ASSIGNMENT1_RESUBMIT.pdf', 2),
(52, 'BBMW1245', 'DATABASE MANAGEMENT', 3, 'Diploma', 'PD Drarft Proposal by D032010140.pdf', 2);

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
  `grade` varchar(11) NOT NULL,
  `similar2` varchar(255) NOT NULL,
  `grade2` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(599, 9, 32, 'N/A', 'N/A', '', ''),
(600, 9, 33, 'N/A', 'N/A', '', ''),
(601, 9, 34, 'N/A', 'N/A', '', ''),
(602, 9, 35, 'N/A', 'N/A', '', ''),
(603, 9, 36, 'N/A', 'N/A', '', ''),
(604, 9, 37, 'N/A', 'N/A', '', ''),
(605, 9, 38, 'N/A', 'N/A', '', ''),
(606, 9, 39, 'N/A', 'N/A', '', ''),
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
(624, 4, 32, 'N/A', 'N/A', '', ''),
(625, 4, 33, 'N/A', 'N/A', '', ''),
(626, 4, 34, 'N/A', 'N/A', '', ''),
(627, 4, 35, 'N/A', 'N/A', '', ''),
(628, 4, 36, 'N/A', 'N/A', '', ''),
(629, 4, 37, 'N/A', 'N/A', '', ''),
(630, 4, 38, 'N/A', 'N/A', '', ''),
(631, 4, 39, 'N/A', 'N/A', '', ''),
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
(649, 10, 32, 'N/A', 'N/A', '', ''),
(650, 10, 33, 'N/A', 'N/A', '', ''),
(651, 10, 34, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(652, 10, 35, 'N/A', 'N/A', '', ''),
(653, 10, 36, 'DITM 2123  Web Programming UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'A', '', ''),
(654, 10, 37, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'C-', '', ''),
(655, 10, 38, 'N/A', 'N/A', '', ''),
(656, 10, 39, 'N/A', 'N/A', '', ''),
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
(674, 11, 32, 'N/A', 'N/A', '', ''),
(675, 11, 33, 'N/A', 'N/A', '', ''),
(676, 11, 34, 'N/A', 'N/A', '', ''),
(677, 11, 35, 'N/A', 'N/A', '', ''),
(678, 11, 36, 'N/A', 'N/A', '', ''),
(679, 11, 37, 'N/A', 'N/A', '', ''),
(680, 11, 38, 'N/A', 'N/A', '', ''),
(681, 11, 39, 'N/A', 'N/A', '', ''),
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
(699, 12, 32, 'N/A', 'N/A', '', ''),
(700, 12, 33, 'N/A', 'N/A', '', ''),
(701, 12, 34, 'N/A', 'N/A', '', ''),
(702, 12, 35, 'N/A', 'N/A', '', ''),
(703, 12, 36, 'N/A', 'N/A', '', ''),
(704, 12, 37, 'N/A', 'N/A', '', ''),
(705, 12, 38, 'N/A', 'N/A', '', ''),
(706, 12, 39, 'N/A', 'N/A', '', ''),
(707, 8, 15, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(708, 8, 16, 'DITP 1113 Programming 1', 'A', '', ''),
(709, 8, 17, 'DITM 2113  Multimedia System', 'A', '', ''),
(710, 8, 18, 'DITS 1133 Computer Organisation and Architecture', 'A', '', ''),
(711, 8, 19, 'DITI 1233 Calculus and Numerical Methods', 'A', '', ''),
(712, 8, 20, 'DITP 2113  Data Structure and Algorithm', 'A', '', ''),
(713, 8, 21, 'DITP 1333 Database', 'A', '', ''),
(714, 8, 22, 'N/A', 'N/A', '', ''),
(715, 8, 23, 'DITU 3964  Diploma Project', 'A', '', ''),
(716, 8, 24, 'DITI 2233  Statistics and Probability', 'A', '', ''),
(717, 8, 25, 'N/A', 'N/A', '', ''),
(718, 8, 26, 'DITS 2213 Operating System', 'A', '', ''),
(719, 8, 27, 'N/A', 'N/A', '', ''),
(720, 8, 28, 'N/A', 'N/A', '', ''),
(721, 8, 29, 'N/A', 'N/A', '', ''),
(722, 8, 30, 'DITP 3113  Object-oriented Programming', 'A', '', ''),
(723, 8, 31, 'DITS 2313 Data Communication and Networking', 'C-', '', ''),
(724, 8, 32, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(725, 8, 33, 'DITP 1113 Programming 1', 'A', '', ''),
(726, 8, 34, 'DITI1243 LINEAR ALGEBRA AND DISCRETE MATHEMATICS', 'A', '', ''),
(727, 8, 35, 'N/A', 'N/A', '', ''),
(728, 8, 36, 'N/A', 'N/A', '', ''),
(729, 8, 37, 'N/A', 'N/A', '', ''),
(730, 8, 38, 'N/A', 'N/A', '', ''),
(731, 8, 39, 'N/A', 'N/A', '', ''),
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
(749, 16, 32, 'N/A', 'N/A', '', ''),
(750, 16, 33, 'N/A', 'N/A', '', ''),
(751, 16, 34, 'N/A', 'N/A', '', ''),
(752, 16, 35, 'N/A', 'N/A', '', ''),
(753, 16, 36, 'N/A', 'N/A', '', ''),
(754, 16, 37, 'N/A', 'N/A', '', ''),
(755, 16, 38, 'N/A', 'N/A', '', ''),
(756, 16, 39, 'N/A', 'N/A', '', ''),
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
(778, 13, 32, 'N/A', 'N/A', '', ''),
(779, 13, 33, 'N/A', 'N/A', '', ''),
(780, 13, 34, 'N/A', 'N/A', '', ''),
(781, 13, 35, 'N/A', 'N/A', '', ''),
(782, 13, 36, 'N/A', 'N/A', '', ''),
(783, 13, 37, 'N/A', 'N/A', '', ''),
(784, 13, 38, 'N/A', 'N/A', '', ''),
(785, 13, 39, 'N/A', 'N/A', '', ''),
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
(803, 27, 32, 'N/A', 'N/A', '', ''),
(804, 27, 33, 'N/A', 'N/A', '', ''),
(805, 27, 34, 'N/A', 'N/A', '', ''),
(806, 27, 35, 'N/A', 'N/A', '', ''),
(807, 27, 36, 'N/A', 'N/A', '', ''),
(808, 27, 37, 'N/A', 'N/A', '', ''),
(809, 27, 38, 'N/A', 'N/A', '', ''),
(810, 27, 39, 'N/A', 'N/A', '', ''),
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
(828, 28, 32, 'N/A', 'N/A', '', ''),
(829, 28, 33, 'N/A', 'N/A', '', ''),
(830, 28, 34, 'N/A', 'N/A', '', ''),
(831, 28, 35, 'N/A', 'N/A', '', ''),
(832, 28, 36, 'N/A', 'N/A', '', ''),
(833, 28, 37, 'N/A', 'N/A', '', ''),
(834, 28, 38, 'N/A', 'N/A', '', ''),
(835, 28, 39, 'N/A', 'N/A', '', ''),
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
(878, 24, 32, 'N/A', 'N/A', 'N/A', 'N/A'),
(879, 24, 33, 'N/A', 'N/A', 'N/A', 'N/A'),
(880, 24, 34, 'N/A', 'N/A', 'N/A', 'N/A'),
(881, 24, 35, 'N/A', 'N/A', 'N/A', 'N/A'),
(882, 24, 36, 'N/A', 'N/A', 'N/A', 'N/A'),
(883, 24, 37, 'N/A', 'N/A', 'N/A', 'N/A'),
(884, 24, 38, 'N/A', 'N/A', 'N/A', 'N/A'),
(885, 24, 39, 'N/A', 'N/A', 'N/A', 'N/A');

-- --------------------------------------------------------

--
-- Table structure for table `institution`
--

CREATE TABLE `institution` (
  `int_id` int(11) NOT NULL,
  `int_name` varchar(200) NOT NULL,
  `branch` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `date` date NOT NULL DEFAULT current_timestamp(),
  `review_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `new_tp`
--

INSERT INTO `new_tp` (`tp_id`, `code`, `title`, `credit_hour`, `tplink`, `status`, `int_id`, `stud_id`, `date`, `review_date`) VALUES
(2, 'Bitp 2324', 'Mathematics', 3, 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 1, 4, '2024-08-17', '2024-08-18'),
(3, 'BKMW1234', 'COMPUTER ARCHITECTURE', 3, 'GROUP18_ASSIGNMENT1_RESUBMIT.pdf', 'Accepted', 2, 9, '2024-08-17', '2024-08-18'),
(4, 'BBMW1245', 'DATABASE MANAGEMENT', 3, 'PD Drarft Proposal by D032010140.pdf', 'Accepted', 2, 9, '2024-08-18', '2024-08-18'),
(5, 'DBAC5643', 'SYSTEM SECURITY', 3, 'CASE STUDY.pdf', 'Rejected', 1, 4, '2024-08-19', '2024-08-19');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `stud_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `stud_id`, `message`, `status`) VALUES
(5, 22, 'You have been poked by your advisor. Please do credit transfer now!', 'unread'),
(7, 21, 'You have been poked by your advisor. Please do credit transfer now!', 'read');

-- --------------------------------------------------------

--
-- Table structure for table `programme`
--

CREATE TABLE `programme` (
  `prog_id` int(11) NOT NULL,
  `prog_name` varchar(100) NOT NULL,
  `prog_code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(8, 'BACHELOR OF INFORMATION TECHNOLOGY (GAME TECHNOLOGY) WITH HONOURS', 'BITE'),
(11, 'DEGREE DATABASE PROGRAMMINV', 'BITV');

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
  `request_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`req_id`, `course`, `message`, `link`, `status`, `stud_id`, `request_date`) VALUES
(19, '4', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-08-18 16:00:10'),
(20, '6', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 4, '2024-08-18 15:58:44'),
(21, '10', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 4, '2024-08-18 16:00:20'),
(23, '3', 'Update TP to the latest version, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-08-18 16:02:12'),
(24, '10', 'Update TP to the latest version, Update course code, Update course name, Wrong Teaching Plan, Other:', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Pending', 10, '2024-06-12 03:15:44'),
(26, '42', 'Update TP to the latest version', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=sharing', 'Accepted', 12, '2024-06-18 14:19:00'),
(29, '6', 'Update TP to the latest version, Update course code, Update course name, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Rejected', 21, '2024-08-18 16:02:04'),
(30, '10', 'Update course code', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=drive_link', 'Accepted', 4, '2024-06-24 03:40:44'),
(31, '52', 'Update course code, Wrong Teaching Plan', 'https://drive.google.com/file/d/1v7fIoVszYtATNqwn6xPOXfv-41VYO9oO/view?usp=sharing', 'Pending', 4, '2024-08-18 16:23:08');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`stud_id`, `name`, `icno`, `faculty`, `prog_id`, `session`, `email`, `phone`, `int_id`, `latest_int`, `username`, `password`, `lect_id`, `admin_id`) VALUES
(4, 'HIDAYAH BINTI BURHANNUDIN', '0210060301116', 'FTMK', 5, '23/24', 'B032220009@student.utem.edu.my', '01110664992', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220009', '07efe027f3de377b221c62a8ab29cb55', 1, 1),
(6, 'NUR AQILAH HUMAIRA BINTI IMRAN', '021017010002', 'FTMK', 5, '23/24', 'B032220016@student.utem.edu.my', '0127216104', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220016', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(7, 'HUSNA ARIFAH BINTI YUZAIMI', '020130030000', 'FTMK', 4, '23/24', 'B032210010@student.utem.edu.my', '012345677890', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032210010', 'e9713c995b07e49d34cc60bab9011e97', 1, 1),
(8, 'SALIHAH HUSNA', '0211111000900', 'FTMK', 5, '23/24', 'B032220018@student.utem.edu.my', '01234564', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032220018', 'e9713c995b07e49d34cc60bab9011e97', 2, 1),
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
(28, 'ADAM RAYYAN', '020121034567', 'FTMK', 8, '23/24', 'B032310127@student.utem.edu.my', '012345788768', 1, 'UNIVERSITI TEKNIKAL MALAYSIA MELAKA', 'B032310127', 'e9713c995b07e49d34cc60bab9011e97', 1, 1);

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
  `transfer_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `aa_status` varchar(10) NOT NULL,
  `aa_comment` varchar(50) NOT NULL,
  `tda_status` varchar(10) NOT NULL,
  `tda_comment` varchar(50) NOT NULL,
  `dean_status` varchar(10) NOT NULL,
  `dean_comment` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transfer`
--

INSERT INTO `transfer` (`transfer_id`, `grade_id`, `transfer_date`, `aa_status`, `aa_comment`, `tda_status`, `tda_comment`, `dean_status`, `dean_comment`) VALUES
(301, 582, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(302, 583, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(303, 584, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(304, 585, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(305, 586, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(306, 587, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(307, 588, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(308, 590, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(309, 591, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(310, 598, '2024-06-21 08:32:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(311, 607, '2024-06-11 15:15:30', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(312, 608, '2024-06-11 15:15:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(313, 609, '2024-06-11 15:15:33', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(314, 610, '2024-06-11 15:15:36', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(315, 611, '2024-06-11 15:15:39', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(316, 612, '2024-06-11 15:15:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(317, 613, '2024-06-11 15:15:45', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(318, 615, '2024-06-11 15:15:47', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(319, 616, '2024-06-11 15:15:50', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(320, 618, '2024-06-11 15:15:52', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(321, 623, '2024-06-11 15:15:54', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(322, 632, '2024-06-12 03:23:03', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(323, 633, '2024-06-12 03:23:06', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(324, 634, '2024-06-12 03:23:08', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(325, 635, '2024-06-12 03:23:09', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(326, 636, '2024-06-12 03:23:12', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(327, 637, '2024-06-12 03:23:13', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(329, 640, '2024-06-12 03:23:16', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(330, 641, '2024-06-21 08:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(331, 643, '2024-06-21 08:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(332, 647, '2024-06-21 08:39:32', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(346, 686, '2024-06-21 08:52:06', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(347, 707, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(348, 708, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(349, 709, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(350, 710, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(351, 711, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(352, 712, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(353, 715, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(354, 718, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(355, 722, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(356, 724, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(357, 725, '2024-06-19 09:30:48', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(359, 657, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(360, 658, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(361, 659, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(362, 660, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(363, 661, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(364, 662, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(365, 663, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(366, 665, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(367, 666, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(368, 668, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(369, 672, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(370, 673, '2024-06-23 07:58:42', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(382, 764, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(383, 765, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(384, 766, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(385, 767, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(386, 770, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(387, 776, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(388, 777, '2024-06-25 15:04:48', 'Accepted', '', 'Rejected', '', 'Pending', ''),
(389, 786, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(390, 787, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(391, 788, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(392, 789, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(393, 790, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(394, 791, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(395, 792, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(396, 794, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(397, 795, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(398, 796, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(399, 797, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(400, 801, '2024-06-24 06:22:31', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(401, 811, '2024-06-27 12:59:50', 'Accepted', '', 'Pending', '', 'Pending', ''),
(402, 812, '2024-06-27 12:59:51', 'Accepted', '', 'Pending', '', 'Pending', ''),
(403, 813, '2024-06-27 12:59:54', 'Accepted', '', 'Pending', '', 'Pending', ''),
(404, 814, '2024-06-27 12:59:58', 'Accepted', '', 'Pending', '', 'Pending', ''),
(405, 815, '2024-06-27 12:59:53', 'Accepted', '', 'Pending', '', 'Pending', ''),
(406, 816, '2024-06-27 13:00:01', 'Accepted', '', 'Pending', '', 'Pending', ''),
(407, 817, '2024-06-27 13:00:02', 'Accepted', '', 'Pending', '', 'Pending', ''),
(408, 820, '2024-06-27 13:00:04', 'Accepted', '', 'Pending', '', 'Pending', ''),
(409, 821, '2024-06-27 13:00:09', 'Accepted', '', 'Pending', '', 'Pending', ''),
(410, 822, '2024-06-27 13:00:11', 'Accepted', '', 'Pending', '', 'Pending', ''),
(411, 823, '2024-06-27 13:00:06', 'Accepted', '', 'Pending', '', 'Pending', ''),
(412, 826, '2024-06-27 13:00:14', 'Accepted', '', 'Pending', '', 'Pending', ''),
(413, 861, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(414, 864, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(415, 866, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(416, 868, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(417, 869, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(418, 870, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(419, 871, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(420, 872, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(421, 873, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(422, 875, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(423, 876, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', ''),
(424, 877, '2024-08-18 17:48:25', 'Accepted', '', 'Accepted', '', 'Accepted', '');

-- --------------------------------------------------------

--
-- Structure for view `accepted_new_tp_view`
--
DROP TABLE IF EXISTS `accepted_new_tp_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accepted_new_tp_view`  AS SELECT `r`.`code` AS `code`, `r`.`title` AS `title`, `r`.`credit_hour` AS `credit_hour`, `r`.`status` AS `status`, `r`.`tplink` AS `tplink`, `r`.`date` AS `date`, `r`.`review_date` AS `review_date`, `i`.`int_name` AS `int_name` FROM (`new_tp` `r` join `institution` `i` on(`r`.`int_id` = `i`.`int_id`)) ORDER BY `r`.`date` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `accepted_requests_view`
--
DROP TABLE IF EXISTS `accepted_requests_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accepted_requests_view`  AS SELECT `r`.`req_id` AS `req_id`, `c`.`course_code` AS `course_code`, `c`.`title` AS `title`, `r`.`message` AS `message`, `r`.`status` AS `status`, `r`.`link` AS `link`, `r`.`request_date` AS `request_date` FROM (`request` `r` join `course` `c` on(`r`.`course` = `c`.`course_id`)) WHERE `r`.`status` = 'Accepted' ORDER BY `r`.`request_date` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `bachelor_courses_view`
--
DROP TABLE IF EXISTS `bachelor_courses_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `bachelor_courses_view`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name`, `i`.`branch` AS `branch` FROM (`course` `d` join `institution` `i` on(`d`.`int_id` = `i`.`int_id`)) WHERE `d`.`type` like 'Bachelor' ORDER BY `d`.`title` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `course_details_view`
--
DROP TABLE IF EXISTS `course_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `course_details_view`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name` FROM (`course` `d` join `institution` `i` on(`d`.`int_id` = `i`.`int_id`))  ;

-- --------------------------------------------------------

--
-- Structure for view `diploma_courses`
--
DROP TABLE IF EXISTS `diploma_courses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `diploma_courses`  AS SELECT `d`.`course_id` AS `course_id`, `d`.`course_code` AS `course_code`, `d`.`title` AS `title`, `d`.`credit_hour` AS `credit_hour`, `d`.`type` AS `type`, `d`.`tpfile` AS `tpfile`, `i`.`int_name` AS `int_name`, `i`.`branch` AS `branch` FROM (`course` `d` join `institution` `i` on(`d`.`int_id` = `i`.`int_id`)) WHERE `d`.`type` like 'Diploma' ORDER BY `d`.`title` ASC  ;

-- --------------------------------------------------------

--
-- Structure for view `students_in_progress_view`
--
DROP TABLE IF EXISTS `students_in_progress_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `students_in_progress_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, `p`.`prog_code` AS `prog_code`, `l`.`lect_name` AS `lect_name` FROM (((((`student` `s` left join `grade` `g` on(`s`.`stud_id` = `g`.`stud_id`)) left join `lecturer` `l` on(`s`.`lect_id` = `l`.`lect_id`)) left join `programme` `p` on(`s`.`prog_id` = `p`.`prog_id`)) left join `transfer` `t` on(`g`.`grade_id` = `t`.`grade_id`)) left join `course` `c` on(`g`.`course_id` = `c`.`course_id`)) WHERE `g`.`grade_id` is null GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username``username`  ;

-- --------------------------------------------------------

--
-- Structure for view `student_courses`
--
DROP TABLE IF EXISTS `student_courses`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_courses`  AS SELECT DISTINCT `c`.`course_code` AS `course_code`, `c`.`title` AS `title`, `c`.`credit_hour` AS `credit_hour`, `s`.`stud_id` AS `stud_id` FROM (((`transfer` `t` join `grade` `g` on(`t`.`grade_id` = `g`.`grade_id`)) join `course` `c` on(`g`.`course_id` = `c`.`course_id`)) join `student` `s` on(`g`.`stud_id` = `s`.`stud_id`))  ;

-- --------------------------------------------------------

--
-- Structure for view `student_details_view`
--
DROP TABLE IF EXISTS `student_details_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_details_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`icno` AS `icno`, `s`.`faculty` AS `faculty`, `p`.`prog_code` AS `prog_code`, `s`.`session` AS `session`, `s`.`email` AS `email`, `s`.`phone` AS `phone`, `i`.`int_name` AS `int_name`, `s`.`username` AS `username`, `l`.`lect_name` AS `lect_name` FROM (((`student` `s` join `programme` `p` on(`s`.`prog_id` = `p`.`prog_id`)) join `institution` `i` on(`s`.`int_id` = `i`.`int_id`)) join `lecturer` `l` on(`s`.`lect_id` = `l`.`lect_id`))  ;

-- --------------------------------------------------------

--
-- Structure for view `student_pending_dean_view`
--
DROP TABLE IF EXISTS `student_pending_dean_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_pending_dean_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, max(`t`.`transfer_id`) AS `transfer_id`, `t`.`aa_status` AS `aa_status`, `t`.`tda_status` AS `tda_status`, `t`.`dean_status` AS `dean_status`, max(`t`.`transfer_date`) AS `transfer_date`, sum(`c`.`credit_hour`) AS `total` FROM (((`transfer` `t` join `grade` `g` on(`t`.`grade_id` = `g`.`grade_id`)) join `student` `s` on(`g`.`stud_id` = `s`.`stud_id`)) join `course` `c` on(`g`.`course_id` = `c`.`course_id`)) WHERE `t`.`aa_status` = 'Accepted' AND `t`.`tda_status` = 'Accepted' AND `t`.`dean_status` = 'Pending' GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username`, `t`.`aa_status`, `t`.`tda_status`, `t`.`dean_status``dean_status`  ;

-- --------------------------------------------------------

--
-- Structure for view `student_total_credits`
--
DROP TABLE IF EXISTS `student_total_credits`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_total_credits`  AS SELECT `s`.`stud_id` AS `stud_id`, sum(`c`.`credit_hour`) AS `totalCredit` FROM (((`transfer` `t` join `grade` `g` on(`t`.`grade_id` = `g`.`grade_id`)) join `course` `c` on(`g`.`course_id` = `c`.`course_id`)) join `student` `s` on(`g`.`stud_id` = `s`.`stud_id`)) GROUP BY `s`.`stud_id``stud_id`  ;

-- --------------------------------------------------------

--
-- Structure for view `student_transfer_dean_view`
--
DROP TABLE IF EXISTS `student_transfer_dean_view`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `student_transfer_dean_view`  AS SELECT `s`.`stud_id` AS `stud_id`, `s`.`name` AS `name`, `s`.`username` AS `username`, max(`t`.`transfer_id`) AS `transfer_id`, `t`.`aa_status` AS `aa_status`, `t`.`tda_status` AS `tda_status`, `t`.`dean_status` AS `dean_status`, max(`t`.`transfer_date`) AS `transfer_date`, sum(`c`.`credit_hour`) AS `total` FROM ((((`transfer` `t` join `grade` `g` on(`t`.`grade_id` = `g`.`grade_id`)) join `student` `s` on(`g`.`stud_id` = `s`.`stud_id`)) join `lecturer` `r` on(`s`.`lect_id` = `r`.`lect_id`)) join `course` `c` on(`g`.`course_id` = `c`.`course_id`)) WHERE `t`.`aa_status` = 'Accepted' AND `t`.`tda_status` = 'Accepted' AND `t`.`dean_status` = 'Accepted' GROUP BY `s`.`stud_id`, `s`.`name`, `s`.`username`, `t`.`aa_status`, `t`.`tda_status`, `t`.`dean_status``dean_status`  ;

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
  ADD KEY `prog_id` (`prog_id`,`int_id`,`lect_id`),
  ADD KEY `lect_id` (`lect_id`),
  ADD KEY `int_id` (`int_id`),
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
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `grade`
--
ALTER TABLE `grade`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=886;

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
  MODIFY `tp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `programme`
--
ALTER TABLE `programme`
  MODIFY `prog_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `req_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `transfer`
--
ALTER TABLE `transfer`
  MODIFY `transfer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=425;

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
  ADD CONSTRAINT `grade_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grade_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `new_tp`
--
ALTER TABLE `new_tp`
  ADD CONSTRAINT `new_tp_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `new_tp_ibfk_2` FOREIGN KEY (`int_id`) REFERENCES `institution` (`int_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `request`
--
ALTER TABLE `request`
  ADD CONSTRAINT `request_ibfk_1` FOREIGN KEY (`stud_id`) REFERENCES `student` (`stud_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`lect_id`) REFERENCES `lecturer` (`lect_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_2` FOREIGN KEY (`int_id`) REFERENCES `institution` (`int_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_3` FOREIGN KEY (`prog_id`) REFERENCES `programme` (`prog_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `student_ibfk_4` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `transfer`
--
ALTER TABLE `transfer`
  ADD CONSTRAINT `transfer_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade` (`grade_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
