-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 07, 2026
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
-- Database: `elem_enrollment_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
-- Simplified guardian/admin accounts with minimal information
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `role` enum('admin','guardian') NOT NULL DEFAULT 'guardian',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users` (Default Admin Account)
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `contact_number`, `role`, `is_active`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@besems.com', '09123456789', 'admin', 1);
-- Default password is: password

-- --------------------------------------------------------

--
-- Table structure for table `grade_levels`
--

CREATE TABLE `grade_levels` (
  `grade_id` int(11) NOT NULL,
  `grade_name` varchar(20) NOT NULL,
  `grade_level` tinyint(1) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grade_levels`
--

INSERT INTO `grade_levels` (`grade_id`, `grade_name`, `grade_level`, `is_active`) VALUES
(1, 'Grade 1', 1, 1),
(2, 'Grade 2', 2, 1),
(3, 'Grade 3', 3, 1),
(4, 'Grade 4', 4, 1),
(5, 'Grade 5', 5, 1),
(6, 'Grade 6', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `section_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_name` varchar(50) NOT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `max_students` int(11) DEFAULT 40,
  `adviser_name` varchar(100) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
-- Complete student information including personal and family details
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `guardian_id` int(11) NOT NULL,
  `lrn` varchar(12) NOT NULL,
  
  -- Personal Information
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `date_of_birth` date NOT NULL,
  `place_of_birth` varchar(200) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  
  -- Additional Information
  `mother_tongue` varchar(50) DEFAULT NULL,
  `religion` varchar(50) DEFAULT NULL,
  `indigenous_people` varchar(50) DEFAULT NULL,
  
  -- Address Information
  `house_number` varchar(50) DEFAULT NULL,
  `street_name` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) NOT NULL,
  `city_municipality` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  
  -- Parent/Guardian Information
  `father_name` varchar(200) DEFAULT NULL,
  `father_occupation` varchar(100) DEFAULT NULL,
  `father_contact` varchar(15) DEFAULT NULL,
  `mother_name` varchar(200) DEFAULT NULL,
  `mother_occupation` varchar(100) DEFAULT NULL,
  `mother_contact` varchar(15) DEFAULT NULL,
  `guardian_name` varchar(200) DEFAULT NULL,
  `guardian_relationship` enum('Parent','Legal Guardian','Grandparent','Aunt/Uncle','Sibling','Other') NOT NULL,
  `guardian_occupation` varchar(100) DEFAULT NULL,
  
  -- School Information
  `assigned_section_id` int(11) DEFAULT NULL,
  `enrollment_type` enum('New','Continuing','Transferee','Returnee') DEFAULT 'New',
  `previous_school` varchar(200) DEFAULT NULL,
  `previous_grade_level` varchar(20) DEFAULT NULL,
  `student_status` enum('Active','Inactive','Transferred','Graduated','Dropped') DEFAULT 'Active',
  
  -- Timestamps
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `student_requirements`
--

CREATE TABLE `student_requirements` (
  `requirement_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  
  -- Required Documents (DepEd Standard)
  `birth_certificate` tinyint(1) DEFAULT 0,
  `report_card_form137` tinyint(1) DEFAULT 0,
  `good_moral_certificate` tinyint(1) DEFAULT 0,
  `certificate_of_completion` tinyint(1) DEFAULT 0,
  `id_picture_2x2` tinyint(1) DEFAULT 0,
  
  -- Additional Documents
  `transfer_credential` tinyint(1) DEFAULT 0,
  `medical_certificate` tinyint(1) DEFAULT 0,
  
  -- Enrollment Status
  `enrollment_status` enum('Pending','For Review','Approved','Declined','Incomplete') DEFAULT 'Pending',
  `remarks` text DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  
  -- Tracking
  `submitted_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `reviewed_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `subject_code` varchar(20) DEFAULT NULL,
  `subject_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `units` decimal(3,1) DEFAULT 1.0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `teacher_name` varchar(100) DEFAULT NULL,
  `room_number` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `announcement_type` enum('General','Enrollment','Event','Holiday','Emergency') DEFAULT 'General',
  `target_audience` enum('All','Guardians','Admins','Specific Grade') DEFAULT 'All',
  `target_grade_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 0,
  `publish_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_history`
-- Track student enrollment across school years
--

CREATE TABLE `enrollment_history` (
  `history_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `school_year` varchar(20) NOT NULL,
  `grade_id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `enrollment_date` date NOT NULL,
  `enrollment_status` enum('Enrolled','Completed','Dropped','Transferred') DEFAULT 'Enrolled',
  `general_average` decimal(5,2) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
-- For audit trail
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action_type` varchar(50) NOT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `record_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_is_active` (`is_active`);

ALTER TABLE `grade_levels`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `grade_name` (`grade_name`),
  ADD UNIQUE KEY `grade_level` (`grade_level`);

ALTER TABLE `sections`
  ADD PRIMARY KEY (`section_id`),
  ADD KEY `grade_id` (`grade_id`),
  ADD KEY `idx_school_year` (`school_year`),
  ADD KEY `idx_is_active` (`is_active`);

ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `lrn` (`lrn`),
  ADD KEY `guardian_id` (`guardian_id`),
  ADD KEY `assigned_section_id` (`assigned_section_id`),
  ADD KEY `idx_last_name` (`last_name`),
  ADD KEY `idx_status` (`student_status`),
  ADD KEY `idx_barangay` (`barangay`),
  ADD KEY `idx_city` (`city_municipality`);

ALTER TABLE `student_requirements`
  ADD PRIMARY KEY (`requirement_id`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD KEY `reviewed_by` (`reviewed_by`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_enrollment_status` (`enrollment_status`);

ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`),
  ADD KEY `grade_id` (`grade_id`),
  ADD KEY `idx_subject_code` (`subject_code`);

ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `idx_day` (`day_of_week`);

ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `target_grade_id` (`target_grade_id`),
  ADD KEY `idx_published` (`is_published`),
  ADD KEY `idx_type` (`announcement_type`);

ALTER TABLE `enrollment_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `grade_id` (`grade_id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `idx_school_year` (`school_year`);

ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_action` (`action_type`),
  ADD KEY `idx_table` (`table_name`),
  ADD KEY `idx_created` (`created_at`);

--
-- AUTO_INCREMENT for dumped tables
--

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `grade_levels`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `sections`
  MODIFY `section_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `student_requirements`
  MODIFY `requirement_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `enrollment_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

ALTER TABLE `sections`
  ADD CONSTRAINT `sections_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade_levels` (`grade_id`) ON DELETE CASCADE;

ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`guardian_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`assigned_section_id`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL;

ALTER TABLE `student_requirements`
  ADD CONSTRAINT `student_requirements_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_requirements_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `student_requirements_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grade_levels` (`grade_id`) ON DELETE CASCADE;

ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`) ON DELETE CASCADE;

ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`target_grade_id`) REFERENCES `grade_levels` (`grade_id`) ON DELETE SET NULL;

ALTER TABLE `enrollment_history`
  ADD CONSTRAINT `enrollment_history_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `enrollment_history_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grade_levels` (`grade_id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `enrollment_history_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `sections` (`section_id`) ON DELETE SET NULL;

ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;