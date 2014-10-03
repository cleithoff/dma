<?php

class Report_Model_DbTable_Additional extends Rest_Db_Table
{

    protected $_name = 'report_additional';
    protected $_rowClass = 'Report_Model_Additional';

	protected $_referenceMap = array(
		'report_report' => array(
			'columns'		=> array('report_report_id'),
			'refTableClass'	=> 'Report_Model_DbTable_Report',
			'refColumns'	=> array('id')
		)
	);

}