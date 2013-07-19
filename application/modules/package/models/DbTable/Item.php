<?php

class Package_Model_DbTable_Item extends Rest_Db_Table
{

    protected $_name = 'package_item';
    protected $_rowClass = 'Package_Model_Item';

	protected $_referenceMap = array(
		'package_package' => array(
			'columns'		=> array('package_package_id'),
			'refTableClass'	=> 'Package_Model_DbTable_Package',
			'refColumns'	=> array('id')
		),
		'package_type' => array(
			'columns'		=> array('package_type_id'),
			'refTableClass'	=> 'Package_Model_DbTable_Type',
			'refColumns'	=> array('id')
		)
	);

}