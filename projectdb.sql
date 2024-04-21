-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 14, 2024 at 04:21 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projectDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_role`
--

CREATE TABLE `access_role` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `access_role`
--

INSERT INTO `access_role` (`role_id`, `user_id`) VALUES
(1, 10),
(2, 7),
(3, 9),
(3, 8),
(4, 1),
(4, 3),
(4, 4),
(4, 2),
(4, 9),
(4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `CourseID` varchar(50) NOT NULL,
  `StudentID` varchar(50) NOT NULL,
  `SectionID` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`CourseID`, `StudentID`, `SectionID`, `user_id`) VALUES
('COMP5201', '1', '6000', 7),
('COMP5261', '2', '6000', 7),
('COMP5531', '3', '7000', 7);

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `CourseID` varchar(8) NOT NULL,
  `Course_name` varchar(50) DEFAULT NULL,
  `course_desc` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`CourseID`, `Course_name`, `course_desc`) VALUES
('COMP5201', 'Computer organization and assembly language', 'Programming in a subset of a suitably chosen assembly language; instruction-set level view of computers; translation of sample high-level language constructs to the instruction-set level.'),
('COMP5261', 'Computer Architecture', 'Computer architecture models: control-flow and data-flow.'),
('COMP5531', 'database', 'database course: sql/erdiagram/schema'),
('COMP5541', 'software engineering', 'agile process , requierement gathering , qa etc.. '),
('ENCS6721', 'TECHNICAL WRITING', 'technical writing');

-- --------------------------------------------------------

--
-- Table structure for table `course_materials`
--

CREATE TABLE `course_materials` (
  `id` int(11) NOT NULL,
  `material_name` varchar(255) NOT NULL,
  `material_type` varchar(100) NOT NULL,
  `material_data` longblob NOT NULL,
  `professor_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_materials`
--

INSERT INTO `course_materials` (`id`, `material_name`, `material_type`, `material_data`, `professor_id`, `created_at`) VALUES
(1, 'samplecoursematerial.txt', 'text/plain', 0x7b5c727466315c616e73695c616e7369637067313235325c636f636f61727466323633390a5c636f636f61746578747363616c696e67305c636f636f61706c6174666f726d307b5c666f6e7474626c5c66305c6673776973735c6663686172736574302048656c7665746963613b7d0a7b5c636f6c6f7274626c3b5c7265643235355c677265656e3235355c626c75653235353b7d0a7b5c2a5c657870616e646564636f6c6f7274626c3b3b7d0a5c6d6172676c313434305c6d61726772313434305c766965777731313532305c7669657768383430305c766965776b696e64300a5c706172645c74783536365c7478313133335c7478313730305c7478323236375c7478323833345c7478333430315c7478333936385c7478343533355c7478353130325c7478353636395c7478363233365c7478363830335c7061726469726e61747572616c5c7061727469676874656e666163746f72300a0a5c66305c66733234205c6366302053616d706c6520636f75727365206d6174657269616c2066696c657d, 1, '2024-04-13 19:11:01'),
(2, 'samplecoursematerial.txt', 'text/plain', 0x7b5c727466315c616e73695c616e7369637067313235325c636f636f61727466323633390a5c636f636f61746578747363616c696e67305c636f636f61706c6174666f726d307b5c666f6e7474626c5c66305c6673776973735c6663686172736574302048656c7665746963613b7d0a7b5c636f6c6f7274626c3b5c7265643235355c677265656e3235355c626c75653235353b7d0a7b5c2a5c657870616e646564636f6c6f7274626c3b3b7d0a5c6d6172676c313434305c6d61726772313434305c766965777731313532305c7669657768383430305c766965776b696e64300a5c706172645c74783536365c7478313133335c7478313730305c7478323236375c7478323833345c7478333430315c7478333936385c7478343533355c7478353130325c7478353636395c7478363233365c7478363830335c7061726469726e61747572616c5c7061727469676874656e666163746f72300a0a5c66305c66733234205c6366302053616d706c6520636f75727365206d6174657269616c2066696c657d, 1, '2024-04-13 19:14:09'),
(3, 'samplecoursematerial.txt', 'text/plain', 0x7b5c727466315c616e73695c616e7369637067313235325c636f636f61727466323633390a5c636f636f61746578747363616c696e67305c636f636f61706c6174666f726d307b5c666f6e7474626c5c66305c6673776973735c6663686172736574302048656c7665746963613b7d0a7b5c636f6c6f7274626c3b5c7265643235355c677265656e3235355c626c75653235353b7d0a7b5c2a5c657870616e646564636f6c6f7274626c3b3b7d0a5c6d6172676c313434305c6d61726772313434305c766965777731313532305c7669657768383430305c766965776b696e64300a5c706172645c74783536365c7478313133335c7478313730305c7478323236375c7478323833345c7478333430315c7478333936385c7478343533355c7478353130325c7478353636395c7478363233365c7478363830335c7061726469726e61747572616c5c7061727469676874656e666163746f72300a0a5c66305c66733234205c6366302053616d706c6520636f75727365206d6174657269616c2066696c657d, 7, '2024-04-13 19:25:15');

-- --------------------------------------------------------

--
-- Table structure for table `group_of_course`
--

CREATE TABLE `group_of_course` (
  `group_id` int(11) NOT NULL,
  `course_id` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_of_course`
--

INSERT INTO `group_of_course` (`group_id`, `course_id`) VALUES
(10000, 'COMP5201'),
(10001, 'COMP5201');

-- --------------------------------------------------------

--
-- Table structure for table `member_of_group`
--

CREATE TABLE `member_of_group` (
  `student_id` varchar(8) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_of_group`
--

INSERT INTO `member_of_group` (`student_id`, `group_id`) VALUES
('feph1', 10000),
('bota2', 10001);

-- --------------------------------------------------------

--
-- Table structure for table `professor`
--

CREATE TABLE `professor` (
  `ProfID` varchar(8) NOT NULL,
  `userID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `professor`
--

INSERT INTO `professor` (`ProfID`, `userID`) VALUES
('bide7', 7);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(1, 'administrator'),
(2, 'professor'),
(3, 'teaching assistant'),
(4, 'course student');

-- --------------------------------------------------------

--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `SectionID` varchar(9) NOT NULL,
  `CourseID` varchar(8) NOT NULL,
  `Term` varchar(10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `prof_id` varchar(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`SectionID`, `CourseID`, `Term`, `start_date`, `end_date`, `prof_id`) VALUES
('6000', 'COMP5201', '2', '2024-02-01', '2024-07-06', 'bide7');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `StudentID` varchar(8) NOT NULL,
  `user_id` int(12) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`StudentID`, `user_id`) VALUES
('feph1', 1),
('bota2', 2),
('moba3', 3),
('niha4', 4);

-- --------------------------------------------------------

--
-- Table structure for table `student_groups`
--

CREATE TABLE `student_groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(30) NOT NULL,
  `group_leader_sid` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_groups`
--

INSERT INTO `student_groups` (`group_id`, `group_name`, `group_leader_sid`) VALUES
(10000, 'Group 1', 'feph1'),
(10001, 'Group 2', 'bota2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `username` varchar(12) DEFAULT NULL,
  `password` varchar(12) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `first_name`, `last_name`, `username`, `password`, `email`) VALUES
(1, 'felix-an', 'pham ponton', 'feph1', 'NroASE%r0fz', 'felix@email.com'),
(2, 'bogdan', 'tarasenko', 'bota2', 'L7t6GyKTjKO', 'bogdan@email.com'),
(3, 'mohsen ', 'bahaeddini', 'moba3', 'DTVVA00cmRE', 'mohsen@email.com'),
(4, 'nithin', 'harikrishnan', 'niha4', 'ZXRBlTXp0@6', 'nithin@email.com'),
(7, 'bipin', 'desai', 'bide7', 'hcs0d+vQJZ#', 'bipin@email.com'),
(8, 'yan', 'xu ', 'yaxu8', '1U_s43+w+uF', 'yan@email.com'),
(9, 'ritika', 'dhamija', 'ridh9', '0Z76tcHchvK', 'ritika@email.com'),
(10, 'ADMIN', 'ADMIN', 'ADMIN', 'admin', 'admin@admin.admin'),
(15, 'Albert', 'Einsein', 'AlEi15', '4Hj26Y0SaNu', NULL),
(16, 'Paul', 'ramsay', NULL, NULL, 'paul@ramsay.com'),
(17, 'hugo', 'nam', NULL, NULL, 'hugo@nam.com'),
(18, 'eva', 'loan', NULL, NULL, 'eva@loan'),
(19, 'admin1', 'test', NULL, NULL, 'admin2@test'),
(20, 'lucie ', 'ponton', NULL, NULL, 'lucie@ponton'),
(21, 'test2', 'test', NULL, NULL, 'test@test'),
(22, 'test3', 'test3', NULL, NULL, 'test@test2'),
(24, 'allo', 'alol', NULL, NULL, 'sdadasd');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_role`
--
ALTER TABLE `access_role`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_accessrole_roleid` (`role_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`CourseID`,`StudentID`,`SectionID`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`CourseID`);

--
-- Indexes for table `course_materials`
--
ALTER TABLE `course_materials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group_of_course`
--
ALTER TABLE `group_of_course`
  ADD KEY `group_id` (`group_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `member_of_group`
--
ALTER TABLE `member_of_group`
  ADD KEY `student_id` (`student_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `professor`
--
ALTER TABLE `professor`
  ADD PRIMARY KEY (`ProfID`),
  ADD KEY `Fk_profuser` (`userID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`SectionID`,`CourseID`),
  ADD KEY `FK_section_course` (`CourseID`),
  ADD KEY `fk_section_profid` (`prof_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`StudentID`),
  ADD KEY `fk_student_userid` (`user_id`);

--
-- Indexes for table `student_groups`
--
ALTER TABLE `student_groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_materials`
--
ALTER TABLE `course_materials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `student_groups`
--
ALTER TABLE `student_groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10002;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `access_role`
--
ALTER TABLE `access_role`
  ADD CONSTRAINT `access_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`),
  ADD CONSTRAINT `fk_accessrole_roleid` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);

--
-- Constraints for table `group_of_course`
--
ALTER TABLE `group_of_course`
  ADD CONSTRAINT `group_of_course_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `student_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `group_of_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`CourseID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `member_of_group`
--
ALTER TABLE `member_of_group`
  ADD CONSTRAINT `member_of_group_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `student` (`StudentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_of_group_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `student_groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `professor`
--
ALTER TABLE `professor`
  ADD CONSTRAINT `Fk_profuser` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `section`
--
ALTER TABLE `section`
  ADD CONSTRAINT `FK_section_course` FOREIGN KEY (`CourseID`) REFERENCES `course` (`CourseID`),
  ADD CONSTRAINT `fk_section_profid` FOREIGN KEY (`prof_id`) REFERENCES `professor` (`ProfID`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
