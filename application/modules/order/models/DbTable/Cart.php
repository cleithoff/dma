<?php

class Order_Model_DbTable_Cart extends Rest_Db_Table
{

    protected $_name = 'order_cart';
    protected $_rowClass = 'Order_Model_Cart';

	protected $_referenceMap = array(
		'fk_order_cart_order_pool1' => array(
			'columns'		=> array('order_pool_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Pool',
			'refColumns'	=> array('id')
		)
	);

}