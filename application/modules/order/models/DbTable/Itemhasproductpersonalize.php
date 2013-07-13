<?php

class Order_Model_DbTable_Itemhasproductpersonalize extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_item_has_product_personalize';
    protected $_rowClass = 'Order_Model_Itemhasproductpersonalize';

	protected $_referenceMap = array(
		'order_item' => array(
			'columns'		=> array('order_item_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Item',
			'refColumns'	=> array('id')
		),
		'product_personalize' => array(
			'columns'		=> array('product_personalize_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Personalize',
			'refColumns'	=> array('id')
		)
	);

}