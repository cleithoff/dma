<?php

class Ext_panel_Table extends Ext_panel_Panel {
	
	public $store = null;
	public $columns = array();
	public $viewConfig = array();
	
	public function addColumn(Ext_grid_header_Container $column) {
		array_push($this->columns, $column);
		return $column;
	}
	
}