<?php 

class Rest_Model_Mapper_DbTable {
	
	protected $_store = null;
	protected $_rowCount = 0;
	
	protected function getStore() {
		if (is_string($this->_store))  {
			$this->_store = new $this->_store();
		}
		return $this->_store;
	}
	
	public function __construct($store = false) {
		if (!empty($store)) {
			$this->_store = $store;
		}
	}
			
	public function delete(array $data) {
		$cols = array();
		$primary = $this->getStore()->info('primary');
		if (is_array($primary)) {
			foreach ($primary as $key => $val) {
				$cols[] = '`' . $val  . '` = ' . $this->getStore()->getAdapter()->quote($data[$val]);
			}
		} else {
			$cols[] = '`' . $primary  . '` = ' . $this->getStore()->getAdapter()->quote($data[$primary]);
		}

		// TODO $this->getStore()->delete();
		
		$result = $this->getStore()->getAdapter()->query('DELETE FROM `' . $this->getStore()->info('name') . '` WHERE ' . implode(' AND ', $cols) . ' LIMIT 1');
		return $result->rowCount();		
	}
	
	public function update($data) {
		$cols = array();
		$row = null;
		$primary = $this->getStore()->info('primary');
		if (is_array($primary)) {
			foreach ($primary as $key => $val) {
				if (!empty($data[$val])) {
					$cols[$val] = $data[$val];
				}
			}
		} else {
			if (empty($data[$primary])) {
				unset($data[$primary]);
			} else {
				$cols[$primary] = $data[$primary];
			}
		}

		if (count($cols) == 0) {
			$unique = $this->getStore()->getIndex('unique');
			if (is_array($unique)) {
				$unique = reset($unique);
				if (!empty($unique) && is_array($unique) && count($unique)>0) {
					foreach($unique as $key => $val) {
						$cols[$val] = $data[$val];
					}
				}
			}
		}
		
		if (count($cols)>0) {
			$rowset = $this->getStore()->find($cols);
			$row = $rowset->current();
		}
		if (is_array($primary)) {
			foreach ($primary as $key => $val) {
				unset($data[$val]);
			}
		} else {
			unset($data[$primary]);
		}
		if (!empty($row)) {
			foreach ($data as $key => $val) {
				$row->$key = $val;
			}
			//var_dump($row);die();
			//$row->setFromArray($data)
			$row->save();
		} else {			
			$set = array();
			foreach($data as $key => $value) {
				$set[] = '`' . $key . '` = ' . Zend_Db_Table::getDefaultAdapter()->quote($value); // "' . $value . '"';
			}
			$set = implode(',', $set);
			//upsert
			//echo 'INSERT INTO ' . $this->getStore()->info('name') . ' SET ' . $set . ' ON DUPLICATE KEY UPDATE ' . $set;die();
			Zend_Db_Table::getDefaultAdapter()->query('INSERT INTO ' . $this->getStore()->info('name') . ' SET ' . $set . ' ON DUPLICATE KEY UPDATE ' . $set);
			return $data; //$this->insert($data);
		}
		return $row;
	}
	
	public function insert(array $data = array()) {
		$row = $this->getStore()->createRow();
		$row->setFromArray($data);
		$row->save();
		return $row;
	}
	
	public function rowCount() {
		return $this->_rowCount;
		//return $this->getStore()->getAdapter()->fetchOne('SELECT COUNT(*) AS total FROM `' . $this->getStore()->info('name') . '`');
	}
	
	protected function _getReferencedRows($rowset) {
		foreach ($rowset as $key => $row) {
			foreach ($this->getStore()->info(Zend_Db_Table_Abstract::REFERENCE_MAP) as $tablename => $val) {
				$table = $val['refTableClass'];
				$_row = $this->getStore()->find($row['id'])->current();
				if ($res = $_row->findParentRow(new $table())) {
					//var_dump($res); die();
					$data = $res->toArray();
					$rowset[$key][$tablename] = $data;
				} else {
					$rowset[$key][$tablename] = null;
				}	
			}
		}
		return $rowset;
	}
	
	protected function _filter(&$select, $val) {
		if ($val['value'] === null) {
			$select->where($val['property'] . ' IS NULL'); // . $this->getAdapter()->quote($val['value']));
		} else {
			if (empty($val['operator'])) {
				if (strpos($val['value'],'%') !== false) {
					$where = $this->getStore()->getAdapter()->quoteInto($this->getStore()->getAdapter()->quoteIdentifier($val['property']) . ' LIKE ?', ($val['value']));
				} else {
					$where = $this->getStore()->getAdapter()->quoteInto($this->getStore()->getAdapter()->quoteIdentifier($val['property']) . ' = ?', ($val['value']));
				}
				$select->where($where);
			} else {
				switch (strtolower($val['operator'])) {
					case 'in' :
						$select->where($val['property'] . ' ' . $val['operator'] . ' (' . $this->getStore()->getAdapter()->quote($val['value']) . ')');
						break;
					default :
						if (strpos($val['value'],'%') !== false) {
							$select->where($val['property'] . ' LIKE ' . $this->getStore()->getAdapter()->quote($val['value']));
						} else {
							$select->where($val['property'] . ' ' . $val['operator'] . ' ' . $this->getStore()->getAdapter()->quote($val['value']));
						}
				}
			}
		}
		
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
						case 'APPLICATION_PATH':
							$replacement = str_replace(DIRECTORY_SEPARATOR, '/', APPLICATION_PATH);
							break;
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
				$replacement = '"' . $replacement . '"';
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
		
	public function fetchMeta(Zend_Controller_Request_Http $req) {
		if (($sql = $req->getParam('_sql',null)) !== null) {
			
			$sql = $this->_getQuery($sql, array(), array(), $req->getParams());
			
			$result = Zend_Db_Table::getDefaultAdapter()->query($sql);
			$i = 0;
			$info = array();
			while ($meta = $result->getColumnMeta($i)) {
				$info[] = $meta;
				$i++;
			}
			return $info;
		} else {
			$info = $this->getStore()->info();
			$result = Zend_Db_Table::getDefaultAdapter()->query('SELECT * FROM ' . $info['name'] . ' LIMIT 1');
			$i = 0;
			$info = array();
			while ($meta = $result->getColumnMeta($i)) {
				$info[] = $meta;
				$i++;
			}
			return $info;
			//return $this->getStore()->info();
		}
		
	}
	
	public function fetch(Zend_Controller_Request_Http $req) {
		if (($sql = $req->getParam('_sql', null)) !== null) {	

			$sql = $this->_getQuery($sql, array(), array(), $req->getParams());
			return Zend_Db_Table::getDefaultAdapter()->query($sql)->fetchAll();
		}
		
		
		$request = $req->getParams();
		$select = Zend_Db_Table::getDefaultAdapter()->select()->from($this->getStore()->info('name')); //$this->getStore()->select();
		
		if (!empty($request['sort'])) {
			$sort = Zend_Json::decode($request['sort']);
		    foreach($sort as $key => $val) {
		    	if (!empty($val['property'])) {
		    		$select->order($val['property'] . ' ' . $val['direction']);
		    	}
		    }
		}
		
		$columns = $this->getStore()->info(Zend_Db_Table_Abstract::COLS);
		
		foreach ($request as $key => $value) {
			if (in_array($key, $columns) && $req->getControllerName() != $key && $req->getActionName() != $key && $req->getModuleName() != $key) {
				$select->where($key . ' = ?', $value);
			}
		}
		
		
		
		if (!empty($request['filter'])) {
			$joins = array();
			$filter = Zend_Json::decode($request['filter']);
			//var_dump($request['filter']);			
			/*try {
				$val = reset($filter);
				if ($val['value'] === null) {
					$filter = Zend_Json::decode($val['property']);
				}
			} catch (Exception $ex) {
				//var_dump($ex);
				//die();
			}*/
			
			foreach($filter as $key => $val) {
				if (empty($val['property'])) {
					continue;
				}	
						
				if ($val['value'] === null) {
					try {
					 	$_filter = Zend_Json::decode($val['property']);
					 	foreach ($_filter as $_key => $_val) {
					 		
					 		//$select = new Zend_Db_Select();
					 		if (strpos($val['property'], '.') > 0) {
					 			$p = explode('.', $val['property']);
					 			$select->joinInner($p[0], $p[0] . '_id = ' . $p[0] . '.id' , $val['property']);
					 		}
					 		$this->_filter($select, $_val);
					 		
					 	}
					 	continue;
					} catch (Exception $ex) {
						var_dump($ex);die();
					}					
				}
				
				if (strpos($val['property'], '.') > 0) {
					$p = explode('.', $val['property']);
					//$select = new Zend_Db_Select();
					$joins[$p[0]][] = $val;
					//$select->join($p[0], $p[0] . '_id = ' . $p[0] . '.id' , $val['property']);
				}
				$this->_filter($select, $val);
				
			}
			
			foreach($joins as $name => $join) {
				$cols = array();
				foreach ($join as $col) {
					$cols[] = $col['property'];
				}
				$select->join($name, $name . '_id = ' . $name . '.id' , $cols);
			}
			
		}
		
		//echo $select->__toString();die();
		
		//return $this->getStore()->getAdapter()->fetchOne('SELECT COUNT(*) AS total FROM `' . $this->getStore()->info('name') . '`');
		
		$rowCountSelect = clone $select;
		$rowCountSelect->columns(new Zend_Db_Expr('COUNT(*) as _total'));
		
		//echo $rowCountSelect->__toString();
		try {
			$rowCount = Zend_Db_Table::getDefaultAdapter()->query($rowCountSelect->__toString())->fetch();
		} catch(Exception $ex) {
			echo $rowCountSelect->__toString();
			die();
		}
		$this->_rowCount = $rowCount['_total'];

		if (!empty($request['limit'])) {
			$select->limit($request['limit'], intval($request['start']));
		}
		
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($select->__toString())->fetchAll(); // $this->getStore()->fetchAll($select);
				
		// "join" referenced tables
		$rowset = $this->_getReferencedRows($rowset);
				
		return $rowset;		
	}	
	
}