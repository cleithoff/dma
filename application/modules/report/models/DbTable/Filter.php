<?php

class Report_Model_DbTable_Filter extends Rest_Db_Table
{

    protected $_name = 'report_filter';
    protected $_rowClass = 'Report_Model_Filter';

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
		'report_report' => array(
			'columns'		=> array('report_report_id'),
			'refTableClass'	=> 'Report_Model_DbTable_Report',
			'refColumns'	=> array('id')
		)
	);

}