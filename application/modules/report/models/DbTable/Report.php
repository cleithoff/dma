<?php

class Report_Model_DbTable_Report extends Rest_Db_Table
{

    protected $_name = 'report_report';
    protected $_rowClass = 'Report_Model_Report';
	protected $_dependentTables = array('report_filter');

}