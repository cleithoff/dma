<?php

class Report_Model_DbTable_Filteroperator extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_filteroperator';
    protected $_rowClass = 'Report_Model_Filteroperator';
	protected $_dependentTables = array('report_filter');


}