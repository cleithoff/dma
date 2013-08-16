<?php

class Order_Model_DbTable_Itemstatelog extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_itemstatelog';
    protected $_rowClass = 'Order_Model_Itemstatelog';

	protected $_referenceMap = array(
		'fk_user_user_order_itemstate' => array(
			'columns'		=> array('user_user_id'),
			'refTableClass'	=> 'User_Model_DbTable_User',
			'refColumns'	=> array('id')
		),
		'fk_order_itemstate' => array(
			'columns'		=> array('order_itemstate_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Itemstate',
			'refColumns'	=> array('id')
		)
	);

}