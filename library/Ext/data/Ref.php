<?php

class Ext_data_Ref extends Ext_Base {
	
	public $ref = null;
	public $selector = null;
	public $xtype = null;
	public $autoCreate = null;
	
	protected $_metaType = 'controllerref';
	protected $_metaReferenceName = "items";
	protected $_metaReferenceType = "array";
	
}