CREATE DATABASE IF NOT EXISTS romzDB;
GRANT SELECT, INSERT, UPDATE, DELETE ON romzDB.*
    TO  romzDB_root@localhost 
    IDENTIFIED BY 'romzDB_root';
FLUSH PRIVILEGES;

use romzDB;

CREATE TABLE IF NOT EXISTS users(
    id    INT UNSIGNED NOT NULL AUTO_INCREMENT,
    uname VARCHAR(250) NOT NULL,    
    pword VARCHAR(250) NOT NULL,
    fname VARCHAR(250) NOT NULL,
    mname VARCHAR(250) NOT NULL,
    lname VARCHAR(250) NOT NULL,
    cnum  VARCHAR(50) NOT NULL,
    home_add TEXT NOT NULL,
    email_add VARCHAR(250) NOT NULL,
    PRIMARY KEY(id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS thread (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_created    VARCHAR(255) NOT NULL,
    title           VARCHAR(255) NOT NULL,
    created         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comment (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id       INT UNSIGNED NOT NULL,
    username        VARCHAR(255) NOT NULL,
    body            TEXT NOT NULL,
    created         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX (thread_id, created)
)ENGINE=InnoDB;

