<?php

class Import_Service_Order
{

	protected $_import_import_id = null;
	protected $_product_product_id = null;
	protected $_product_item_id = null;
	
	private function _getImportOrder() {
		$importOrder = new Import_Model_DbTable_Ordercompare();
		$rows = $importOrder->select()->order('partner_nr')->query()->fetchAll();
		
		$entries = array();
		$fields = array();
		$entry = null;
		$partner_nr = null;
		
		foreach ($rows as $row) {
			if ($row['partner_nr'] != $partner_nr) {
				$partner_nr = $row['partner_nr'];
				if (is_array($entry)) {
					array_push($entries, $entry);
				}
				$entry = array();
			}
			$entry[$row['key']] = $row['value'];
			$fields[$row['key']] = $row['key'];
		}
		
		if (is_array($entry)) {
			array_push($entries, $entry);
		}
		
		$result = new stdClass();
		
		$result->fields = $fields;
		$result->rows = $entries;
		
		return $result;
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
					$col = trim(trim($str[1],'"'),'\''); // str_replace('"', '', $str[1]);
					$replacement = @$csv[$col];
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
				$replacement = Zend_Db_Table::getDefaultAdapter()->quote($replacement); //'"' . $replacement . '"';
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
						//$class = new $classname();
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
							$table = ucfirst($prefix) . '_Model_DbTable_' . ucfirst(implode('', $table)); //str_replace(' ', '',ucwords(implode(' ', $table)));
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
	
	
	public function import(Zend_Controller_Request_Http $request) {
		
		$this->_import_import_id = $request->getParam('import_import_id', null);
		$this->_product_product_id = $request->getParam('product_product_id', null);
		$this->_product_item_id = $request->getParam('product_item_id', null);
		$this->_haspos = $request->getParam('haspos', null);
		$this->_weightpos = $request->getParam('weightpos', null);
		
		if (empty($this->_import_import_id) || empty($this->_product_product_id) || empty($this->_product_item_id)) {
			return false;
		}

		$import_import = new Import_Model_DbTable_Import();
		$rii = $import_import->find($this->_import_import_id)->current();
		
		$product_item = new Product_Model_DbTable_Item();
		$rpi = $product_item->find($this->_product_item_id)->current();
		
		$import_stack = new Import_Model_DbTable_Stack();
		$ris = $import_stack->createRow(array(
				'title' => $rii['title'] . ', ' .$rpi['title'] . ', ' . date('Y-m-d H:i:s'),
				'import_import_id' => $this->_import_import_id,
				'product_item_id' => $this->_product_item_id,
				'creation_date' => date('Y-m-d H:i:s'),
				));
		$this->_import_stack_id = $ris->save();
		
		
		
		$datasets = $this->_getImportOrder();
		$rows = $datasets->rows;
		$fields = $datasets->fields;
		
		$actions = $this->_getImportAction();
		
		$params = $request->getParams();
		$params['import_stack_id'] = $this->_import_stack_id;
		$params['haspos'] = empty($params['haspos']) || $params['haspos'] == "false" ? 0 : 1;
		$params['weightpos'] = empty($params['weightpos']) ? 0 : $params['weightpos'];
		
		return $this->_doImport($actions, $rows, $fields, $params);		
	}
	
}