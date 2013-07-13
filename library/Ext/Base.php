<?php

class Ext_Base extends Ext_Architect {

	public $xtype = null;
	
	public function __construct(array $config = array()) {
		parent::__construct();
		foreach ($config as $key => $value) {
			if (property_exists($this, $key)) {
				$this->$key = $value;
			}
		}
		if (empty($this->xtype)) {
			$this->xtype = $this->_metaType;
		}
	}
	
}