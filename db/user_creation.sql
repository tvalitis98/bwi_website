DROP USER 'webuser'@'localhost';

CREATE USER 'webuser'@'localhost' IDENTIFIED BY 'i_am_a_web_user';
GRANT ALL PRIVILEGES ON * . * TO 'webuser'@'localhost';
