ALTER TABLE `products`
	ADD COLUMN `image` VARCHAR(255) NULL DEFAULT '' AFTER `price`;

ALTER TABLE `products`
	CHANGE COLUMN `category_id` `category_id` BIGINT(20) NOT NULL DEFAULT '0' AFTER `image`;


