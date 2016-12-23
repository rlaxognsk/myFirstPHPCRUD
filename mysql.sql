DROP DATABASE forum;

CREATE DATABASE forum;

USE forum;

CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_id` VARCHAR( 20 ) NOT NULL,
    `user_pass` CHAR( 100 ) NOT NULL,
    `user_grant` VARCHAR( 20 ) NOT NULL DEFAULT 'user',
    `user_email` VARCHAR( 30 ) NOT NULL
) ENGINE = INNODB;

INSERT INTO users VALUES ( '', 'example', SHA1( 'password' ), 'admin', 'example@example.example' );

CREATE TABLE `boards` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `board_name` VARCHAR( 20 ) NOT NULL,
    `board_latest_number` INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY( `id` )
) ENGINE = INNODB;

INSERT INTO boards ( board_name ) VALUES ( 'main' );

CREATE TABLE `articles` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `board_name` VARCHAR( 20 ) NOT NULL,
    `board_number` INT UNSIGNED NOT NULL,
    `article_title` VARCHAR( 50 ) NOT NULL,
    `article_comment` INT UNSIGNED NOT NULL DEFAULT 0,
    `article_writer` VARCHAR( 20 ) NOT NULL,
    `article_date` DATE NOT NULL,
    `article_hits` INT UNSIGNED NOT NULL DEFAULT 0,
    `article_text` TEXT
) ENGINE = INNODB;

CREATE TABLE `comments` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `parent_article` INT UNSIGNED NOT NULL,
    `comment_writer` VARCHAR( 20 ) NOT NULL,
    `comment_date` DATE NOT NULL,
    `comment_text` TEXT NOT NULL,
    FOREIGN KEY ( `parent_article` ) REFERENCES articles( `id` ) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE USER forum@localhost identified by 'password';
GRANT SELECT, INSERT, UPDATE, DELETE, ALTER ON forum.* TO forum@localhost;