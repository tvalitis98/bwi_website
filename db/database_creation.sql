DROP DATABASE ROBOTS;
CREATE DATABASE ROBOTS;
USE ROBOTS;

CREATE TABLE ROBOT_SESSIONS(
	robot_name VARCHAR(64),
	start_time BIGINT,
	end_time BIGINT,
	PRIMARY KEY (robot_name, start_time)
) ;

/*
INSERT INTO ROBOT_SESSIONS(robot_name, start_time, end_time) VALUES('leela', '1000002', '20000000');
*/
