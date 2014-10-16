<?php

class Order_Model_DbTable_Combineitem extends Rest_Db_Table
{

    protected $_name = 'order_combineitem';
    protected $_rowClass = 'Order_Model_Combine';

	protected $_referenceMap = array(
		'order_combine' => array(
				'columns'		=> array('order_combine_id'),
				'refTableClass'	=> 'Order_Model_DbTable_Combine',
				'refColumns'	=> array('id')
		),
		'product_product' => array(
				'columns'		=> array('product_product_id'),
				'refTableClass'	=> 'Product_Model_DbTable_Product',
				'refColumns'	=> array('id')
		),
		'product_item' => array(
				'columns'		=> array('product_item_id'),
				'refTableClass'	=> 'Product_Model_DbTable_Item',
				'refColumns'	=> array('id')
		),
	);

}