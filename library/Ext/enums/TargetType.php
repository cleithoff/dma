<?php 

class Ext_enums_TargetType {
	
	public static $enum = array(
			'Ext.menu.Item' => array(
					'click' => 'item, e, options',
					),
			'Ext.button.Button' => array(
					'click' => 'button, e, options',
					'toggle' => 'button, pressed, options',
					),
			'Ext.grid.Panel' => array(
					'select' => 'selModel, record, index, options',
					'loadrecord' => 'grid, selModel, record, index, options',
					),
			'Ext.form.Panel' => array(
					'loadrecord' => 'form, selModel, record, index, options',
					),				
			'Ext.form.field.ComboBox' => array(
					'beforeactivate' => 'combo, eOpts',
					'beforequery' => 'queryEvent, eOpts',
					'select' => 'combo, records, options',
			),
	);
	

}