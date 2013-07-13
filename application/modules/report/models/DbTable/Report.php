<?php

class Report_Model_DbTable_Report extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_report';
    protected $_rowClass = 'Report_Model_Report';
	protected $_dependentTables = array('report_filter');


}