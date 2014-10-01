<?php

class Product_Model_DbTable_Barcode extends Rest_Db_Table
{

    protected $_name = 'product_barcode';
    protected $_rowClass = 'Product_Model_Barcode';
	protected $_dependentTables = array('order_item');
	protected $_referenceMap = array(
		'order_item' => array(
			'columns'		=> array('order_item_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Item',
			'refColumns'	=> array('id')
		)
	);

}