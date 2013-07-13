<?php

class Order_Model_DbTable_Order extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_order';
    protected $_rowClass = 'Order_Model_Order';

	protected $_referenceMap = array(
		'fk_order_order_order_pool1' => array(
			'columns'		=> array('order_pool_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Pool',
			'refColumns'	=> array('id')
		),
		'fk_order_order_order_state1' => array(
			'columns'		=> array('order_state_id'),
			'refTableClass'	=> 'Order_Model_DbTable_State',
			'refColumns'	=> array('id')
		),
		'partner_partner' => array(
			'columns'		=> array('partner_partner_id'),
			'refTableClass'	=> 'Partner_Model_DbTable_Partner',
			'refColumns'	=> array('id')
		)
	);

}