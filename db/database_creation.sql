DROP DATABASE ROBOTS;
CREATE DATABASE ROBOTS;
USE ROBOTS;

CREATE TABLE ROBOT_SESSIONS(
	robot_name VARCHAR(64),
	start_time BIGINT,
	end_time BIGINT,
	PRIMARY KEY (robot_name, start_time)
) ;

CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ;
/*
INSERT INTO ROBOT_SESSIONS(robot_name, start_time, end_time) VALUES('leela', '1000002', '20000000');
*/
