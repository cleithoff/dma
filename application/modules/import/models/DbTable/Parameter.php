<?php

class Import_Model_DbTable_Parameter extends Rest_Db_Table
{

    protected $_name = 'import_parameter';
    protected $_rowClass = 'Import_Model_Parameter';

	protected $_referenceMap = array(
		'report_filtertype' => array(
			'columns'		=> array('report_filtertype_id'),
			'refTableClass'	=> 'Report_Model_DbTable_Filtertype',
			'refColumns'	=> array('id')
		),
		'report_filteroperator' => array(
			'columns'		=> array('report_filteroperator_id'),
			'refTableClass'	=> 'Report_Model_DbTable_Filteroperator',
			'refColumns'	=> array('id')
		),
		'import_import' => array(
			'columns'		=> array('import_import_id'),
			'refTableClass'	=> 'Import_Model_DbTable_Import',
			'refColumns'	=> array('id')
		)
	);

}