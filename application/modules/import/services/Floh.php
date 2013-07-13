<?php

class Import_Service_Floh
{
	
	public function import() {
		$i = 0;
		if ($fp = fopen(APPLICATION_PATH . '/../resource/floh.csv', 'r')) {
			while (($data = fgetcsv($fp, null, ";")) !== FALSE) {
				if ($i > 0) {
					$sql = 'INSERT IGNORE INTO import_floh SET partner_nr = ?, floh = ?';
					Zend_Db_Table::getDefaultAdapter()->query($sql, array($data[0],$data[8]));
				}
				$i++;
			}
		}
	}

}

