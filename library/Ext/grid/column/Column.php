<?php

class Ext_grid_column_Column extends Ext_grid_header_Container {
	
	public $text = null;
	public $dataIndex = null;
	public $editor = null;
	public $header = null;
	
	
	protected $_metaType = 'gridcolumn';
	protected $_metaReferenceName = 'columns';
	protected $_metaReferenceType = 'array';
	
	
}