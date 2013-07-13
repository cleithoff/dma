<?php

class Ext_Resource extends Ext_Architect {
	
	public $basePath = 'http://extjs.cachefly.net/ext-4.1.1-gpl/';
	public $debug = false;
	public $includeJs = true;
	public $includeCss = true;
	
	protected $_metaType = 'libraryresource';
	
	protected function _getPattern() {
		return 'resource';
	}
	
	protected function _getPath() {
		return 'resource';
	}
}