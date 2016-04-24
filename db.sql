-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2016 at 06:29 AM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 7.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `final_project`
--

DROP TABLE IF EXISTS `User_details`;
CREATE TABLE `user_details` (
  `user_id` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `first_name` varchar(35) NOT NULL,
  `middle_name` varchar(35) DEFAULT NULL,
  `last_name` varchar(35) NOT NULL,
  `contact_no` varchar(100) DEFAULT NULL,
  `created_on` varchar(255) NOT NULL,
  `status` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE User_Details
  ADD KEY User_Details_User_ID_1 (User_ID);

DROP TABLE IF EXISTS `User_Roles`;
CREATE TABLE `User_Roles` (
  `Role_ID` varchar(1) NOT NULL,
  `Role_Type` varchar(150) NOT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `User_Roles` VALUES ('1','ADMIN'), ('2','USER');

ALTER TABLE User_Roles
  ADD KEY User_Roles_1 (Role_ID);

DROP TABLE IF EXISTS `User_Role_Map`;
CREATE TABLE `User_Role_Map` (
  `user_id` varchar(255) NOT NULL,
  `Role_ID` varchar(1) NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE User_Role_Map
  ADD KEY User_Role_Map_User_ID_1 (User_ID),
  ADD KEY User_Role_Map_Role_ID_1 (Role_ID);

ALTER TABLE User_Roles
  ADD KEY User_Roles_Role_ID_1 (Role_ID);

ALTER TABLE User_Role_Map
  ADD CONSTRAINT User_Role_Map_fk_1 FOREIGN KEY (User_ID) REFERENCES User_Details (User_ID),
  ADD CONSTRAINT User_Role_Map_fk_2 FOREIGN KEY (Role_ID) REFERENCES User_Roles (Role_ID);

-- --------------------------------------------------------

--
-- Table structure for table `main_category`
--

CREATE TABLE `main_category` (
  `Cat_id` int(11) NOT NULL,
  `Category` text NOT NULL,
   PRIMARY KEY (`Cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_category`
--

INSERT INTO `main_category` (`Cat_id`, `Category`) VALUES
(1, 'Housing'),
(2, 'Books'),
(3, 'Personal stuff'),
(4, 'Barter');

-- --------------------------------------------------------
CREATE TABLE `sub_category` (
  `sub_id` int(11) NOT NULL,
  `sub_category` text NOT NULL,
  PRIMARY KEY (`sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `main_category`
--

INSERT INTO `sub_category` (`sub_id`, `sub_category`) VALUES
(1, 'for rent'),
(2, 'for sale'),
(3, 'electronics'),
(4, 'furniture'),
(5, 'vehicle'),
(6, 'other');
-- --------------------------------------------------------

--
-- Table structure for table `sub_category`
--

CREATE TABLE `sub_category_map` (
  `sub_cat_map_id` int(11) NOT NULL,
  `Cat_id` int(11) NOT NULL,
  `sub_category_id` int(11) NOT NULL,
  PRIMARY KEY (`sub_cat_map_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE main_category
  ADD KEY main_category_1 (Cat_id);

ALTER TABLE sub_category
  ADD KEY sub_category_1 (sub_id);

ALTER TABLE sub_category_map
  ADD KEY sub_category_map_1 (Cat_id),
  ADD KEY sub_category_map_2 (sub_category_id);

ALTER TABLE sub_category_map
  ADD CONSTRAINT sub_category_map_fk_1 FOREIGN KEY (Cat_id) REFERENCES main_category (Cat_id),
  ADD CONSTRAINT sub_category_map_fk_2 FOREIGN KEY (sub_category_id) REFERENCES sub_category (sub_id);

--
-- Dumping data for table `sub_category`
--

INSERT INTO `sub_category_map` (`sub_cat_map_id`, `Cat_id`, `sub_category_id`) VALUES
(1001, 1, 1),
(1002, 1, 2),
(1003, 2, 1),
(1004, 2, 2),
(1005, 3, 3),
(1006, 3, 4),
(1007, 3, 5),
(1008, 3, 6),
(1009, 4, 3),
(1010, 4, 4),
(1011, 4, 5),
(1012, 4, 6);

-- --------------------------------------------------------

--
-- Table structure for table `ad`
--

CREATE TABLE `ad` (
  `Ad_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `posting_title` text NOT NULL,
  `posting_body` text NOT NULL,
  `contact_info` varchar(255) NOT NULL,
  `Cat_id` int(11) NOT NULL,
  `sub_cat_id` int(11) NOT NULL,
  PRIMARY KEY (`Ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

create table ad_pref_map (
  `Ad_id` varchar(255) NOT NULL,
  `pref_id` varchar(255) NOT NULL
);

ALTER TABLE ad
  ADD KEY ad_1 (ad_id),
  ADD KEY ad_2 (user_id),
  ADD KEY ad_3 (Cat_id),
  ADD KEY ad_4 (sub_cat_id);

ALTER TABLE ad
  ADD CONSTRAINT ad_fk_1 FOREIGN KEY (user_id) REFERENCES user_details (user_id),
  ADD CONSTRAINT ad_fk_2 FOREIGN KEY (Cat_id) REFERENCES main_category (Cat_id),
  ADD CONSTRAINT ad_fk_3 FOREIGN KEY (sub_cat_id) REFERENCES sub_category (sub_id);

ALTER TABLE ad_pref_map
  ADD KEY ad_pref_map_1 (ad_id);

ALTER TABLE ad_pref_map
  ADD CONSTRAINT ad_pref_map_fk_1 FOREIGN KEY (ad_id) REFERENCES ad (ad_id);

-- --------------------------------------------------------

--
-- Table structure for table `barter_for_electronics`
--

CREATE TABLE `barter_for_electronics` (
  `barter_pref_id` varchar(255) NOT NULL,
  `make / manufacturer` varchar(255) NOT NULL,
  `model name / number` varchar(255) NOT NULL,
  `size / dimensions` varchar(255) NOT NULL,
  `condition` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `barter_for_furniture`
--

CREATE TABLE `barter_for_furniture` (
  `furniture_pref_id` varchar(255) NOT NULL,
  `make / manufacturer` varchar(255) NOT NULL,
  `model name / number` varchar(255) NOT NULL,
  `size / dimensions` varchar(255) NOT NULL,
  `condition` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_for_rent`
--

CREATE TABLE `b_for_rent` (
  `B_rent_pref_id` varchar(255) NOT NULL,
  `Size/dimensions` varchar(255) NOT NULL,
  `Condition` varchar(255) NOT NULL,
  `Language` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_for_sale`
--

CREATE TABLE `b_for_sale` (
  `B_sale_pref_id` varchar(255) NOT NULL,
  `Size/dimensions` varchar(255) NOT NULL,
  `Condition` varchar(255) NOT NULL,
  `Language` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_others`
--

CREATE TABLE `b_others` (
  `Barter_others_id` varchar(255) NOT NULL,
  `posting_title` text NOT NULL,
  `location` text NOT NULL,
  `zip_code` text NOT NULL,
  `posting_body` text NOT NULL,
  `price` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `b_vehicle`
--

CREATE TABLE `b_vehicle` (
  `Barter_vehicle_id` varchar(255) NOT NULL,
  `model_type` text NOT NULL,
  `model_year` text NOT NULL,
  `VIN` text NOT NULL,
  `odometer` text NOT NULL,
  `condition` text NOT NULL,
  `cylinder` text NOT NULL,
  `drive` text NOT NULL,
  `fuel` text NOT NULL,
  `paint_color` text NOT NULL,
  `size` text NOT NULL,
  `transmission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `h_for_rent`
--

CREATE TABLE `h_for_rent` (
  `H_rent_pref_id` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `ft^2` varchar(255) NOT NULL,
  `rent` varchar(255) NOT NULL,
  `available on` varchar(255) NOT NULL,
  `bedroom` varchar(255) NOT NULL,
  `bathroom` varchar(255) NOT NULL,
  `laundry` varchar(255) NOT NULL,
  `parking` varchar(255) NOT NULL,
  `pets` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `h_for_sale`
--

CREATE TABLE `h_for_sale` (
  `H_sale_pref_id` varchar(255) NOT NULL,
  `s_location` text NOT NULL,
  `s_zip_code` varchar(255) NOT NULL,
  `ft^2` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `available_on` varchar(255) NOT NULL,
  `bedroom` varchar(255) NOT NULL,
  `bathroom` varchar(255) NOT NULL,
  `housing_type` varchar(255) NOT NULL,
  `laundry` varchar(255) NOT NULL,
  `parking` varchar(255) NOT NULL,
  `furnished` varchar(255) NOT NULL,
  `no_smoking` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



--
-- Table structure for table `p_for_electronics`
--

CREATE TABLE `p_for_electronics` (
  `p_pref_id` varchar(255) NOT NULL,
  `make / manufacturer` varchar(255) NOT NULL,
  `model name / number` varchar(255) NOT NULL,
  `size / dimensions` varchar(255) NOT NULL,
  `device_condition` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `p_for_furniture`
--

CREATE TABLE `p_for_furniture` (
  `p_furniture_pref_id` varchar(255) NOT NULL,
  `make / manufacturer` varchar(255) NOT NULL,
  `model name / number` varchar(255) NOT NULL,
  `size / dimensions` varchar(255) NOT NULL,
  `device_condition` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `p_others`
--

CREATE TABLE `p_others` (
  `P_others_id` varchar(255) NOT NULL,
  `posting_title` text NOT NULL,
  `location` text NOT NULL,
  `zip_code` text NOT NULL,
  `posting_body` text NOT NULL,
  `price` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `p_vehicle`
--

CREATE TABLE `p_vehicle` (
  `P_vehicle_id` varchar(255) NOT NULL,
  `vehicle_type` text NOT NULL,
  `model_year` text NOT NULL,
  `VIN` text NOT NULL,
  `odometer` text NOT NULL,
  `condition` text NOT NULL,
  `cylinder` text NOT NULL,
  `drive` text NOT NULL,
  `fuel` text NOT NULL,
  `paint_color` text NOT NULL,
  `size` text NOT NULL,
  `transmission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


--
-- Indexes for table `b_for_rent`
--
ALTER TABLE `b_for_rent`
  ADD PRIMARY KEY (`B_rent_pref_id`);

--
-- Indexes for table `b_for_sale`
--
ALTER TABLE `b_for_sale`
  ADD PRIMARY KEY (`B_sale_pref_id`);

--
-- Indexes for table `b_others`
--
ALTER TABLE `b_others`
  ADD PRIMARY KEY (`Barter_others_id`);

--
-- Indexes for table `b_vehicle`
--
ALTER TABLE `b_vehicle`
  ADD PRIMARY KEY (`Barter_vehicle_id`);

--
-- Indexes for table `h_for_sale`
--
ALTER TABLE `h_for_sale`
  ADD PRIMARY KEY (`H_sale_pref_id`);

--
-- Indexes for table `p_others`
--
ALTER TABLE `p_others`
  ADD PRIMARY KEY (`P_others_id`);

--
-- Indexes for table `p_vehicle`
--
ALTER TABLE `p_vehicle`
  ADD PRIMARY KEY (`P_vehicle_id`);


