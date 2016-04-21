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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `user_details`
  ADD PRIMARY KEY (`user_id`);

ALTER TABLE User_Details
  ADD KEY User_Details_User_ID_1 (User_ID);

DROP TABLE IF EXISTS `User_Roles`;
CREATE TABLE `User_Roles` (
  `Role_ID` varchar(1) NOT NULL,
  `Role_Type` varchar(150) NOT NULL,
  PRIMARY KEY (`Role_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `User_Roles` VALUES ('1','ADMIN'), ('2','USER');

ALTER TABLE User_Roles
  ADD KEY User_Roles_1 (Role_ID);

DROP TABLE IF EXISTS `User_Role_Map`;
CREATE TABLE `User_Role_Map` (
  `user_id` varchar(255) NOT NULL,
  `Role_ID` varchar(1) NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE User_Role_Map
  ADD KEY User_Role_Map_User_ID_1 (User_ID),
  ADD KEY User_Role_Map_Role_ID_1 (Role_ID);

ALTER TABLE User_Roles
  ADD KEY User_Roles_Role_ID_1 (Role_ID);

ALTER TABLE User_Role_Map
  ADD CONSTRAINT User_Role_Map_fk_1 FOREIGN KEY (User_ID) REFERENCES User_Details (User_ID),
  ADD CONSTRAINT User_Role_Map_fk_2 FOREIGN KEY (Role_ID) REFERENCES User_Roles (Role_ID);