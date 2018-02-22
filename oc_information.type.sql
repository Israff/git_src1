ALTER TABLE `oc_information` ADD COLUMN `type` ENUM( 'A', 'V', 'I' ) DEFAULT 'I';
ALTER TABLE `oc_information` ADD INDEX(`type`);
