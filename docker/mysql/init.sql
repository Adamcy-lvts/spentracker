-- Initialize the spentracker database
CREATE DATABASE IF NOT EXISTS spentracker;
CREATE USER IF NOT EXISTS 'spentracker_user'@'%' IDENTIFIED BY 'spentracker_password';
GRANT ALL PRIVILEGES ON spentracker.* TO 'spentracker_user'@'%';
FLUSH PRIVILEGES;