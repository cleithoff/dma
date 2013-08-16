<?php

class Order_Model_DbTable_Statelog extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_statelog';
    protected $_rowClass = 'Order_Model_Statelog';

	protected $_referenceMap = array(
		'fk_order_state' => array(
			'columns'		=> array('order_orderstate_id'),
			'refTableClass'	=> 'Order_Model_DbTable_State',
			'refColumns'	=> array('id')
		),
		'fk_user_user' => array(
			'columns'		=> array('user_user_id'),
			'refTableClass'	=> 'User_Model_DbTable_User',
			'refColumns'	=> array('id')
		)
	);

}