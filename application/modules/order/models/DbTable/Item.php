<?php

class Order_Model_DbTable_Item extends Rest_Db_Table
{

    protected $_name = 'order_item';
    protected $_rowClass = 'Order_Model_Item';
	protected $_dependentTables = array('order_item_has_product_personalize', 'package_package');
	protected $_referenceMap = array(
		'order_itemstate' => array(
			'columns'		=> array('order_itemstate_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Itemstate',
			'refColumns'	=> array('id')
		),
		'order_pool' => array(
			'columns'		=> array('order_pool_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Pool',
			'refColumns'	=> array('id')
		),
		'product_item' => array(
			'columns'		=> array('product_item_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Item',
			'refColumns'	=> array('id')
		),
		'product_product' => array(
				'columns'		=> array('product_product_id'),
				'refTableClass'	=> 'Product_Model_DbTable_Product',
				'refColumns'	=> array('id')
		),
		'partner_partner' => array(
				'columns'		=> array('partner_partner_id'),
				'refTableClass'	=> 'Partner_Model_DbTable_Partner',
				'refColumns'	=> array('id')
		),			
		'order_order' => array(
				'columns'		=> array('order_order_id'),
				'refTableClass'	=> 'Order_Model_DbTable_Order',
				'refColumns'	=> array('id')
		),		
		'import_stack' => array(
				'columns'		=> array('import_stack_id'),
				'refTableClass'	=> 'Import_Model_DbTable_Stack',
				'refColumns'	=> array('id')
		),
	);

}