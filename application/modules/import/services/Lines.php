<?php

class Import_Service_Lines
{

	public function import() {
		$i = 0;
		if ($fp = fopen(APPLICATION_PATH . '/../resource/floh.csv', 'r')) {
			while (($data = fgetcsv($fp, null, ";")) !== FALSE) {
				if ($i > 0) {
					$sql = 'INSERT IGNORE INTO import_lines SET partner_nr = ?, line1 = ?, line2 = ?, line3 = ?, line4 = ?, line5 = ?, line6 = ?, line7 = ?, floh = ?';
					Zend_Db_Table::getDefaultAdapter()->query($sql, array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8]));
				}
				$i++;
			}
		}
	}
	
	public function assemble() {

		$partner_partner = new Partner_Model_DbTable_Partner();
		
		$product_item_id = 2;
		$import_lines = Zend_Db_Table::getDefaultAdapter()->query('SELECT * FROM import_lines')->fetchAll();
		foreach($import_lines as $import_lines_row) {
			
			// get partner_partner_id
			$partner_partner_id = $import_lines_row['floh'];
			
			if (empty($partner_partner_id)) continue;
			
			// create order_pool
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_pool SET dummy = 0');
			$order_pool_id = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
			
			// create order_cart
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_cart SET idsession = "' . Zend_Session::getId() . '", order_pool_id = ' . intval($order_pool_id));
			$order_cart_id = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
			
			// create order_order
			try {
				Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_order SET partner_partner_id = "' . $partner_partner_id . '", order_pool_id = ' . intval($order_pool_id) . ', incoming = NOW(), order_state_id = 2');
			} catch(Exception $ex) {
				echo 'INSERT INTO order_order SET partner_partner_id = "' . $partner_partner_id . '", order_pool_id = ' . intval($order_pool_id) . ', incoming = NOW(), order_state_id = 2';
				die();
			}
			$order_order_id = Zend_Db_Table::getDefaultAdapter()->lastInsertId();			
			
			// create order_item
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item SET product_item_id = "' . $product_item_id . '", order_pool_id = ' . intval($order_pool_id) . ', amount = 0, order_itemstate_id = 6');
			$order_item_id = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
							
			// create order_item_has_product_personalize
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 14, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line1']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 15, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line2']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 16, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line3']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 17, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line4']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 18, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line5']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 19, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line6']));
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO order_item_has_product_personalize SET product_personalize_id = 20, order_item_id = ' . intval($order_item_id) . ', value = ' . Zend_Db_Table::getDefaultAdapter()->quote($import_lines_row['line7']));
				
			
		}
	}
}

