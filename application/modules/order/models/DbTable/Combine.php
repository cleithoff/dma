<?php

class Order_Model_DbTable_Combine extends Rest_Db_Table
{

    protected $_name = 'order_combine';
    protected $_rowClass = 'Order_Model_Combine';

	protected $_referenceMap = array(
		/*'item_stack' => array(
			'columns'		=> array('item_stack_id'),
			'refTableClass'	=> 'Item_Model_DbTable_Stack',
			'refColumns'	=> array('id')
		),*/
		'partner_partner' => array(
				'columns'		=> array('partner_partner_id'),
				'refTableClass'	=> 'Partner_Model_DbTable_Partner',
				'refColumns'	=> array('id')
		),
	);

}