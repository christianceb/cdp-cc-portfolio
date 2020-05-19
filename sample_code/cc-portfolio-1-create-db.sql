DROP USER IF EXISTS `rad_store_user`@`localhost`;
DROP DATABASE IF EXISTS `rad_store`;

CREATE DATABASE IF NOT EXISTS `rad_store` /*!40100 COLLATE 'utf8mb4_general_ci' */;

CREATE USER IF NOT EXISTS `rad_store_user`@`localhost` IDENTIFIED BY 'Secret1';
GRANT USAGE ON *.* TO 'rad_store_user'@localhost IDENTIFIED BY 'Secret1';
GRANT ALL privileges ON `rad_store`.* TO 'rad_store_user'@localhost;

USE rad_store;

CREATE TABLE IF NOT EXISTS `categories`
(
    `id`          bigint       NOT NULL AUTO_INCREMENT,
    `code`        char(4)      NOT NULL DEFAULT 'UNKN' UNIQUE,
    `name`        varchar(32)  NOT NULL DEFAULT 'ERROR: Unknown',
    `description` varchar(255) NOT NULL DEFAULT 'ERROR: Unknown',
    `created_at`  datetime     NOT NULL DEFAULT NOW(),
    `updated_at`  datetime     NULL ON UPDATE NOW(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 20;

CREATE TABLE IF NOT EXISTS `products`
(
    `id`          bigint         NOT NULL AUTO_INCREMENT,
    `name`        varchar(128)   NOT NULL,
    `description` text           NOT NULL,
    `price`       decimal(10, 2) NOT NULL,
    `category_id` bigint         NOT NULL,
    `created_at`  datetime       NOT NULL,
    `updated_at`  datetime       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  AUTO_INCREMENT = 70;
