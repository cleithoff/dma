<?php

class Product_Model_DbTable_Item extends Rest_Db_Table
{

    protected $_name = 'product_item';
    protected $_rowClass = 'Product_Model_Item';
	protected $_dependentTables = array('order_item', 'product_item_has_product_customize');
	protected $_referenceMap = array(
		'fk_product_item_product_layout1' => array(
			'columns'		=> array('product_layout_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Layout',
			'refColumns'	=> array('id')
		),
		'fk_product_item_product_product1' => array(
			'columns'		=> array('product_product_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Product',
			'refColumns'	=> array('id')
		)
	);

}