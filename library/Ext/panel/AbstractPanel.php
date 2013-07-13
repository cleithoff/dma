<?php

class Ext_panel_AbstractPanel extends Ext_container_Container {
	
	
	public $bodyPadding = 0;
	public $dockedItems = array();
	
	public function addDocked($component) {
		array_push($this->dockedItems, $component);
		return $component;
	}
	
}