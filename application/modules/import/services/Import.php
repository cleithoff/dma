<?php

class Import_Service_Import
{
	
	protected $_table;
	
	protected function makeDbFieldName($name) {
		$name = strtolower($name);
		$name = str_replace('-', '_', $name);
		$name = str_replace(' ', '_', $name);
		$name = str_replace('/', '_', $name);
		$name = str_replace('.', '', $name);
		$name = str_replace('ä', 'ae', $name);
		$name = str_replace('ö', 'oe', $name);
		$name = str_replace('ü', 'ue', $name);
		$name = str_replace('ß', 'ss', $name);
		return $name;
	}
	
	protected function getFieldNames($data) {
		$fields = array();
		
		foreach ($data as $key => $value) {
			$fields[$key] = $this->makeDbFieldName($value);
		}
		
		return $fields;
	}
	
	protected function setImportTable($table) {		
		$this->_table = $table;		
	}
	
	protected function getImportTable() {
		return $this->_table;
	}
	
	protected function writeDataToDb($fields, $data) {

		$t = $this->getImportTable();
		$t = new Import_Model_DbTable_Partner();
		
		$row = array();
		foreach ($fields as $key => $value) {
			if (empty($data[$key])) {
				$data[$key] = null;
			}
			$row[$value] = $data[$key];
		}
		
		$columns = $t->info(Zend_Db_Table::COLS);
		
		$set = array();
		foreach($columns as $col) {
			if (isset($row[$col])) {
				$set[] = Zend_Db_Table::getDefaultAdapter()->quoteInto($col . ' = ?', $row[$col]);
			}
		}
		
		$set = implode(',', $set);
		
		$sql = 'INSERT INTO ' . $t->info(Zend_Db_Table::NAME)  . ' SET ' . $set . ' ON DUPLICATE KEY UPDATE ' . $set;
		
		try {
			Zend_Db_Table::getDefaultAdapter()->query($sql);
		} catch(Exception $ex) {
			var_dump($fields);
			var_dump($data);
			echo $sql; die();
		}
		//echo $sql;die();

		//$r = $this->getImportTable()->createRow($row);
		//$r->save();
	}
	
	protected function writeDataListToDb($fields, $data) {
		// get index for partner_nr
		foreach ($fields as $key => $value) {
			if ($value == 'partner_nr') {
				$partner_nr = $key;
				break;
			}
		}
		
		foreach ($fields as $key => $value) {
			if (empty($data[$key])) {
				$data[$key] = null;
			}
			if (strtolower($value) == 'anzahl') {
				$data[$key] = str_replace(',', '.', str_replace('.', '', $data[$key]));
			}
			$row = array(
				'partner_nr' => $data[$partner_nr],
				'key' => $value,
				'value' => $data[$key],
			);
			$this->getImportTable()->insert($row);				
		}
	}	

	public function loadImportPartner($filename) {
		
		$this->setImportTable(new Import_Model_DbTable_Partner());	
		Zend_Db_Table::getDefaultAdapter()->query('DELETE FROM import_partnercompare'); //->execute();
		Zend_Db_Table::getDefaultAdapter()->query('DELETE FROM import_partner'); //->execute();
		
		$fields = array();
		$row = 1;
		if (($handle = fopen(APPLICATION_PATH . '/../upload/partner/' . $filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
				if ($row === 1) { // get field names
					$fields = $this->getFieldNames($data);
				} else {
					$this->writeDataToDb($fields, $data);
				}
				$row++;
			}
			fclose($handle);
		}		
		Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO import_partnercompare SELECT * FROM import_partner'); //->execute();
		
		// update for used values
		$sql = "
		UPDATE import_partnercompare ipc
		INNER JOIN partner_partner pp ON pp.partner_nr = ipc.partner_nr
		INNER JOIN partner_address pa ON pa.id = pp.partner_address_id_invoice
		SET 
			ipc.post_anrede = pa.post_anrede,
			ipc.post_name1 = pa.post_name1,
			ipc.post_name2 = pa.post_name2,
			ipc.post_strasse = pa.post_strasse,
			ipc.post_plz = pa.post_plz,
			ipc.post_ort = pa.post_ort,
			ipc.druck_hg_tel = pa.druck_hg_tel
		";
		Zend_Db_Table::getDefaultAdapter()->query($sql);

	}
	
	public function loadImportExecute($filename) {
		
		$fields = array();
		$row = 1;
		if (($handle = fopen(APPLICATION_PATH . '/../upload/execute/' . $filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
				if ($row === 1) { // get field names
					$fields = $this->getFieldNames($data);
				} else {
					$_data = array();
					foreach($data as $key => $value) {
						$_data[$fields[$key]] = $value;
					}
					$rows[] = $_data;
				}
				$row++;
			}
			fclose($handle);
		}
		
		$result =  new stdClass();
		$result->fields = $fields;
		$result->rows = $rows;
		return $result;
	}
	
	public function loadImportOrder($filename, $product_item_id) {
		
		$this->setImportTable(new Import_Model_DbTable_Order());
		Zend_Db_Table::getDefaultAdapter()->query('DELETE FROM import_ordercompare'); //->execute();
		Zend_Db_Table::getDefaultAdapter()->query('DELETE FROM import_order'); //->execute();
		
		Zend_Db_Table::getDefaultAdapter()->query('ALTER TABLE import_ordercompare AUTO_INCREMENT = 1');
		Zend_Db_Table::getDefaultAdapter()->query('ALTER TABLE import_order AUTO_INCREMENT = 1');
		
		$fields = array();
		$row = 1;
		if (($handle = fopen(APPLICATION_PATH . '/../upload/order/' . $filename, "r")) !== FALSE) {
			while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
				if ($row === 1) { // get field names
					$fields = $this->getFieldNames($data);
				} else {
					$this->writeDataListToDb($fields, $data);
				}
				$row++;
			}
			fclose($handle);
		}
		Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO import_ordercompare SELECT * FROM import_order'); //->execute();
		
		// todo: update for used values
		$sql = "
		SELECT `ppartner`.`partner_nr`, `pp`.`key`, `oihpp`.`value`, `oo`.`incoming` FROM `product_item` `pi`

		INNER JOIN `product_layout` `pl` ON `pl`.`id` = `pi`.`product_layout_id`
		INNER JOIN `product_layout_has_product_personalize` `plhpp` ON `plhpp`.`product_layout_id` = `pl`.`id`
		INNER JOIN `product_personalize` `pp` ON `pp`.`id` = `plhpp`.`product_personalize_id`
		INNER JOIN `order_item_has_product_personalize` `oihpp` ON `oihpp`.`product_personalize_id` = `pp`.`id`
		INNER JOIN `order_item` `oi` ON `oihpp`.`order_item_id` = `oi`.`id`
		INNER JOIN `order_order` `oo` ON `oo`.`order_pool_id` = `oi`.`order_pool_id`
		INNER JOIN `partner_partner` `ppartner` ON `oo`.`partner_partner_id` = `ppartner`.`id`
		INNER JOIN import_ordercompare ON `import_ordercompare`.`partner_nr` = `ppartner`.`partner_nr`
		
		WHERE `pi`.`id` = ?
		
		ORDER BY `incoming` ASC, `key` ASC
		";
		
		
		
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($product_item_id))->fetchAll();
		
		foreach($rowset as $row) {
			if (strtolower($row['key']) == 'anzahl') {
				$row['value'] = str_replace(',', '.', str_replace('.', '', $row['value']));
			}
			$sql = 'INSERT INTO import_ordercompare SET `partner_nr` = ?,`key` = ?,`value` = ? ON DUPLICATE KEY UPDATE `value` = ?';
			Zend_Db_Table::getDefaultAdapter()->query($sql, array($row['partner_nr'],$row['key'],$row['value'],$row['value']));
		}
	}

	private function _getImportAction() {
		$importAction = new Import_Model_DbTable_Action();
		$rows = $importAction->select()->where('import_import_id = ?', $this->_import_import_id)->order('linenumber')->query()->fetchAll();
	
		return $rows;
	}
	
	private function _getReplaces($matches, $doQuote, $csv, $result, $param) {
	
		$replacements = array();
		foreach ($matches as $match) {
			$str = str_replace(array('{','}','"'), array('','','"'), $match);
			$str = explode('.', $str);
			$source = $str[0];
			switch($source) {
				case 'csv':
					$col = str_replace('"', '', $str[1]);
					$replacement = $csv[$col];
					break;
				case 'result':
					$table = $str[1];
					$col = $str[2];
					$replacement = $result[$table][$col];
					break;
				case 'param':
					$key = $str[1];
					$replacement = $param[$key];
					break;
				case 'static':
					$static = $str[1];
					switch($static) {
						case 'PHPSESSID':
							$replacement = Zend_Session::getId();
							break;
					}
					break;
				default:
					$replacement = '';
					break;
			}
			if ($doQuote) {
				$replacement = Zend_Db_Table::getDefaultAdapter()->quote($replacement); // '"' . $replacement . '"';
			}
			array_push($replacements, $replacement); // $replacements
		}
		return $replacements;
	}
	
	private function _getQuery($sql, $csv, $result, $param) {
		$matches = array();
		preg_match_all('/\{[^\{\}]*\}/', $sql, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], true, $csv, $result, $param);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}'), array('\\{','\\.','\\}'), $match) . '/';
			}
			$sql = preg_replace($matches[0], $replacements, $sql);
		}
	
		$matches = array();
		preg_match_all('/\{.*?\}/', $sql, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], true, $csv, $result, $param);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}'), array('\\{','\\.','\\}'), $match) . '/';
			}
			$sql = preg_replace($matches[0], $replacements, $sql);
		}
	
		return $sql;
	}
	
	private function _doImport(array $actions, array $rows, array $fields, array $param) {
	
		reset($actions);
		foreach ($rows as $csv) {
			$result = array();
			$select = array();
			$linenumber = array();
			reset($actions);
			while($action = current($actions)) {
				$key = key($actions);
				//foreach ($actions as $key => $action) {
	
				switch($action['action']) {
					case 'UPSERT':
						$insert_id = null;
						$sql = 'INSERT INTO ' . $action['source'] . ' SET ' . $action['condition'] . ',  ' . $action['setter'] . ' ON DUPLICATE KEY UPDATE ' . $action['setter'];
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						try {
							Zend_Db_Table::getDefaultAdapter()->query($sql);
							$insert_id = Zend_Db_Table::getDefaultAdapter()->lastInsertId($action['source']);
						} catch (Exception $ex) {
							echo $sql;
							die();
						}
						if (!empty($insert_id)) {
							$sql = 'SELECT * FROM ' . $action['source'] . ' WHERE id = ' . $insert_id;
							$sql = $this->_getQuery($sql, $csv, $result, $param);
						} else {
							$sql = 'SELECT * FROM ' . $action['source'] . ' WHERE ' . str_replace(',', ' AND ', $action['condition']);
							$sql = $this->_getQuery($sql, $csv, $result, $param);
							$resultset = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
							$result[$action['source']] = reset($resultset);
						}
						$resultset = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
						$result[$action['source']] = reset($resultset);
						break;
					case 'GET':
						$result[$action['source']] = null;
						$sql = 'SELECT * FROM ' . $action['source'] . ' WHERE ' . $action['condition'];
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						$resultset = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
						$resultset = reset($resultset);
						if (!empty($resultset)) {
							$result[$action['source']] = $resultset;
						}
						break;
					case 'UPDATE':
						$sql = 'UPDATE ' . $action['source'] . ' SET ' . $action['setter'] . ' WHERE ' . $action['condition'];
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						try {
							Zend_Db_Table::getDefaultAdapter()->query($sql);
						} catch(Exception $ex) {
							echo $sql;
							die();
						}
						$sql = 'SELECT * FROM ' . $action['source'] . ' WHERE ' . $action['condition'];
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						$resultset = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
						$result[$action['source']] = reset($resultset);
						break;
					case 'INSERT':
						$sql = 'INSERT INTO ' . $action['source'] . ' SET ' . $action['setter']; // . ' WHERE ' . $action['condition'];
						$sql = str_replace(array("\r\n", "\r", "\n"), '', $sql);
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						try {
							Zend_Db_Table::getDefaultAdapter()->query($sql);
						} catch(Exception $ex) {
							echo $sql;
							die();
						}
						$sql = 'SELECT * FROM ' . $action['source'] . ' WHERE id = ' . Zend_Db_Table::getDefaultAdapter()->lastInsertId();
						$sql = str_replace(array("\r\n", "\r", "\n"), '', $sql);
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						$resultset = Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
						$result[$action['source']] = reset($resultset);
						break;
					case 'SCRIPT':
						$classname = $action['source'];
						$method = $this->_getQuery($action['setter'], $csv, $result, $param);
						$class = new $classname();
						$eval = '$class = new ' . $classname . '(); $class->' . $method . ';';
						eval($eval);
						break;
					case 'EXECUTE':
						$sql = 'CALL ' . $action['source']; // . ' SET ' . $action['setter']; // . ' WHERE ' . $action['condition'];
						$sql = $this->_getQuery($sql, $csv, $result, $param);
						Zend_Db_Table::getDefaultAdapter()->query($sql);
						break;
					case 'SELECT':
						$linenumber[$action['linenumber']] = $key;
						if (empty($select[$action['source']])) {
							$table = explode('_', $action['source']);
							$prefix = array_shift($table);
							$table = ucfirst($prefix) . '_Model_DbTable_' . str_replace(' ', '',ucwords(implode(' ', $table)));
							$where = $this->_getQuery($action['condition'], $csv, $result, $param);
							$select[$action['source']] = Zend_Db_Table::getTableFromString($table)->fetchAll($where)->toArray();
							$result[$action['source']] = current($select[$action['source']]);
						} else {
							$result[$action['source']] = next($select[$action['source']]);
						}
						if (empty($result[$action['source']])) {
	
							$linenumber = $action['setter'];
							while (next($actions)) {
								$action = current($actions);
								if ($action['linenumber'] == $linenumber) {
									// GOTO setter - 1, see next() below
									prev($actions);
									break;
								}
							}
							continue;
						}
						break;
					case 'NEXT':
						if (!empty($result[$action['source']])) {
							$linenumber = $action['setter'];
							//$i = 0;
							while (prev($actions)) {
								$action = current($actions);
								if (!$action || $action['linenumber'] == $linenumber) {
									// GOTO setter - 1, see next() below
									prev($actions);
									break;
								}
								/*$i++;
									if ($i > 6) {
								die();
								}*/
							}
							continue;
						}
						break;
	
				}
				next($actions);
			}
		}
		return true;
	}
	
	
	public function importExecute($filename, $request) {
	
		$this->_import_import_id = $request->getParam('import_import_id', null);
	
		if (empty($this->_import_import_id)) {
			return false;
		}
	
		$import_import = new Import_Model_DbTable_Import();
		$rii = $import_import->find($this->_import_import_id)->current();
		
		$datasets = $this->loadImportExecute($filename);
		$rows = $datasets->rows;
		$fields = $datasets->fields;
	
		$actions = $this->_getImportAction();
	
		$params = $request->getParams();
	
		return $this->_doImport($actions, $rows, $fields, $params);
	}
	
	
}

