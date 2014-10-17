<?php

class Order_Model_DbTable_Order extends Rest_Db_Table
{

    protected $_name = 'order_order';
    protected $_rowClass = 'Order_Model_Order';

	protected $_referenceMap = array(
		'order_pool' => array(
			'columns'		=> array('order_pool_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Pool',
			'refColumns'	=> array('id')
		),
		'order_state' => array(
			'columns'		=> array('order_state_id'),
			'refTableClass'	=> 'Order_Model_DbTable_State',
			'refColumns'	=> array('id')
		),
		'partner_partner' => array(
			'columns'		=> array('partner_partner_id'),
			'refTableClass'	=> 'Partner_Model_DbTable_Partner',
			'refColumns'	=> array('id')
		),
		'import_import' => array(
				'columns'		=> array('import_import_id'),
				'refTableClass'	=> 'Import_Model_DbTable_Import',
				'refColumns'	=> array('id')
		),
		'import_stack' => array(
				'columns'		=> array('import_stack_id'),
				'refTableClass'	=> 'Import_Model_DbTable_Stack',
				'refColumns'	=> array('id')
		),
	);

}