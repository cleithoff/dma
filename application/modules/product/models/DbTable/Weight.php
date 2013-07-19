<?php

class Product_Model_DbTable_Weight extends Rest_Db_Table
{

    protected $_name = 'product_weight';
    protected $_rowClass = 'Product_Model_Weight';

	protected $_referenceMap = array(
		'product_item' => array(
			'columns'		=> array('product_item_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Item',
			'refColumns'	=> array('id')
		)
	);

}