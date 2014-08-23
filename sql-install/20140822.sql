ALTER TABLE `rest`.`product_item` ADD COLUMN `product_item_no_internal_stock` VARCHAR(45) NULL DEFAULT NULL  AFTER `product_item_no_external` , ADD COLUMN `product_item_no_external_stock` VARCHAR(45) NULL DEFAULT NULL  AFTER `product_item_no_internal_stock` ;

UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5294, product_item_no_internal_stock = 5341 WHERE id = 8;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5295, product_item_no_internal_stock = 5342 WHERE id = 10;
UPDATE product_item SET product_item_no_internal = 5296, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 11;
UPDATE product_item SET product_item_no_internal = 5297, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 9;

UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5298, product_item_no_internal_stock = 5345 WHERE id = 14;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5299, product_item_no_internal_stock = 5346 WHERE id = 12;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5300, product_item_no_internal_stock = 5347 WHERE id = 16;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5301, product_item_no_internal_stock = 5348 WHERE id = 15;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5302, product_item_no_internal_stock = 5349 WHERE id = 13;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5303, product_item_no_internal_stock = 5350 WHERE id = 30;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5304, product_item_no_internal_stock = 5351 WHERE id = 17;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5305, product_item_no_internal_stock = 5352 WHERE id = 18;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5306, product_item_no_internal_stock = 5353 WHERE id = 19;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5307, product_item_no_internal_stock = 5354 WHERE id = 20;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5308, product_item_no_internal_stock = 5355 WHERE id = 4;
UPDATE product_item SET product_item_no_internal = 5309, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 5;
-- plexiglas display unknown UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5321, product_item_no_internal_stock = 5356 WHERE id = ?;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5322, product_item_no_internal_stock = 5357 WHERE id = 6;
UPDATE product_item SET product_item_no_internal = 5323, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 7;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5310, product_item_no_internal_stock = 5358 WHERE id = 23;
UPDATE product_item SET product_item_no_internal = 5311, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 24;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5312, product_item_no_internal_stock = 5359 WHERE id = 28;
UPDATE product_item SET product_item_no_internal = 5313, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = 29;
UPDATE product_item SET product_item_no_internal = NULL, product_item_no_external_stock = 5314, product_item_no_internal_stock = 5360 WHERE id = 27;
-- Druckvorlage unknown UPDATE product_item SET product_item_no_internal = 5331, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = ?;
-- starterset unkown UPDATE product_item SET product_item_no_internal = 5332, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = ?;
-- Geschenkgutscheine Karton UPDATE product_item SET product_item_no_internal = 5333, product_item_no_external_stock = NULL, product_item_no_internal_stock = NULL WHERE id = ?;

UPDATE `rest`.`import_action` SET `condition`='product_item.product_item_no_internal = {csv.product_item_no_external} OR product_item.product_item_no_internal_stock = {csv.product_item_no_external} OR product_item.product_item_no_external_stock = {csv.product_item_no_external}\n' WHERE `id`='40';

UPDATE `rest`.`product_personalize` SET `key`='filename_graphics' WHERE `id`='23';
UPDATE `rest`.`import_action` SET `condition`='order_order.order_no_external = {csv.order_no_external}' WHERE `id`='36';
UPDATE `rest`.`import_action` SET `setter`='order_pool.import_stack_id = {result.import_stack.id}' WHERE `id`='37';

ALTER TABLE `rest`.`order_order` ADD COLUMN `import_import_id` INT NULL DEFAULT NULL  AFTER `order_no_external` , ADD COLUMN `import_stack_id` INT NULL DEFAULT NULL  AFTER `import_import_id` , 
  ADD CONSTRAINT `import_import`
  FOREIGN KEY (`import_import_id` )
  REFERENCES `rest`.`import_import` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `import_stack`
  FOREIGN KEY (`import_stack_id` )
  REFERENCES `rest`.`import_stack` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `import_import_idx` (`import_import_id` ASC) 
, ADD INDEX `import_stack_idx` (`import_stack_id` ASC) ;

UPDATE order_order 
INNER JOIN order_pool ON order_order.order_pool_id = order_pool.id
INNER JOIN import_stack ON order_pool.import_stack_id = import_stack.id
SET order_order.import_import_id = import_stack.import_import_id, order_order.import_stack_id = import_stack.id;


CREATE  TABLE `rest`.`order_meta` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `key` VARCHAR(45) NOT NULL ,
  `title` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `key` (`key` ASC) ) ENGINE=InnoDB;

CREATE  TABLE `rest`.`order_item_has_order_meta` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `order_item_id` INT NOT NULL ,
  `order_meta_id` INT NOT NULL ,
  `order_meta_key` VARCHAR(45) NOT NULL ,
  `value` VARCHAR(4096) NULL ,
  PRIMARY KEY (`id`) )  ENGINE=InnoDB;
