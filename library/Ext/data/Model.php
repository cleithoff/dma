<?php

class Ext_data_Model extends Ext_Base {
	
	public $fields = array();
	public $proxy = null;
	public $idProperty = 'id';
	public $clientIdProperty = null;
	public $belongsTo = null;
	public $hasMany = null;
	public $associations = array();
	
	protected $_metaType = 'datamodel';
	protected $_metaReferenceName = 'items';
	
	public function setFields(array $fields = array(), $idProperty = 'id', $clientIdProperty = null) {
		$this->fields = $fields;
		$this->idProperty = $idProperty;
		$this->clientIdProperty = $clientIdProperty;
	}
	
	protected function _getCn() {
		$array = parent::_getCn();
		foreach ($this->fields as $field) {
			$array[] = $field->getMeta();
		}
		return $array;
	}	
	
	protected function _getPattern() {
		return 'model';
	}
	
	protected function _getPath() {
		return 'model';
	}
	
}