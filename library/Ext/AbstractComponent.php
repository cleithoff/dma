<?php 

class Ext_AbstractComponent extends Ext_Base {
	
	public $disabled = null;
	public $id = null;
	public $itemId = null;
	public $height = null;
	public $width = null;
	public $xtype = null;
	public $cls = null;
	public $minHeight = null;
	public $maxHeight = null;
	public $plugins = array();
	
	public $listeners = array();
	
	public function addListeners(array $listeners = array()) {
		array_merge($this->listeners, $listeners);
		return $listeners;
	}

	private $_ownerCt = null;
	
	public function getOwnerCt() {
		return $this->_ownerCt;
	}

	public function setOwnerCt($ownerCt) {
		$this->_ownerCt = $ownerCt;		
	}
	
	protected function _getPath() {
		return 'view';
	}
	
}