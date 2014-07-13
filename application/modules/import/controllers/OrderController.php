<?php

class Import_OrderController extends Rest_Controller_Action_DbTable
{

	protected $_service = null;
	
	public function init()
	{
		$this->_helper->contextSwitch()
		->clearActionContexts()
		->addActionContext('index', 'json')
		->addActionContext('tree', 'json')
		->addActionContext('import', 'json')
		->setDefaultContext('json')
		->initContext('json')
		;
	}
	
    protected function getService() {    	
    	if ($this->_service == null) {
    		$this->_service = new Import_Service_Import();
    	}   	
    	return $this->_service;
    }
	
	public function treeAction() {
		
		$product_item_id = $this->getRequest()->getParam('product_item_id', 'NULL');
		
		$root = new stdClass();
		$root->text = "Root";
		$root->expanded = true;
		$root->children = array();
		
		$sql = "
		SELECT id as idx,partner_nr,`key`,`value`,'import_order' AS origin FROM import_order
		UNION
		SELECT id as idx,partner_nr,`key`,`value`,'import_compare' AS origin FROM import_ordercompare
		UNION
		SELECT 0 as idx, `ppartner`.`partner_nr`, `ppersonalize`.`key`, `oihpp`.`value`, 'personalize' AS origin FROM (
			SELECT `partner_partner_id`, `order_pool_id` FROM (
				SELECT `oo`.`partner_partner_id`, `oo`.`order_pool_id`, `oo`.`incoming` FROM `order_order` `oo` 
				ORDER BY `oo`.`order_pool_id` /*`oo`.`incoming`*/ DESC
			) `oo2` GROUP BY `partner_partner_id`
		) `oo3`
		INNER JOIN `order_item` `oi` ON `oo3`.`order_pool_id` = `oi`.`order_pool_id` AND `oi`.`product_item_id` = " . $product_item_id . "
		INNER JOIN `order_item_has_product_personalize` `oihpp` ON `oihpp`.`order_item_id` = `oi`.`id`
		INNER JOIN `product_personalize` `ppersonalize` ON `ppersonalize`.`id` = `oihpp`.`product_personalize_id`
		INNER JOIN `partner_partner` `ppartner` ON `ppartner`.`id` = `oo3`.`partner_partner_id`	
		INNER JOIN import_ordercompare ON `import_ordercompare`.`partner_nr` = `ppartner`.`partner_nr`		
		ORDER BY partner_nr, origin
		;";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$rows = $db->query($sql)->fetchAll();
		
		$partner_nr = null;
		
		$entry = null;
		$data = null;
		
		foreach ($rows as $row) {
			if ($partner_nr != $row['partner_nr']) {
				if ($entry !== null && $data !== null) {
					if ($data->origin == 'import_order' || $data->origin == 'personalize') {
						array_push($entry->children, $data);
					}
					
				}				
				
				$entry = new stdClass();
				$entry->text = $row['partner_nr'];
				
				$entry->expanded = true;
				//$entry->leaf = false;
				
				$partner_nr = $row['partner_nr'];
				$entry->children = array();
				array_push($root->children, $entry);
				
				$origin = null;
				$data = null;
			}
			
			if ($origin != $row['origin']) {
				if ($data !== null) {
					array_push($entry->children, $data);
					$data = null;
				}
				$origin = $row['origin'];
			}
			
			if ($data === null) {
				if ($row['origin'] == 'import_order' || $row['origin'] == 'personalize') {
					$data = new stdClass();
					$data->text = $row['origin'];
					$data->origin = $row['origin'];
					$data->leaf = true;
				} elseif ($row['origin'] == 'import_compare') {
					$entry->text = $row['partner_nr'];
				}
			}
						
			$key = $row['key'];
			if ($row['origin'] == 'import_order' || $row['origin'] == 'personalize') {
				$data->$key = $row['value'];
			} elseif ($row['origin'] == 'import_compare') {
				if ($key != 'id') {
					$entry->$key = $row['value'];
				}
			}
						
		}
		if ($entry !== null && $data !== null) {
			if ($data->origin == 'import_order' || $data->origin == 'personalize') {
				array_push($entry->children, $data);
			}
			$data = null;
		}
		echo Zend_Json::encode($root);
		die();
	}
	
	public function importAction() {
		
		$service = new Import_Service_Order();
		
		$service->import($this->getRequest());
		
		/*$pp = new Partner_Model_DbTable_Partner();
		$pa = new Partner_Model_DbTable_Address();
		
		$sql = 'SELECT ipc.*,pp.id as partner_id,pp.partner_address_id_invoice,pp.partner_address_id_delivery FROM import_partnercompare ipc LEFT JOIN partner_partner AS pp ON pp.partner_nr = ipc.partner_nr';

		$rows = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
		foreach ($rows as $row) {
			$data['post_anrede'] = $row['post_anrede'];
			$data['post_name1'] = $row['post_name1'];
			$data['post_name2'] = $row['post_name2'];
			$data['post_strasse'] = $row['post_strasse'];
			$data['post_plz'] = $row['post_plz'];
			$data['post_ort'] = $row['post_ort'];
			$data['druck_hg_tel'] = $row['druck_hg_tel'];
			if (empty($row['partner_id'])) {
				// insert partner_partner
				$pp->insert(array('partner_nr' => $row['partner_nr']));
				$ppid = Zend_Db_Table::getDefaultAdapter()->lastInsertId();				
				// insert partner_address (invoice+delivery)
				$data['partner_partner_id'] = $ppid;
				$pa->insert($data);
				$paidi = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
				$pa->insert($data);
				$paidd = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
				// update partner_partner (invoice+delivery)
				$pprow = $pp->find($ppid)->current();
				$pprow->partner_address_id_invoice = $paidi;
				$pprow->partner_address_id_delivery = $paidd;
				$pprow->save();
			} else {
				$data['partner_partner_id'] = $row['partner_id'];
				if (empty($row['partner_address_id_invoice'])) {
					// insert partner_address
					$pa->insert($data);
					$paidi = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
					// update partner_partner (invoice)
					$pprow = $pp->find($row['partner_id'])->current();
					$pprow->partner_address_id_invoice = $paidi;
					$pprow->save();
				} else {
					// update partner_address
					$parow = $pa->find($row['partner_address_id_invoice'])->current();
					$parow->setFromArray($row);
					$parow->save();
				}
				
				if (empty($row['partner_address_id_delivery'])) {
					// insert partner_address
					$pa->insert($data);
					$paidd = Zend_Db_Table::getDefaultAdapter()->lastInsertId();
					// update partner_partner (invoice)
					$pprow = $pp->find($row['partner_id'])->current();
					$pprow->partner_address_id_delivery = $paidd;
					$pprow->save();
				} else {
					// update partner_address
					$parow = $pa->find($row['partner_address_id_delivery'])->current();
					$parow->setFromArray($row);
					$parow->save();
				}
			}
		}*/
		$this->view->success = true;
	}
	
	public function indexAction() {
		
	}
}
