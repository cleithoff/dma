<?php

class Package_Model_DbTable_Packageorder extends Rest_Db_Table
{

    protected $_name = 'package_packageorder';
    protected $_rowClass = 'Package_Model_Packageorder';

	protected $_referenceMap = array(
		'frame_type' => array(
			'columns'		=> array('frame_type_id'),
			'refTableClass'	=> 'Frame_Model_DbTable_Type',
			'refColumns'	=> array('id')
		),
		'order_combine' => array(
			'columns'		=> array('order_combine_id'),
			'refTableClass'	=> 'Order_Model_DbTable_Combine',
			'refColumns'	=> array('id')
		),
	);

}