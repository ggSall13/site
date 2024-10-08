CREATE DATABASE `site.loc`

CREATE TABLE `site.loc`.`users` 
(`id` INT NOT NULL AUTO_INCREMENT , 
`name` VARCHAR(150) NOT NULL , 
`email` VARCHAR(150) NOT NULL , 
`phone` VARCHAR(20) NOT NULL , 
`password` VARCHAR(255) NOT NULL , 
`created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , 
PRIMARY KEY (`id`), UNIQUE (`email`), UNIQUE (`phone`)) ENGINE = InnoDB;


CREATE TABLE `site.loc`.`categories` 
(`id` INT NOT NULL AUTO_INCREMENT , 
`categoryName` VARCHAR(50) NOT NULL , 
PRIMARY KEY (`id`), UNIQUE (`categoryName`)) ENGINE = InnoDB;


CREATE TABLE `site.loc`.`ads` 
(`id` INT NOT NULL AUTO_INCREMENT , 
`categoryId` INT NOT NULL , 
`title` VARCHAR(255) NOT NULL , 
`description` TEXT NULL DEFAULT NULL , 
`createdAt` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`adSlug` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;


CREATE TABLE `site.loc`.`images`
(  `id` INT NOT NULL AUTO_INCREMENT,
   `adId` INT NOT NULL,
   `path` VARCHAR(255) NOT NULL,
   PRIMARY KEY (`id`)) ENGINE = InnoDB

ALTER TABLE `images` ADD FOREIGN KEY (`adId`) REFERENCES `ads`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;

ALTER TABLE `ads` ADD `userId` INT NOT NULL AFTER `categoryId`;
ALTER TABLE `ads` ADD FOREIGN KEY (`userId`) REFERENCES `users`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE `categories` ADD `slug` VARCHAR(255) NOT NULL AFTER `dirPath`;
ALTER TABLE `ads` ADD `price` VARCHAR(20) NOT NULL AFTER `description`;
ALTER TABLE `users` ADD `userSlug` VARCHAR(255) NOT NULL AFTER `created_at`;

CREATE TABLE `site.loc`.`subCategories` (
   `id` INT NOT NULL AUTO_INCREMENT , 
   `parentCategoryId` INT NOT NULL , 
   `subCategoryName` VARCHAR(255) NOT NULL , 
   `subCategorySlug` VARCHAR(255) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;

ALTER TABLE `subCategories` ADD FOREIGN KEY (`parentCategoryId`) REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `ads` CHANGE `categoryId` `categorySlug` VARCHAR(255) NOT NULL;
ALTER TABLE `ads` ADD `parentCategoryId` INT NOT NULL AFTER `parentCategoryId`;
ALTER TABLE `ads` ADD FOREIGN KEY (`parentCategoryId`) REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
ALTER TABLE `ads` ADD INDEX(`price`);
ALTER TABLE `ads` CHANGE `price` `price` INT NOT NULL;
