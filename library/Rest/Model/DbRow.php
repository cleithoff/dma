<?php 

class Rest_Model_DbRow extends Zend_Db_Table_Row_Abstract {
	
	protected $_virtual = array();
	
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