<?php

class Ext_data_proxy_Proxy extends Ext_Base {
	
	public $reader = null;
	public $writer = null;
	
	protected $_metaType = 'proxy';
	protected $_metaReferenceName = 'proxy';
	protected $_metaReferenceType = 'object';
	
}