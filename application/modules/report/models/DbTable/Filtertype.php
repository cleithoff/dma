<?php

class Report_Model_DbTable_Filtertype extends Zend_Db_Table_Abstract
{

    protected $_name = 'report_filtertype';
    protected $_rowClass = 'Report_Model_Filtertype';
	protected $_dependentTables = array('report_filter');


}