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
	);

}