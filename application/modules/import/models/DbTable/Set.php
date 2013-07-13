<?php

class Import_Model_DbTable_Set extends Zend_Db_Table_Abstract
{

    protected $_name = 'import_set';
    protected $_rowClass = 'Import_Model_Set';

	protected $_referenceMap = array(
		'fk_import_set_import_action1' => array(
			'columns'		=> array('import_action_id'),
			'refTableClass'	=> 'Import_Model_DbTable_Action',
			'refColumns'	=> array('id')
		)
	);

}