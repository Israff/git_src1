ALTER TABLE `oc_information` ADD COLUMN `type` ENUM( 'A', 'V', 'I' ) DEFAULT 'I';
ALTER TABLE `oc_information` ADD INDEX(`type`);

ALTER TABLE `oc_information` CHANGE COLUMN `type` `type` ENUM('I','A','V','S') DEFAULT 'I';


ALTER TABLE `oc_information` ADD COLUMN `date_added` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;