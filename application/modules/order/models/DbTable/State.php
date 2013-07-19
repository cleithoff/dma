<?php

class Order_Model_DbTable_State extends Rest_Db_Table
{

    protected $_name = 'order_state';
    protected $_rowClass = 'Order_Model_State';
	protected $_dependentTables = array('order_order', 'order_state');
	protected $_referenceMap = array(
		'fk_order_state_order_state1' => array(
			'columns'		=> array('order_state_id_prev'),
			'refTableClass'	=> 'Order_Model_DbTable_State',
			'refColumns'	=> array('id')
		),
		'fk_order_state_order_state2' => array(
			'columns'		=> array('order_state_id_next'),
			'refTableClass'	=> 'Order_Model_DbTable_State',
			'refColumns'	=> array('id')
		)
	);

}