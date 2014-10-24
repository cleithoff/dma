<?php

class Package_Model_DbTable_Itemorder extends Rest_Db_Table
{

    protected $_name = 'package_itemorder';
    protected $_rowClass = 'Package_Model_Itemorder';

	protected $_referenceMap = array(
		'package_packageorder' => array(
			'columns'		=> array('package_packageorder_id'),
			'refTableClass'	=> 'Package_Model_DbTable_Packageorder',
			'refColumns'	=> array('id')
		),
		'package_type' => array(
			'columns'		=> array('package_type_id'),
			'refTableClass'	=> 'Package_Model_DbTable_Type',
			'refColumns'	=> array('id')
		)
	);

}