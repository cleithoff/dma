<?php

class Ext_data_Field extends Ext_Base {
	
	public $name = null;
	public $type = 'auto';
	public $defaultValue = null;
	public $persist = true;
	public $useNull = false;
	
	protected $_metaType = 'datafield';
	protected $_metaReferenceName = 'fields';
	
}