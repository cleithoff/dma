<?php

class Ext_form_field_ComboBox extends Ext_form_field_Picker {
	
	public $displayField = null;
	public $store = null;
	public $valueField = null;
	public $queryParam = 'query';
	public $allQuery = null;
	
	protected $_metaType = 'combobox';
	
}