<?php

class Package_Model_DbTable_Package extends Rest_Db_Table
{

    protected $_name = 'package_package';
    protected $_rowClass = 'Package_Model_Package';

	protected $_referenceMap = array(
		'frame_type' => array(
			'columns'		=> array('frame_type_id'),
			'refTableClass'	=> 'Frame_Model_DbTable_Type',
			'refColumns'	=> array('id')
		),
		'order_item' => array(
			'columns'		=> array('order_item_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Item',
			'refColumns'	=> array('id')
		),
	);

}