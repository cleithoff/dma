<?php

class Ext_Action extends Ext_Base {
	
	public $text = null;
	public $handler = null;
	public $iconCls = null;
	
	protected $_metaReferenceName = 'listeners';
	protected $_metaReferenceType = 'array';
	
                //"designer|userClassName": "onMenuitemClick",
    public $fn = null;
    public $implHandler = null;
    public $name = null; //"click"
    public $scope = "me";
    public $targetType = null; //"Ext.menu.Item"
    public $controlQuery = null; // "menuitem"
    //"designer|targetType": "Ext.menu.Item",
    //"designer|controlQuery": "menuitem"
        /*
                "implHandler": [
                    "//Ext.getCmp('MainTabPanel').add(Ext.widget(item.href.replace('#','')));\r"
                ],
*/

	protected $_metaType = 'action';
	
}