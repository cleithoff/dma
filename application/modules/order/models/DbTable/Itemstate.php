<?php

class Order_Model_DbTable_Itemstate extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_itemstate';
    protected $_rowClass = 'Order_Model_Itemstate';
	protected $_dependentTables = array('order_item', 'order_itemstate');
	protected $_referenceMap = array(
		'fk_order_itemstate_order_itemstate1' => array(
			'columns'		=> array('order_itemstate_id_prev'),
			'refTableClass'	=> 'Order_Model_DbTable_Itemstate',
			'refColumns'	=> array('id')
		),
		'fk_order_itemstate_order_itemstate2' => array(
			'columns'		=> array('order_itemstate_id_next'),
			'refTableClass'	=> 'Order_Model_DbTable_Itemstate',
			'refColumns'	=> array('id')
		)
	);

}