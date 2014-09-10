ALTER TABLE `rest`.`product_layout` ADD COLUMN `plugin_classes` TEXT NULL DEFAULT NULL  AFTER `correction_form_class` ;

UPDATE rest.product_layout SET xsl_front_preview = REPLACE(xsl_front_preview, 'xls', 'xsl');
UPDATE rest.product_layout SET xsl_back_preview = REPLACE(xsl_back_preview, 'xls', 'xsl');
UPDATE rest.product_layout SET xsl_front_print = REPLACE(xsl_front_print, 'xls', 'xsl');
UPDATE rest.product_layout SET xsl_back_print = REPLACE(xsl_back_print, 'xls', 'xsl');

UPDATE `rest`.`product_layout` SET `plugin_classes`='Product_Service_Plugingutscheincard' WHERE `id`='7';
UPDATE `rest`.`product_layout` SET `plugin_classes`='Product_Service_Plugingutscheincard' WHERE `id`='9';
