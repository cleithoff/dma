<?php

class Ext_panel_Panel extends Ext_panel_AbstractPanel {
	
	public $height = 250;
	public $width = 450;
	public $title = 'My Panel';
	public $header = null;
	public $closable = null;
	public $collapsible = null;
	public $collapseDirection = null;
	public $collapsed = null;
	public $manageHeight = true;
	public $associations = null;
	
	protected $_metaType = 'panel';
	
}