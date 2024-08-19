-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2024 at 12:48 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cocudb`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`` PROCEDURE `insert_teacher` (IN `p_TeacherName` VARCHAR(50), IN `p_TeacherIc` VARCHAR(12), IN `p_TeacherPhone` VARCHAR(11), IN `p_TeacherEmail` VARCHAR(20))   BEGIN
    INSERT INTO teacher (TeacherName, TeacherIc, TeacherPhone, TeacherEmail) 
    VALUES (p_TeacherName, p_TeacherIc, p_TeacherPhone, p_TeacherEmail);
END$$

CREATE DEFINER=`` PROCEDURE `update_clubtype` (IN `p_ClubTypeID` INT(10), IN `p_ClubTypeName` VARCHAR(30))   BEGIN
	UPDATE clubtype
    SET ClubTypeName = p_ClubTypeName
    WHERE ClubTypeID = P_ClubTypeID;
End$$

--
-- Functions
--
CREATE DEFINER=`` FUNCTION `insert_level` (`p_LevelType` VARCHAR(20)) RETURNS VARCHAR(100) CHARSET utf8mb4  BEGIN
DECLARE insert_level VARCHAR(100);

    INSERT INTO level (LevelType) 
    VALUES (p_LevelType);
    
RETURN insert_level;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `ActivityID` int(10) NOT NULL,
  `ActivityName` varchar(50) NOT NULL,
  `ActivityVenue` varchar(50) NOT NULL,
  `ActivityDate` varchar(10) NOT NULL,
  `ActivityMark` int(3) NOT NULL,
  `ClubID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`ActivityID`, `ActivityName`, `ActivityVenue`, `ActivityDate`, `ActivityMark`, `ClubID`) VALUES
(18, 'Taklimat Kesihatan', 'Dewan Makan Aspuri', '05-09-2024', 2, 15),
(28, 'Melukis Logo Kelab Sejarah', 'Kelas 2 Khadijah', '29-05-2024', 20, 20),
(31, 'Pameran', 'Kelas 4 Ibnu Sina', '12-06-2024', 5, 17),
(34, 'Mesyuarat Jawatan Kuasa', 'Bilik RBT', '23-06-2023', 20, 20),
(35, 'Taklimat Bantuan Kecemasan', 'Surau', '23-06-2023', 12, 15),
(36, 'Taklimat Kebakaran', 'Tapak Perhimpunan', '23-06-2024', 40, 15),
(37, 'Latihan Individu', 'Padang Sekolah', '23-06-2024', 25, 24),
(38, 'Latihan Main Berkumpulan', 'Padang Sekolah', '23-06-2024', 20, 24),
(69, 'Latihan Ketangkasan', 'Padang Sekolah', '15-08-2024', 30, 23);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(10) NOT NULL,
  `AdminName` varchar(50) NOT NULL,
  `AdminIc` varchar(12) NOT NULL,
  `AdminEmail` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `AdminName`, `AdminIc`, `AdminEmail`) VALUES
(1, 'Siti Haslina Binti Hassan', '751108017438', 'haslina@gmail.com'),
(7, 'Wan Habibah Wan Ibrahim', '080808080808', 'abc@88');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `AttendanceID` int(10) NOT NULL,
  `ActivityID` int(10) DEFAULT NULL,
  `CompetitionID` int(10) DEFAULT NULL,
  `StudentID` int(10) NOT NULL,
  `RegisterStudentID` int(10) NOT NULL,
  `AttendanceStatus` varchar(10) CHARACTER SET latin1 NOT NULL,
  `DateTimeTaken` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`AttendanceID`, `ActivityID`, `CompetitionID`, `StudentID`, `RegisterStudentID`, `AttendanceStatus`, `DateTimeTaken`) VALUES
(112, 28, NULL, 20, 18, 'Present', '23-06-2024'),
(117, 34, NULL, 20, 18, 'Present', '24-06-2023'),
(118, NULL, 15, 20, 18, 'Absent', '20-06-2024'),
(119, NULL, 15, 24, 23, 'Present', '20-06-2024'),
(120, NULL, 15, 11, 24, 'Present', '20-06-2024'),
(122, 34, NULL, 24, 23, 'Present', '24-06-2023'),
(124, NULL, 16, 20, 18, 'Present', '23-06-2024'),
(125, NULL, 16, 24, 23, 'Present', '23-06-2024'),
(127, 35, NULL, 20, 19, 'Present', '24-06-2024'),
(128, 35, NULL, 8, 26, 'Present', '24-06-2024'),
(130, 18, NULL, 4, 27, 'Present', '24-06-2024'),
(131, 18, NULL, 27, 29, 'Present', '24-06-2024'),
(132, 18, NULL, 26, 28, 'Absent', '24-06-2024'),
(133, 35, NULL, 4, 27, 'Present', '24-06-2024'),
(134, 18, NULL, 8, 26, 'Present', '24-06-2024'),
(135, NULL, 17, 4, 27, 'Present', '24-06-2024'),
(136, NULL, 17, 20, 19, 'Present', '24-06-2024'),
(137, NULL, 17, 27, 29, 'Present', '24-06-2024'),
(138, 36, NULL, 8, 26, 'Present', '24-06-2024'),
(139, 36, NULL, 4, 27, 'Present', '24-06-2024'),
(140, 36, NULL, 26, 28, 'Present', '24-06-2024'),
(141, 36, NULL, 27, 29, 'Present', '24-06-2024'),
(142, 37, NULL, 12, 30, 'Present', '24-06-2024'),
(143, 37, NULL, 28, 31, 'Present', '24-06-2024'),
(145, 37, NULL, 29, 33, 'Present', '24-06-2024'),
(146, 38, NULL, 12, 30, 'Absent', '24-06-2024'),
(147, 38, NULL, 28, 31, 'Present', '24-06-2024'),
(148, 38, NULL, 13, 32, 'Present', '24-06-2024'),
(212, 45, NULL, 22, 25, 'Present', '24-06-2024'),
(213, 46, NULL, 22, 25, 'Present', '24-06-2024'),
(214, NULL, 24, 22, 25, 'Absent', '22-07-2024'),
(246, 68, NULL, 20, 18, 'Absent', '13-08-2024'),
(247, 68, NULL, 24, 23, 'Present', '13-08-2024'),
(248, 68, NULL, 22, 25, 'Absent', '13-08-2024'),
(249, NULL, 25, 20, 18, 'Absent', '13-08-2024'),
(250, NULL, 25, 24, 23, 'Absent', '13-08-2024'),
(251, NULL, 25, 22, 25, 'Present', '13-08-2024'),
(259, 69, NULL, 8, 40, 'Absent', '15-08-2024'),
(263, 69, NULL, 20, 41, 'Absent', '15-08-2024'),
(265, NULL, 32, 8, 40, 'Absent', '15-08-2024'),
(266, NULL, 32, 20, 41, 'Absent', '15-08-2024'),
(268, 34, NULL, 4, 42, 'Present', '16-08-2023'),
(270, 70, NULL, 20, 18, 'Present', '16-08-2024'),
(271, 70, NULL, 22, 25, 'Present', '16-08-2024');

-- --------------------------------------------------------

--
-- Table structure for table `club`
--

CREATE TABLE `club` (
  `ClubID` int(10) NOT NULL,
  `ClubName` varchar(50) NOT NULL,
  `ClubTypeID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `club`
--

INSERT INTO `club` (`ClubID`, `ClubName`, `ClubTypeID`) VALUES
(15, 'Persatuan Bulan Sabit Merah (PBSM)', 1),
(17, 'Kelab Sains', 2),
(18, 'Jabatan Pertahanan Awam Malaysia (JPAM)', 1),
(19, 'Kelab Bahasa Melayu', 2),
(20, 'Kelab Sejarah', 2),
(22, 'Kelab Permainan Hoki', 15),
(23, 'Kelab Permainan Badminton', 15),
(24, 'Kelab Permainan Bola Jaring', 15),
(25, 'Persatuan Puteri Islam Malaysia (PPIM)', 1),
(26, 'Kelab Permainan Catur', 15);

-- --------------------------------------------------------

--
-- Table structure for table `clubtype`
--

CREATE TABLE `clubtype` (
  `ClubTypeID` int(10) NOT NULL,
  `ClubTypeName` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clubtype`
--

INSERT INTO `clubtype` (`ClubTypeID`, `ClubTypeName`) VALUES
(1, 'Uniform Club'),
(2, 'Association Club'),
(15, 'Sport Club');

-- --------------------------------------------------------

--
-- Table structure for table `competition`
--

CREATE TABLE `competition` (
  `CompetitionID` int(10) NOT NULL,
  `CompetitionName` varchar(50) NOT NULL,
  `CompetitionDate` varchar(10) NOT NULL,
  `CompetitionMark` int(100) NOT NULL,
  `CompetitionAchievement` varchar(50) DEFAULT NULL,
  `LevelID` int(10) NOT NULL,
  `ClubID` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `competition`
--

INSERT INTO `competition` (`CompetitionID`, `CompetitionName`, `CompetitionDate`, `CompetitionMark`, `CompetitionAchievement`, `LevelID`, `ClubID`) VALUES
(1, 'Pertandingan Pameran Sains Dan Matematik', '09-05-2024', 15, '', 1, 17),
(4, 'Pertandingan Pidato', '09-05-2024', 25, '', 2, 19),
(9, 'Pertandingan Inovasi ', '07-06-2024', 25, '', 1, 17),
(15, 'Pertandingan Pidato', '20-06-2024', 50, '', 1, 20),
(16, 'Pertandingan Pameran Kemerdekaan', '23-06-2024', 30, 'Naib Johan', 2, 20),
(17, 'Pertandingan Kawad Kaki', '23-06-2024', 30, 'Ketiga', 1, 15),
(18, 'Pertandingan Bola Jaring Perempuan Daerah Jasin', '23-06-2024', 35, 'Naib Johan', 2, 24),
(24, 'abc', '21-07-2024', 20, 'abc', 2, 20),
(25, 'def', '22-07-2024', 26, 'def', 7, 20),
(32, 'Pertandingan Badminton MSSN (P)', '15-08-2024', 15, '', 2, 23);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `LevelID` int(10) NOT NULL,
  `LevelType` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`LevelID`, `LevelType`) VALUES
(1, 'Peringkat Sekolah'),
(2, 'Peringkat Daerah'),
(7, 'Peringkat Kebangsaan');

-- --------------------------------------------------------

--
-- Table structure for table `registerstudent`
--

CREATE TABLE `registerstudent` (
  `RegisterStudentID` int(10) NOT NULL,
  `StudentID` int(10) NOT NULL,
  `ClubID` int(10) NOT NULL,
  `CurrentYear` varchar(4) NOT NULL,
  `StudentClass` varchar(20) NOT NULL,
  `StudentFormLevel` varchar(1) NOT NULL,
  `ClubPosition` varchar(20) NOT NULL,
  `TotalMark` int(3) DEFAULT NULL,
  `Grade` varchar(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registerstudent`
--

INSERT INTO `registerstudent` (`RegisterStudentID`, `StudentID`, `ClubID`, `CurrentYear`, `StudentClass`, `StudentFormLevel`, `ClubPosition`, `TotalMark`, `Grade`) VALUES
(18, 20, 20, '2024', '5 As-Syafie', '5', 'AJK Tingkatan 5', 88, 'A'),
(19, 20, 15, '2022', '3 As-Syafie', '3', 'Penolong Bendahari', 82, 'A'),
(23, 24, 20, '2024', '3 Saodah', '3', 'AJK Tingkatan 3', 85, 'A'),
(24, 11, 20, '2023', '4 Al-Khawarizmi', '4', 'Penolong Setiausaha', 90, 'A'),
(25, 22, 20, '2024', '5 Ibnu Sina', '5', 'Ahli Aktif', 92, 'A'),
(26, 8, 15, '2023', '4 Al-Khawarizmi', '4', 'Pengerusi', 56, 'C'),
(27, 4, 15, '2024', '4 Ibnu Sina', '4', 'Naib Pengerusi', 78, 'B'),
(28, 26, 15, '2024', '2 Khadijah', '2', 'AJK Tingkatan 2', 69, 'B'),
(29, 27, 15, '2024', '1 Aisyah', '1', 'Ahli Biasa', 38, 'D'),
(30, 12, 24, '2024', '5 Al-Khawarizmi', '5', 'Bendahari', 45, 'D'),
(31, 28, 24, '2022', '4 Ibnu Sina', '4', 'Naib Pengerusi', 60, 'B'),
(32, 13, 24, '2023', '3 Aisyah', '3', 'AJK Tingkatan 3', 78, 'A'),
(33, 29, 24, '2024', '2 Aisyah', '2', 'AJK Tingkatan 2', 80, 'A'),
(40, 8, 23, '2024', '5 Ibnu Sina', '5', 'Bendahari', 10, 'E'),
(41, 20, 23, '2024', '5 As-Syafie', '5', 'AJK Tingkatan 5', 36, 'D'),
(43, 13, 20, '2023', '4 As-Syafie', '4', 'AJK Tingkatan 4', 44, 'D');

-- --------------------------------------------------------

--
-- Table structure for table `registerteacher`
--

CREATE TABLE `registerteacher` (
  `RegisterTeacherID` int(10) NOT NULL,
  `TeacherID` int(10) NOT NULL,
  `ClubID` int(10) NOT NULL,
  `DateCreated` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `registerteacher`
--

INSERT INTO `registerteacher` (`RegisterTeacherID`, `TeacherID`, `ClubID`, `DateCreated`) VALUES
(44, 1, 20, '23-06-2022'),
(45, 11, 17, '23-06-2022'),
(46, 13, 25, '23-06-2022'),
(47, 10, 18, '23-06-2022'),
(48, 16, 24, '23-06-2022'),
(49, 2, 15, '05-06-2023'),
(50, 36, 22, '05-06-2023'),
(51, 37, 23, '21-05-2024');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` int(10) NOT NULL,
  `StudentName` varchar(50) NOT NULL,
  `StudentIc` varchar(12) NOT NULL,
  `StudentParentName` varchar(50) NOT NULL,
  `StudentParentPhone` varchar(11) NOT NULL,
  `StudentAddress` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `StudentName`, `StudentIc`, `StudentParentName`, `StudentParentPhone`, `StudentAddress`) VALUES
(3, 'Nur Aqilah Humaira Binti Imran', '021017010000', 'Siti Paridah Binti Juhari', '0137779684', 'Fasa 9B, Taman Muhibbah, 77300 Merlimau, Melaka'),
(4, 'Nurul Jannah Binti Rosli', '021012048236', 'Rosli Bin Zan', '0123377452', 'Fasa 8A, Taman Muhibbah, 77300 Merlimau, Melaka'),
(8, 'Ain Afiqah Binti Isa', '020106047288', 'Isa Bin Uthman', '0168826354', 'Taman Harmoni Merlimau Melaka'),
(11, 'Nur Salihah Husna Binti Abdul Rahim', '061111077620', 'Salmah ', '0121474836', 'Lorong Sentol 5, Pulau Pinang'),
(12, 'Nadia Farah Ain Binti Ahmad', '050723047234', 'Ahmad Bin Hasbullah', '0168364429', 'M12 Jalan 5, Taman Ayer Molek Perdana,75460 Ayer Molek, Melaka'),
(13, 'Zahirah Binti Wahid', '050421054836', 'Aminah Binti Zakaria', '0135462291', 'J114, Jalan 2, Taman Pesona, 77300 Merlimau, Melaka'),
(19, 'Nurul Nabilah Binti Imran', '040521010620', 'Imran Bin Mohd Ibrahim', '0137306104', 'Taman Muhibbah Merlimau'),
(20, 'Hidayah Binti Burhannudin', '031006030110', 'Burhannudin Bin Yahya', '01110664992', 'Lot 2077, Taman Zasna, Bandar Baru Jasin, 77300, Melaka'),
(22, 'Nur Fakhirah Binti Abdul Rahman', '020209051273', 'Abdul Rahman', '01374738576', 'Blok C Taman Seri Manja '),
(24, 'Husna Arifah Binti Yuzaimi', '040609018263', 'Yuzaimi Bin Yuzaimi', '01723821635', 'J12, Taman Persona, Jasin, Melaka'),
(26, 'Aliah Najwa Binti Azizi', '030711072873', 'Azizi Bin Salahudin', '01823463543', 'Jalan 15, Sebatu, Batu Gajah, Melaka'),
(27, 'Illya Nasuha Binti Azahan', '020521042182', 'Norlia Binti Fauzi', '01935764443', 'No 2 Jln Pantai siring 6, Serkam Darat, Melaka'),
(28, 'Nur Nadhirah Binti Zamri', '041118073294', 'Zamri Bin Atan', '01392361745', 'No29, Jln Umbai, Melaka'),
(29, 'Khairiyah Binti Idris', '050918072129', 'Idris Bin Harun', '01237483543', 'B1 Jln 3, Tmn Batu Berendam, Melaka'),
(30, 'Durrah Sofiyyah Binti Noraihan', '020809918364', 'Noraihan Bin Abas', '01982334893', '123 abc melaka');

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

CREATE TABLE `teacher` (
  `TeacherID` int(10) NOT NULL,
  `TeacherName` varchar(255) NOT NULL,
  `TeacherIc` varchar(12) NOT NULL,
  `TeacherPhone` varchar(11) NOT NULL,
  `TeacherEmail` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`TeacherID`, `TeacherName`, `TeacherIc`, `TeacherPhone`, `TeacherEmail`) VALUES
(1, 'Faridah Binti Rasdin', '830217093648', '0173537728', 'faridah@gmail.com'),
(2, 'Nabilah Binti Roslan', '950412047096', '01664883574', 'nabilah@gmail.com'),
(10, 'Nurly Binti Amal', '950412047090', '01787427433', 'nurly@gmail.com'),
(11, 'Maznita Binti Maznan', '800923077254', '0163836495', 'maznita@gmail.com'),
(13, 'Sabariah Binti Mahadi', '750216059342', '01485457657', 'sabariah@gmail.com'),
(16, 'Sharidatul Binti Ismail', '690722081273', '01719473264', 'sharidatul@gmail.com'),
(36, 'Siti Nur Adinin Binti Saleh', '920318048348', '01692812321', 'adnin@gmail.com'),
(37, 'Radziah Binti Sarip', '910923061983', '01862327851', 'radziah@gmail.com'),
(38, 'Munirah Binti Jaafar', '760912042937', '01677122356', 'munirah@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`ActivityID`),
  ADD KEY `Club` (`ClubID`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`AttendanceID`),
  ADD KEY `ActivityAt` (`ActivityID`),
  ADD KEY `CompetitionAt` (`CompetitionID`),
  ADD KEY `RegisterStudentID` (`RegisterStudentID`),
  ADD KEY `StudentID` (`StudentID`);

--
-- Indexes for table `club`
--
ALTER TABLE `club`
  ADD PRIMARY KEY (`ClubID`),
  ADD KEY `ClubTypeID` (`ClubTypeID`);

--
-- Indexes for table `clubtype`
--
ALTER TABLE `clubtype`
  ADD PRIMARY KEY (`ClubTypeID`);

--
-- Indexes for table `competition`
--
ALTER TABLE `competition`
  ADD PRIMARY KEY (`CompetitionID`),
  ADD KEY `Level` (`LevelID`),
  ADD KEY `ClubCom` (`ClubID`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`LevelID`);

--
-- Indexes for table `registerstudent`
--
ALTER TABLE `registerstudent`
  ADD PRIMARY KEY (`RegisterStudentID`),
  ADD KEY `StudentID` (`StudentID`),
  ADD KEY `ClubID` (`ClubID`);

--
-- Indexes for table `registerteacher`
--
ALTER TABLE `registerteacher`
  ADD PRIMARY KEY (`RegisterTeacherID`),
  ADD KEY `Teacher_ID` (`TeacherID`),
  ADD KEY `Club_ID` (`ClubID`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`TeacherID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `ActivityID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `AttendanceID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=272;

--
-- AUTO_INCREMENT for table `club`
--
ALTER TABLE `club`
  MODIFY `ClubID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `clubtype`
--
ALTER TABLE `clubtype`
  MODIFY `ClubTypeID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `competition`
--
ALTER TABLE `competition`
  MODIFY `CompetitionID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `LevelID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `registerstudent`
--
ALTER TABLE `registerstudent`
  MODIFY `RegisterStudentID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `registerteacher`
--
ALTER TABLE `registerteacher`
  MODIFY `RegisterTeacherID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `student`
--
ALTER TABLE `student`
  MODIFY `StudentID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `teacher`
--
ALTER TABLE `teacher`
  MODIFY `TeacherID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
