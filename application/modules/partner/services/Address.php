<?php

class Partner_Service_Address
{

	public function zipcleanup() {
		 
		$sql = "UPDATE partner_address SET post_plz = LPAD(post_plz, 5, '0')";
		Zend_Db_Table::getDefaultAdapter()->query($sql);
		 
		$sql = "UPDATE product_personalize
			INNER JOIN order_item_has_product_personalize ON order_item_has_product_personalize.product_personalize_id = product_personalize.id
			SET order_item_has_product_personalize.value = LPAD(order_item_has_product_personalize.value,5,'0')
			WHERE `key` IN ('druck_hg_plz', 'druck_ng_plz')";
		Zend_Db_Table::getDefaultAdapter()->query($sql);
		 
		return true;
	}
}

