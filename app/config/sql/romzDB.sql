CREATE DATABASE IF NOT EXISTS romzDB;
GRANT SELECT, INSERT, UPDATE, DELETE ON romzDB.*
    TO  romzDB_root@localhost 
    IDENTIFIED BY 'romzDB_root';
FLUSH PRIVILEGES;

use romzDB;

CREATE TABLE IF NOT EXISTS users(
    user_id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username        VARCHAR(50) NOT NULL,    
    password        VARCHAR(250) NOT NULL,
    firstname       VARCHAR(50) NOT NULL,
    lastname        VARCHAR(50) NOT NULL,
    email_add       VARCHAR(50) NOT NULL,
    PRIMARY KEY(user_id)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS threads (
    thread_id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id         INT UNSIGNED NOT NULL,
    title           VARCHAR(50) NOT NULL,
    description     TEXT NOT NULL,
    created         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated         DATETIME NOT NULL,
    PRIMARY KEY (thread_id),
    INDEX (user_id, created)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS comments (
    comment_id      INT UNSIGNED NOT NULL AUTO_INCREMENT,
    thread_id       INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NOT NULL,
    body            TEXT NOT NULL,
    created         TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated         DATETIME NOT NULL,
    PRIMARY KEY (comment_id),
    INDEX (thread_id, user_id, created)
)ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS thread_likes (
    thread_id       INT UNSIGNED NOT NULL,
    user_id         INT UNSIGNED NOT NULL,
    like_status     INT UNSIGNED NOT NULL,
    INDEX (thread_id)
)ENGINE=InnoDB;



