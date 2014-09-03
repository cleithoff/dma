ALTER TABLE `rest`.`import_action` ADD COLUMN `limit` TEXT NULL DEFAULT NULL  AFTER `setter` ;

ALTER TABLE `rest`.`import_action` CHANGE COLUMN `limit` `limitation` TEXT NULL DEFAULT NULL  ;

ALTER TABLE `rest`.`order_item` ADD COLUMN `product_product_id` INT NULL DEFAULT NULL  AFTER `product_item_id` ;

UPDATE `rest`.`import_action` SET `setter`='order_item.product_item_id = {result.product_item.id}, order_item.product_product_id = {result.product_item.product_product_id}, order_item.order_pool_id = {result.order_pool.id}, order_item.amount = {csv.amount}, order_item.order_itemstate_id = 1 ' WHERE `id`='41';

ALTER TABLE `rest`.`order_item` 
ADD INDEX `product_product_id` (`product_product_id` ASC) ;

CREATE  TABLE `rest`.`product_barcode` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `barcode` VARCHAR(16) NOT NULL ,
  `reserved1` VARCHAR(45) NULL DEFAULT NULL ,
  `type` VARCHAR(45) NULL DEFAULT NULL ,
  `reserved2` VARCHAR(45) NULL DEFAULT NULL ,
  `reserved3` VARCHAR(45) NULL DEFAULT NULL ,
  `number` VARCHAR(45) NULL DEFAULT NULL ,
  `product_product_id` INT NULL DEFAULT NULL ,
  `product_item_id` INT NULL DEFAULT NULL ,
  `order_pool_id` INT NULL DEFAULT NULL ,
  `order_order_id` INT NULL DEFAULT NULL ,
  `order_item_id` INT NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `barcode` (`barcode` ASC) ,
  INDEX `type` (`type` ASC) ,
  INDEX `product_product_id` (`product_product_id` ASC) ,
  INDEX `product_item_id` (`product_item_id` ASC) ,
  INDEX `order_pool_id` (`order_pool_id` ASC) ,
  INDEX `order_order_id` (`order_order_id` ASC) ,
  INDEX `order_item_id` (`order_item_id` ASC) );

  
--LOAD DATA INFILE 'C:/xampp/htdocs/dma/sql-install/Karton_Partnereindruck_20140724_113842_5296.TXT' INTO TABLE product_barcode FIELDS TERMINATED BY ';' (`barcode`,`reserved1`,`type`,`reserved2`,`reserved3`);
--LOAD DATA INFILE 'C:/xampp/htdocs/dma/sql-install/PVC_Partnereindruck_20140724_113704_5297.TXT' INTO TABLE product_barcode FIELDS TERMINATED BY ';' (`barcode`,`reserved1`,`type`,`reserved2`,`reserved3`);
--UPDATE product_barcode SET product_product_id = 6, product_item_id = 9 WHERE type = "PLAST";
--UPDATE product_barcode SET product_product_id = 7, product_item_id = 11 WHERE type = "PAPPE";
  
INSERT INTO `rest`.`import_action` (`import_import_id`, `linenumber`, `action`, `source`, `condition`, `setter`, `limitation`) VALUES ('3', '165', 'UPDATE', 'product_barcode', 'product_barcode.product_item_id = {result.product_item.id}', 'product_barcode.order_pool_id = {result.order_pool.id}, product_barcode.order_order_id = {result.order_order.id}, product_barcode.order_item_id = {result.order_item.id}', '{csv.amount}');
UPDATE `rest`.`import_action` SET `limitation`='{csv.amount|#}' WHERE `id`='50';
UPDATE `rest`.`import_action` SET `condition`='product_barcode.product_item_id = {result.product_item.id} AND product_barcode.order_pool_id IS NULL' WHERE `id`='50';

ALTER TABLE `rest`.`order_item` ADD COLUMN `product_item_no` VARCHAR(45) NULL  AFTER `authkey` ;

UPDATE `rest`.`import_action` SET `setter`='order_item.product_item_id = {result.product_item.id}, order_item.product_product_id = {result.product_item.product_product_id}, order_item.order_pool_id = {result.order_pool.id}, order_item.amount = {csv.amount}, order_item.order_itemstate_id = 1, order_item.product_item_no = {csv.product_item_no_external}' WHERE `id`='41';

INSERT INTO `rest`.`report_report` (`title`, `description`, `sql`, `fileprefix`) VALUES ('Lieferschein für BestellID', 'Lieferschein für eine Bestellung', 'SELECT\r  partner_partner.partner_nr,\r  partner_partner.title partner_title,\r \r  partner_address.post_anrede,\r  partner_address.post_name1,\r  partner_address.post_name2,\r  partner_address.post_strasse,\r  partner_address.post_plz,\r  partner_address.post_ort,\r \r  order_order.order_no_external,\r  DATE_FORMAT(order_order.incoming,\'%d.%m.%Y\') order_incoming_date,\r  order_item.product_item_no,\r  product_product.title AS product_title,\r  product_item.title AS product_item_title,\r  order_item.amount,\r  GROUP_CONCAT(product_barcode.barcode) barcodes\r \r  FROM order_pool\r  INNER JOIN order_order ON order_order.order_pool_id = order_pool.id\r  INNER JOIN order_item ON order_pool.id = order_item.order_pool_id\r  INNER JOIN product_item ON product_item.id = order_item.product_item_id\r  INNER JOIN product_product ON product_product.id = product_item.product_product_id\r  INNER JOIN partner_partner ON order_order.partner_partner_id = partner_partner.id\r  INNER JOIN partner_address ON partner_partner.partner_address_id_delivery = partner_address.id\r  LEFT JOIN product_barcode ON product_barcode.order_item_id = order_item.id\r  WHERE \r        order_pool.id = {param.order_pool_id}\r  GROUP BY order_item.id;', 'lieferschein');
INSERT INTO `rest`.`report_filter` (`title`, `key`, `report_filtertype_id`, `report_filteroperator_id`, `report_report_id`, `jsonparam`) VALUES ('BestellID', 'order_pool_id', '2', '1', '7', '{\"fieldLabel\":\"BestellID\",\"name\":\"order_pool_id\",\"format\":\"#\"}');

