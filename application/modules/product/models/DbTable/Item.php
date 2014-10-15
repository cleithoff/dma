<?php

class Product_Model_DbTable_Item extends Rest_Db_Table
{

    protected $_name = 'product_item';
    protected $_rowClass = 'Product_Model_Item';
	protected $_dependentTables = array('order_item', 'product_item_has_product_customize');
	protected $_referenceMap = array(
		'product_layout' => array(
			'columns'		=> array('product_layout_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Layout',
			'refColumns'	=> array('id')
		),
		'product_product' => array(
			'columns'		=> array('product_product_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Product',
			'refColumns'	=> array('id')
		)
	);

}