UPDATE `rest`.`user_resource` SET `name`='DMA | Menu | Produkte | Produkte' WHERE `id`='9';
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Layout', 'ProductLayoutMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Kategorien', 'ProductCategoryMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Pakete', 'ProductPackageMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Datentypen', 'ProductDatatypMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Eigenschaften', 'ProductPropertyMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Anpassungen', 'ProductCustomizeMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Personalisierung', 'ProductPersonalizeMenuItem');
INSERT INTO `rest`.`user_resource` (`name`, `slug`) VALUES ('DMA | Menu | Produkte | Währung', 'ProductCurrencyMenuItem');

INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '18', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '19', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '20', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '21', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '22', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '23', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '24', 'create,read,update,delete,execute,release');
INSERT INTO `rest`.`user_role_has_user_resource` (`user_role_id`, `user_resource_id`, `allow`) VALUES ('1', '25', 'create,read,update,delete,execute,release');

UPDATE `rest`.`user_resource` SET `slug`='ProductDatatypeMenuItem' WHERE `id`='21';

ALTER TABLE `rest`.`product_customize` DROP FOREIGN KEY `fk_product_customize_product_datatype1` ;

ALTER TABLE `rest`.`product_customize` CHANGE COLUMN `idproduct_customize` `id` INT(11) NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `rest`.`product_item_has_product_customize` 
  ADD CONSTRAINT `fk_product_item_has_product_customize_product_item2`
  FOREIGN KEY (`product_customize_id` )
  REFERENCES `rest`.`product_customize` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


ALTER TABLE `rest`.`product_item_has_product_customize` DROP FOREIGN KEY `fk_product_item_has_product_customize_product_item2` ;
ALTER TABLE `rest`.`product_item_has_product_customize` 
  ADD CONSTRAINT `fk_product_item_has_product_customize_product_customize1`
  FOREIGN KEY (`product_customize_id` )
  REFERENCES `rest`.`product_customize` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `rest`.`product_package` ADD COLUMN `label` VARCHAR(255) NULL DEFAULT NULL  AFTER `id` ;

UPDATE `rest`.`product_package` SET `label`='createPackagesFleurop2011' WHERE `id`='1';
UPDATE `rest`.`product_package` SET `label`='createPackagesFleurop2014' WHERE `id`='2';

ALTER TABLE `rest`.`product_product` DROP FOREIGN KEY `fk_product_product_product_currency1` , DROP FOREIGN KEY `fk_product_product_product_package1` ;
ALTER TABLE `rest`.`product_product` CHANGE COLUMN `idproduct_package` `product_package_id` INT(11) NOT NULL  , CHANGE COLUMN `idproduct_currency` `product_currency_id` INT(11) NOT NULL  , 
  ADD CONSTRAINT `fk_product_product_product_currency1`
  FOREIGN KEY (`product_currency_id` )
  REFERENCES `rest`.`product_currency` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `fk_product_product_product_package1`
  FOREIGN KEY (`product_package_id` )
  REFERENCES `rest`.`product_package` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `rest`.`product_item_has_product_customize` 
DROP PRIMARY KEY ;
ALTER TABLE `rest`.`product_item_has_product_customize` ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT  FIRST 
, ADD PRIMARY KEY (`id`) ;

ALTER TABLE `rest`.`product_product_has_product_property` 
DROP PRIMARY KEY ;

ALTER TABLE `rest`.`product_product_has_product_property` ADD COLUMN `id` INT NOT NULL AUTO_INCREMENT  FIRST 
, ADD PRIMARY KEY (`id`) ;
