<?php 

class Rest_Model_DbRow extends Zend_Db_Table_Row_Abstract {
	
	protected $_virtual = array();

	protected $_rows = array();
	
	protected $_dbTable = array();
	
	public function __get($columnName) {
		return parent::__get($columnName);
	}
	
	public function __set($columnName, $value) {
		try {
			parent::__set($columnName, $value);
		}
		catch (Exception $e) {
			$this->_virtual[$columnName] = $value;
		}
	}
	
	public function __call($method, array $args) {
		preg_match_all('/((?:^|[A-Z])[a-z]+)/',$method,$matches);
		
		$func = $matches[0][0];
		
		if ($func === "dep") {
			
			

			
			$prefix = $matches[0][1];
				
			$suffix = $matches[0][2];
				
			for ($i = 3; $i < count($matches[0]); $i++) {
				$suffix .= strtolower($matches[0][$i]);
			}
				
			$idvar1 = strtolower($prefix) . '_' . strtolower($suffix) . '_id';

			$classname = explode('_', get_class($this));
			
			$idvar2 = strtolower(reset($classname)) . '_' . strtolower(end($classname)) . '_id';
			
			if (array_key_exists($idvar1, $this->_rows)) return $this->_rows[$idvar1];
				
			
			$dbTable = $prefix . '_Model_DbTable_' . $suffix;
				
			$dbTable = new $dbTable();
				
			$idval = $this->id; //$idvar;
				
			$rows = $dbTable->fetchAll($idvar2 . " = " . $idval);
				
			if (!empty($rows)) {
				$this->_rows[$idvar1] = $rows;
				return $rows;
			}
			return null;
		}
		
		if ($func === "get" || $func === "all") { 
			$prefix = $matches[0][1];
			
			$suffix = $matches[0][2];
			
			for ($i = 3; $i < count($matches[0]); $i++) {
				$suffix .= strtolower($matches[0][$i]);
			}
			
			$idvar = strtolower($prefix) . '_' . strtolower($suffix) . '_id';
			
			if (array_key_exists($idvar, $this->_rows)) return $this->_rows[$idvar];
			
			$dbTable = $prefix . '_Model_DbTable_' . $suffix;
			
			$dbTable = new $dbTable();
			
			$idval = $this->$idvar;
			
			$row = $dbTable->find($idval);
			
			if (!empty($row)) {
				if ($func === "get") {
					$this->_rows[$idvar] = $row->current();
					return $row->current();
				}
				if ($func === "all") {
					// $this->_rows[$idvar] = $row->current();
					return $row;
				}
			}
			return null;
		}
		
		return parent::__call($method, $args);
	}
	
	public function toArray() {
		return array_merge(parent::toArray(), $this->_virtual);
	}
	
	public function setFromArray(array $data) {
		$fields = $this->getTable()->info(Zend_Db_Table::METADATA);
		foreach ($fields as $field) {
			if ($field['IDENTITY']) {
				unset($data[$field['COLUMN_NAME']]);
			}
		}
		return parent::setFromArray($data);
	}
	
}