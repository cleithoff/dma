<?php

class Import_PartnerController extends Rest_Controller_Action_DbTable
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
		$root = new stdClass();
		$root->text = "Root";
		$root->expanded = true;
		$root->children = array();
		
		$sql = "
		SELECT id as idx,partner_nr,post_anrede,post_name1,post_name2,post_strasse,post_plz,post_ort,druck_hg_tel,'import_partner' AS origin FROM rest.import_partner
		UNION
		SELECT id as idx,partner_nr,post_anrede,post_name1,post_name2,post_strasse,post_plz,post_ort,druck_hg_tel,'import_partnercompare' AS origin FROM rest.import_partnercompare
		UNION
		SELECT pp.id as idx,pp.partner_nr,pa.post_anrede,pa.post_name1,pa.post_name2,pa.post_strasse,pa.post_plz,pa.post_ort,pa.druck_hg_tel,'partner_partner' AS origin FROM rest.partner_partner pp
		LEFT JOIN partner_address pa ON pp.partner_address_id_invoice = pa.id
	    WHERE pp.partner_nr IN (SELECT partner_nr FROM import_partner)
		ORDER BY partner_nr, origin
		;";
		$db = Zend_Db_Table::getDefaultAdapter();
		$rows = $db->query($sql)->fetchAll();
		
		$partner_nr = null;
		
		foreach ($rows as $row) {
			if ($partner_nr != $row['partner_nr']) {
				$entry = new stdClass();
				$entry->text = $row['partner_nr'];
				
				$entry->expanded = true;
				//$entry->leaf = false;
				
				$partner_nr = $row['partner_nr'];
				$entry->children = array();
				array_push($root->children, $entry);
			}
			
			
			if ($row['origin'] == 'import_partner' || $row['origin'] == 'partner_partner') {
				$row['text'] = $row['origin'];
				$row['leaf'] = true;
				array_push($entry->children, $row);
			} elseif ($row['origin'] == 'import_partnercompare') {
				foreach ($row as $key => $value) {
					if ($key != 'id') {
						$entry->$key = $value;
					}
				}
			}
		}
		echo Zend_Json::encode($root);
		die();
	}
	
	public function importAction() {
		$pp = new Partner_Model_DbTable_Partner();
		$pa = new Partner_Model_DbTable_Address();
		
		$sql = 'SELECT ipc.*,pp.id as partner_id,pp.title,pp.partner_address_id_invoice,pp.partner_address_id_delivery FROM import_partnercompare ipc LEFT JOIN partner_partner AS pp ON pp.partner_nr = ipc.partner_nr';

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
				$pp->insert(array('partner_nr' => $row['partner_nr'], 'title' => $row['post_name1'] . ' ' . $row['post_name2']));
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
				if (empty($row['title'])) {
					$pprow = $pp->find($row['partner_id'])->current();
					$pprow->title = $row['post_name1'] . ' ' . $row['post_name2'];
					$pprow->save();
				}
				
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
		}
		$this->view->success = true;
	}
	
	public function indexAction() {
		
	}
}
