<?php

class Rest_Db_Table extends Zend_Db_Table_Abstract {
	
	protected $_index = false;
	
	public function getIndex($type = false) {
		
		if (empty($type)) {
			return $this->_index;
		}
		
		if (!empty($this->_index[$type])) {
			return $this->_index[$type];
		}
		
		return false;
	}
	
}