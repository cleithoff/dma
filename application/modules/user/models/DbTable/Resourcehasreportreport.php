<?php

class User_Model_DbTable_Resourcehasreportreport extends Rest_Db_Table
{

    protected $_name = 'user_resource_has_report_report';
    protected $_rowClass = 'User_Model_Resourcehasreportreport';
    
    protected $_referenceMap = array(
    		'user_resource' => array(
    				'columns'		=> array('user_resource_id'),
    				'refTableClass'	=> 'User_Model_DbTable_Resource',
    				'refColumns'	=> array('id')
    		),
    		'report_report' => array(
    				'columns'		=> array('report_report_id'),
    				'refTableClass'	=> 'Report_Model_DbTable_Report',
    				'refColumns'	=> array('id')
    		),
    );

}

