-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 18, 2024 at 04:00 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crmsdb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_event` (IN `p_event_id` INT)   BEGIN
    DELETE FROM recycle_event WHERE event_id = p_event_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `delete_item` (IN `p_item_id` INT)   BEGIN
    DELETE FROM item_category WHERE item_id = p_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_item` (IN `p_item_id` INT, IN `p_item_name` VARCHAR(255), IN `p_item_category` VARCHAR(255), IN `p_item_image` VARCHAR(255))   BEGIN 
    UPDATE item_category 
    SET item_name = p_item_name, item_category = p_item_category, item_image = p_item_image
    WHERE item_id = p_item_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `edit_pickup` (IN `p_event_id` INT, IN `p_lorry_id` INT, IN `p_pickup_status` VARCHAR(255), IN `p_pickup_date` DATE, IN `p_pickup_time` TIME, IN `p_dropoff_destination` VARCHAR(255))   BEGIN
    UPDATE pickup_session 
    SET lorry_id = p_lorry_id, pickup_status = p_pickup_status, pickup_date = p_pickup_date, pickup_time = p_pickup_time, dropoff_destination = p_dropoff_destination 
    WHERE event_id = p_event_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_driver` (IN `p_name` VARCHAR(255), IN `p_phone_num` VARCHAR(11))   BEGIN 
    INSERT INTO driver (name, phone_num)
    VALUES (p_name, p_phone_num);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_item` (IN `p_item_name` VARCHAR(100), IN `p_item_category` VARCHAR(50), IN `p_item_image` VARCHAR(255))   BEGIN
    INSERT INTO item_category (item_name, item_category, item_image) 
    VALUES (p_item_name, p_item_category, p_item_image);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_pickup` (IN `p_event_id` INT, IN `p_lorry_id` INT, IN `p_pickup_status` VARCHAR(255), IN `p_pickup_time` TIME, IN `p_pickup_date` DATE, IN `p_dropoff_destination` VARCHAR(255))   BEGIN
    INSERT INTO pickup_session (event_id, lorry_id, pickup_status, pickup_time, pickup_date, dropoff_destination) 
    VALUES (p_event_id, p_lorry_id, p_pickup_status, p_pickup_time, p_pickup_date, p_dropoff_destination);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insert_recycle_event` (IN `p_event_name` VARCHAR(255), IN `p_event_loc` VARCHAR(255), IN `p_event_date` DATE, IN `p_event_start_time` TIME, IN `p_event_end_time` TIME, IN `p_event_image` VARCHAR(255), IN `p_admin_id` INT)   BEGIN
    INSERT INTO recycle_event (event_name, event_loc, event_date, event_start_time, event_end_time, event_image, admin_id)
    VALUES (p_event_name, p_event_loc, p_event_date, p_event_start_time, p_event_end_time, p_event_image, p_admin_id);
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `add_donate` (`p_cloth_condition` VARCHAR(255), `p_user_id` INT, ` p_item_id` INT, `p_event_id` INT) RETURNS INT(11)  BEGIN
   INSERT INTO donate_item (cloth_condition, user_id, item_id, event_id)
    VALUES (p_cloth_condition, p_user_id, p_item_id, p_event_id);
    
    RETURN LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `getFormattedFeedback` () RETURNS TEXT CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    DECLARE formatted_feedback TEXT DEFAULT '';
    DECLARE description VARCHAR(255);
    DECLARE feedback_date DATE;
    DECLARE feedback_time TIME;
    DECLARE rating_score INT;

    DECLARE done INT DEFAULT 0;
    DECLARE cur CURSOR FOR SELECT description, feedback_date, feedback_time, rating_score FROM feedback;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO description, feedback_date, feedback_time, rating_score;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Format data as needed
        SET formatted_feedback = CONCAT(formatted_feedback, description, ' - ', feedback_date, ' ', feedback_time, ' ', rating_score, '\n');
    END LOOP;

    CLOSE cur;

    RETURN formatted_feedback;
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `isLorryAvailable` (`lorry_id` INT) RETURNS TINYINT(1)  BEGIN
    DECLARE available BOOLEAN;
    
    SELECT COUNT(*) INTO available
    FROM pickup_lorry
    WHERE lorry_id = lorry_id
      AND status = 'Available';
    
    RETURN available;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_num` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `phone_num`, `address`, `password`) VALUES
(1, 'Salihah ', '0172149959', '5-1, Taman Nuri Sentosa, Durian Tunggal', 'd69c095450a8744bfde14159359db85ec7234273b26409a874e3b2dbe2d4dde4');

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `ann_id` int(5) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`ann_id`, `message`, `created_at`, `is_read`, `user_id`) VALUES
(11, 'Please come to the Pusat Amal QC - Melaka on the 24-06-2024 to drop off your items.', '2024-06-23 14:59:28', 0, 4),
(12, 'Please come to the Pusat Amal QC - Melaka on the 24-06-2024 to drop off your items.', '2024-06-23 14:59:37', 0, 4),
(13, 'Please come to the Pusat Amal QC - Melaka on the 24-06-2024 to drop off your items.', '2024-06-23 15:00:05', 0, 4),
(14, 'Your donation item at Sedekah Baju untuk Anak Yatim with ID 10 has been picked up.', '2024-06-23 15:41:56', 0, 4),
(15, 'Please come to the Pusat Amal QC - Melaka on the 24-06-2024 to drop off your items.', '2024-06-23 16:44:54', 0, 6),
(16, 'Please come to the Pusat Amal QC - Melaka on the 25-06-2024 to drop off your items.', '2024-06-23 16:45:07', 0, 6),
(17, 'Please come to the Pusat Amal QC - Melaka on the 24-06-2024 to drop off your items.', '2024-06-23 16:45:42', 0, 5),
(18, 'Please come to the Pusat Amal QC - Melaka on the 25-06-2024 to drop off your items.', '2024-06-23 16:45:53', 0, 5),
(19, 'Please come to the The Vintage Attire - Penang on the 26-06-2024 to drop off your items.', '2024-06-23 17:42:40', 0, 4),
(21, 'Your donation item at Sedekah Baju untuk Anak Yatim with ID 10 has been picked up.', '2024-06-23 18:47:53', 0, 4),
(22, 'Please come to the The Vintage Attire - Penang on the 03-08-2024 to drop off your items.', '2024-08-01 15:16:07', 0, 4),
(23, 'Your donation item at Monthly Recycle july with ID 12 has been picked up and hand in to Pertubuhan Rumah Kebajikan Seri Cahaya Pulau Pinang.', '2024-08-01 15:17:45', 0, 4),
(25, 'Please come to the The Vintage Attire - Penang on the 17-08-2024 to drop off your items.', '2024-08-15 14:24:46', 0, 4),
(26, 'Your donation item at Monthly Recycle july with ID 12 has been picked up and hand in to Pertubuhan Rumah Kebajikan Seri Cahaya Pulau Pinang.', '2024-08-16 03:45:48', 0, 4),
(27, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:09:37', 0, 6),
(28, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:10:37', 0, 5),
(29, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:16:10', 0, 9),
(30, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:34:49', 0, 8),
(31, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:36:49', 0, 7),
(32, 'Please come to the Pusat Amal QC - Melaka on the 24-08-2024 to drop off your items.', '2024-08-18 13:49:07', 0, 4),
(33, 'Please come to the Usnasa Bundle Kepala Batas - Penang on the 20-08-2024 to drop off your items.', '2024-08-18 13:54:00', 0, 4),
(34, 'Please come to the Usnasa Bundle Kepala Batas - Penang on the 20-08-2024 to drop off your items.', '2024-08-18 13:55:01', 0, 9);

-- --------------------------------------------------------

--
-- Table structure for table `donate_item`
--

CREATE TABLE `donate_item` (
  `donate_id` int(5) NOT NULL,
  `cloth_condition` varchar(50) NOT NULL,
  `user_id` int(5) NOT NULL,
  `item_id` int(5) NOT NULL,
  `event_id` int(5) NOT NULL,
  `item_point` int(5) NOT NULL,
  `point_redeem` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donate_item`
--

INSERT INTO `donate_item` (`donate_id`, `cloth_condition`, `user_id`, `item_id`, `event_id`, `item_point`, `point_redeem`) VALUES
(24, 'Brand New', 4, 8, 10, 10, 'Yes'),
(25, 'Good Condition', 4, 9, 10, 10, 'Yes'),
(26, 'Brand New', 4, 6, 10, 10, 'Yes'),
(27, 'Brand New', 4, 9, 10, 10, 'Yes'),
(28, 'Good Condition', 6, 6, 10, 0, 'Yes'),
(29, 'Good Condition', 6, 8, 10, 0, 'Yes'),
(30, 'Good Condition', 6, 9, 10, 0, 'Yes'),
(31, 'Good Condition', 6, 9, 7, 0, 'Yes'),
(32, 'Brand New', 5, 8, 10, 0, 'Yes'),
(33, 'Brand New', 5, 9, 10, 0, 'Yes'),
(34, 'Average Condition', 5, 10, 7, 0, 'Yes'),
(35, 'Brand New', 4, 8, 5, 0, 'Yes'),
(36, 'Never Use', 4, 6, 12, 10, 'Yes'),
(37, 'Never Use', 4, 8, 12, 10, 'Yes'),
(42, 'Good Condition', 4, 6, 12, 10, 'No'),
(43, 'Good Condition', 4, 8, 12, 10, 'No'),
(44, 'Good Condition', 4, 9, 12, 10, 'No'),
(45, 'Good Condition', 4, 10, 12, 10, 'No'),
(46, 'Good Condition', 6, 6, 13, 0, 'No'),
(47, 'Good Condition', 6, 8, 13, 0, 'No'),
(48, 'Never Use', 5, 9, 13, 0, 'No'),
(49, 'Never Use', 5, 10, 13, 0, 'No'),
(50, 'Average Condition', 9, 8, 13, 0, 'No'),
(51, 'Average Condition', 9, 10, 13, 0, 'No'),
(52, 'Good Condition', 8, 9, 13, 0, 'No'),
(53, 'Good Condition', 8, 14, 13, 0, 'No'),
(54, 'Never Use', 7, 6, 13, 0, 'No'),
(55, 'Never Use', 7, 9, 13, 0, 'No'),
(56, 'Never Use', 7, 10, 13, 0, 'No'),
(57, 'Never Use', 7, 14, 13, 0, 'No'),
(58, 'Good Condition', 4, 6, 13, 0, 'No'),
(59, 'Good Condition', 4, 8, 13, 0, 'No'),
(60, 'Good Condition', 4, 9, 13, 0, 'No'),
(61, 'Good Condition', 4, 10, 13, 0, 'No'),
(62, 'Good Condition', 4, 13, 13, 0, 'No'),
(63, 'Good Condition', 4, 14, 13, 0, 'No'),
(64, 'Average Condition', 4, 6, 14, 0, 'No'),
(65, 'Average Condition', 4, 8, 14, 0, 'No'),
(66, 'Average Condition', 4, 9, 14, 0, 'No'),
(67, 'Average Condition', 4, 10, 14, 0, 'No'),
(68, 'Average Condition', 4, 13, 14, 0, 'No'),
(69, 'Average Condition', 4, 14, 14, 0, 'No'),
(70, 'Never Use', 9, 6, 14, 0, 'No'),
(71, 'Never Use', 9, 10, 14, 0, 'No'),
(72, 'Never Use', 9, 14, 14, 0, 'No');

-- --------------------------------------------------------

--
-- Table structure for table `driver`
--

CREATE TABLE `driver` (
  `driver_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `phone_num` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `driver`
--

INSERT INTO `driver` (`driver_id`, `name`, `phone_num`) VALUES
(1, 'Faiz Ahmad', '0187261726'),
(2, 'Aqil Zulkiflee', '0129182111'),
(3, 'Majid Hamid', '0123456789');

--
-- Triggers `driver`
--
DELIMITER $$
CREATE TRIGGER `before_insert_driver` BEFORE INSERT ON `driver` FOR EACH ROW BEGIN
    DECLARE phone_count INT;
    SELECT COUNT(*) INTO phone_count
    FROM driver
    WHERE phone_num = NEW.phone_num;

    IF phone_count > 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phone number already registered';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `driver_lorry_info`
-- (See below for the actual view)
--
CREATE TABLE `driver_lorry_info` (
`name` varchar(50)
,`phone_num` varchar(12)
,`plate_number` varchar(10)
);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(5) NOT NULL,
  `description` varchar(100) NOT NULL,
  `feedback_date` date NOT NULL,
  `feedback_time` time NOT NULL,
  `rating_score` int(11) NOT NULL,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`feedback_id`, `description`, `feedback_date`, `feedback_time`, `rating_score`, `user_id`) VALUES
(3, 'Good!', '2024-08-18', '11:33:00', 4, 4),
(4, 'Convenient use', '2024-08-01', '20:50:00', 5, 6),
(5, 'Really good platform', '2024-08-01', '20:51:00', 5, 6);

-- --------------------------------------------------------

--
-- Table structure for table `item_category`
--

CREATE TABLE `item_category` (
  `item_id` int(5) NOT NULL,
  `item_name` varchar(100) NOT NULL,
  `item_category` varchar(50) NOT NULL,
  `item_image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_category`
--

INSERT INTO `item_category` (`item_id`, `item_name`, `item_category`, `item_image`) VALUES
(6, 'Scarf', 'Women', 'tudung.jpg'),
(8, 'T-Shirt', 'Men', 'tshirt.jpg'),
(9, 'Baju Kurung', 'Women', 'bajukurung.jpg'),
(10, 'Blouse', 'Women', 'blouse.jpg'),
(13, 'Seluar lelaki', 'Men', 'seluar lelaki.jpg'),
(14, 'Seluar Wanita dan Skirt', 'Women', 'seluar perempuan.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `lorry_driver`
--

CREATE TABLE `lorry_driver` (
  `lorry_id` int(5) NOT NULL,
  `driver_id` int(5) NOT NULL,
  `duty_date` date NOT NULL,
  `duty_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lorry_driver`
--

INSERT INTO `lorry_driver` (`lorry_id`, `driver_id`, `duty_date`, `duty_time`) VALUES
(1, 1, '2024-05-22', '17:19:00'),
(2, 1, '2024-06-10', '11:00:00'),
(2, 1, '2024-06-13', '11:53:00'),
(1, 1, '2024-06-24', '18:42:00'),
(3, 3, '2024-08-23', '09:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `pickup_lorry`
--

CREATE TABLE `pickup_lorry` (
  `lorry_id` int(5) NOT NULL,
  `plate_number` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL,
  `admin_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_lorry`
--

INSERT INTO `pickup_lorry` (`lorry_id`, `plate_number`, `status`, `admin_id`) VALUES
(1, 'WB3475', 'Unavailable', 1),
(2, 'PQT 1718', 'Available', 1),
(3, 'MDG2839', 'Unavailable', 1);

--
-- Triggers `pickup_lorry`
--
DELIMITER $$
CREATE TRIGGER `before_insert_pickup_lorry` BEFORE INSERT ON `pickup_lorry` FOR EACH ROW BEGIN
    DECLARE plate_count INT;

    SELECT COUNT(*) INTO plate_count
    FROM pickup_lorry
    WHERE plate_number = NEW.plate_number;

    IF plate_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Plate number already exists';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `pickup_session`
--

CREATE TABLE `pickup_session` (
  `event_id` int(5) NOT NULL,
  `lorry_id` int(5) NOT NULL,
  `pickup_status` varchar(50) NOT NULL,
  `pickup_time` time NOT NULL,
  `pickup_date` date NOT NULL,
  `dropoff_destination` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pickup_session`
--

INSERT INTO `pickup_session` (`event_id`, `lorry_id`, `pickup_status`, `pickup_time`, `pickup_date`, `dropoff_destination`) VALUES
(5, 2, 'Already Pickup', '20:35:00', '2024-06-19', 'Pusat Jagaan Permata Kasih Alma'),
(10, 1, 'Already Pickup', '18:30:00', '2024-06-25', 'Pertubuhan Kebajikan Anak Anak Harapan'),
(12, 2, 'Already Pickup', '17:22:00', '2024-08-17', 'Pertubuhan Rumah Kebajikan Seri Cahaya Pulau Pinang'),
(13, 3, 'To Be Pickup', '17:30:00', '2024-08-24', 'Pertubuhan Kebajikan Anak Anak Harapan'),
(14, 1, 'To Be Pickup', '14:00:00', '2024-08-20', 'Pertubuhan Rumah Kebajikan Seri Cahaya Pulau Pinang');

-- --------------------------------------------------------

--
-- Table structure for table `point_redeem`
--

CREATE TABLE `point_redeem` (
  `redeem_id` int(5) NOT NULL,
  `total_point` int(100) NOT NULL,
  `total_ringgit` double NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_num` varchar(50) NOT NULL,
  `approve_status` varchar(50) NOT NULL,
  `user_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `point_redeem`
--

INSERT INTO `point_redeem` (`redeem_id`, `total_point`, `total_ringgit`, `bank_name`, `account_num`, `approve_status`, `user_id`) VALUES
(3, 60, 3, 'Maybank', '1503920192891', 'Approved', 4);

-- --------------------------------------------------------

--
-- Table structure for table `recycle_event`
--

CREATE TABLE `recycle_event` (
  `event_id` int(5) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_loc` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_start_time` time NOT NULL,
  `event_end_time` time NOT NULL,
  `event_image` varchar(255) NOT NULL,
  `admin_id` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recycle_event`
--

INSERT INTO `recycle_event` (`event_id`, `event_name`, `event_loc`, `event_date`, `event_start_time`, `event_end_time`, `event_image`, `admin_id`) VALUES
(5, 'Monthly Recycle June', 'The Vintage Attire - Penang', '2024-06-26', '10:00:00', '18:30:00', 'images/kedai1.png', 1),
(7, 'Sumbangan Pakaian June', 'Pusat Amal QC - Melaka', '2024-06-25', '09:00:00', '17:00:00', 'images/kedai3.jpg', 1),
(10, 'Sedekah Baju untuk Anak Yatim', 'Pusat Amal QC - Melaka', '2024-06-24', '09:00:00', '17:30:00', 'images/kedai3.jpg', 1),
(12, 'Monthly Recycle july', 'The Vintage Attire - Penang', '2024-08-17', '09:30:00', '13:30:00', 'images/kedai1.png', 1),
(13, 'Merdeka Donation', 'Pusat Amal QC - Melaka', '2024-08-24', '09:08:00', '17:08:00', 'images/kedai3.jpg', 1),
(14, 'Monthly Recycle August', 'Usnasa Bundle Kepala Batas - Penang', '2024-08-20', '09:00:00', '12:00:00', 'images/kedai2.jpeg', 1);

--
-- Triggers `recycle_event`
--
DELIMITER $$
CREATE TRIGGER `before_insert_recycle_event` BEFORE INSERT ON `recycle_event` FOR EACH ROW BEGIN
    DECLARE event_count INT;

    SELECT COUNT(*) INTO event_count
    FROM recycle_event
    WHERE event_date = NEW.event_date;

    IF event_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Event date already exists.Please select another date.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_num` varchar(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `name`, `phone_num`, `address`, `email`, `username`, `password`) VALUES
(4, 'Hidayah', '0194747944', 'Taman Nuri Sentosa, Durian Tunggal', 'hidayahd@gmail.com', 'dayah', '$2y$10$nPVogvYLVcwMtiMio4A3Te9BFbHeDEaC0/AlkoAJhASn/HJh0ciC.'),
(5, 'Aminah', '0172149959', 'Taman Nuri Sentosa, Durian Tunggal', 'aminah@gmail.com', 'aminah', '$2y$10$QDcV0YA4aM7PJx7ZXu.iMOA.eG2Vot6/HCOZk1rx/i3cwq.jK8ReO'),
(6, 'Aqilah Humaira', '0192839191', 'Taman Seri Juru, BM', 'qilah@gmail.com', 'qilah', '$2y$10$z2FnOwmj4x0U.AtFeLcVdOnScv0j3yxbZF2mIA92EqBJSspEQW7uC'),
(7, 'Najwa', '0123527271', 'Taman Bukit Katil Melaka', 'najwa66@gmail.com', 'najwaskh', '$2y$10$7BPae1CXuzTPR9x.OLTYjepURlGUVIEe3XdEW4oacfud2zpm8Lygi'),
(8, 'Nabila ', '0172817281', 'The Height Residence Melaka', 'nabila291@gmail.com', 'bella', '$2y$10$lDPHPTvajJ61MOiV581ylOGk9duzT5cq8.jEipMf87KjgFnMTglGe'),
(9, 'Nurfakhirah', '0122628182', 'Taman Nuri Indah Melaka', 'fakhirah@gmail.com', 'fakhirah', '$2y$10$xxIK8EjY959fZzqOTgz7e.mQPrUJQY7xdTl.iuXPyu281pcqRH7R6');

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `before_insert_user` BEFORE INSERT ON `user` FOR EACH ROW BEGIN
    DECLARE phone_count INT;
    
    -- Check if the new phone number already exists in the table
    SELECT COUNT(*) INTO phone_count
    FROM user
    WHERE phone_num = NEW.phone_num;
    
    -- If the phone number already exists, raise an error with custom message
    IF phone_count > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Phone number already registered';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_pickup_details`
-- (See below for the actual view)
--
CREATE TABLE `view_pickup_details` (
`event_id` int(5)
,`lorry_id` int(5)
,`pickup_status` varchar(50)
,`pickup_time` time
,`pickup_date` date
,`dropoff_destination` varchar(255)
,`event_name` varchar(255)
,`event_loc` varchar(255)
,`plate_number` varchar(10)
);

-- --------------------------------------------------------

--
-- Structure for view `driver_lorry_info`
--
DROP TABLE IF EXISTS `driver_lorry_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `driver_lorry_info`  AS SELECT `driver`.`name` AS `name`, `driver`.`phone_num` AS `phone_num`, `pickup_lorry`.`plate_number` AS `plate_number` FROM ((`driver` join `lorry_driver` on(`driver`.`driver_id` = `lorry_driver`.`driver_id`)) join `pickup_lorry` on(`lorry_driver`.`lorry_id` = `pickup_lorry`.`lorry_id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_pickup_details`
--
DROP TABLE IF EXISTS `view_pickup_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_pickup_details`  AS SELECT `pickup_session`.`event_id` AS `event_id`, `pickup_session`.`lorry_id` AS `lorry_id`, `pickup_session`.`pickup_status` AS `pickup_status`, `pickup_session`.`pickup_time` AS `pickup_time`, `pickup_session`.`pickup_date` AS `pickup_date`, `pickup_session`.`dropoff_destination` AS `dropoff_destination`, `recycle_event`.`event_name` AS `event_name`, `recycle_event`.`event_loc` AS `event_loc`, `pickup_lorry`.`plate_number` AS `plate_number` FROM ((`pickup_session` join `recycle_event` on(`pickup_session`.`event_id` = `recycle_event`.`event_id`)) join `pickup_lorry` on(`pickup_session`.`lorry_id` = `pickup_lorry`.`lorry_id`)) ORDER BY `pickup_session`.`pickup_date` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`ann_id`),
  ADD KEY `user-id` (`user_id`);

--
-- Indexes for table `donate_item`
--
ALTER TABLE `donate_item`
  ADD PRIMARY KEY (`donate_id`),
  ADD KEY `userid` (`user_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `driver`
--
ALTER TABLE `driver`
  ADD PRIMARY KEY (`driver_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `useridd` (`user_id`);

--
-- Indexes for table `item_category`
--
ALTER TABLE `item_category`
  ADD PRIMARY KEY (`item_id`);

--
-- Indexes for table `lorry_driver`
--
ALTER TABLE `lorry_driver`
  ADD KEY `lorryid` (`lorry_id`),
  ADD KEY `driver_id` (`driver_id`);

--
-- Indexes for table `pickup_lorry`
--
ALTER TABLE `pickup_lorry`
  ADD PRIMARY KEY (`lorry_id`),
  ADD KEY `adminid` (`admin_id`);

--
-- Indexes for table `pickup_session`
--
ALTER TABLE `pickup_session`
  ADD KEY `eventid` (`event_id`),
  ADD KEY `lorry_id` (`lorry_id`);

--
-- Indexes for table `point_redeem`
--
ALTER TABLE `point_redeem`
  ADD PRIMARY KEY (`redeem_id`),
  ADD KEY `userr_id` (`user_id`);

--
-- Indexes for table `recycle_event`
--
ALTER TABLE `recycle_event`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `ann_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `donate_item`
--
ALTER TABLE `donate_item`
  MODIFY `donate_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `driver`
--
ALTER TABLE `driver`
  MODIFY `driver_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `item_category`
--
ALTER TABLE `item_category`
  MODIFY `item_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `pickup_lorry`
--
ALTER TABLE `pickup_lorry`
  MODIFY `lorry_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `point_redeem`
--
ALTER TABLE `point_redeem`
  MODIFY `redeem_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recycle_event`
--
ALTER TABLE `recycle_event`
  MODIFY `event_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `user-id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `donate_item`
--
ALTER TABLE `donate_item`
  ADD CONSTRAINT `event_id` FOREIGN KEY (`event_id`) REFERENCES `recycle_event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `item_id` FOREIGN KEY (`item_id`) REFERENCES `item_category` (`item_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userid` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `useridd` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lorry_driver`
--
ALTER TABLE `lorry_driver`
  ADD CONSTRAINT `driver_id` FOREIGN KEY (`driver_id`) REFERENCES `driver` (`driver_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lorryid` FOREIGN KEY (`lorry_id`) REFERENCES `pickup_lorry` (`lorry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pickup_lorry`
--
ALTER TABLE `pickup_lorry`
  ADD CONSTRAINT `adminid` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pickup_session`
--
ALTER TABLE `pickup_session`
  ADD CONSTRAINT `eventid` FOREIGN KEY (`event_id`) REFERENCES `recycle_event` (`event_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lorry_id` FOREIGN KEY (`lorry_id`) REFERENCES `pickup_lorry` (`lorry_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `point_redeem`
--
ALTER TABLE `point_redeem`
  ADD CONSTRAINT `userr_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `recycle_event`
--
ALTER TABLE `recycle_event`
  ADD CONSTRAINT `admin_id` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
