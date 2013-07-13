<?php

class Product_Model_DbTable_Weight extends Zend_Db_Table_Abstract
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