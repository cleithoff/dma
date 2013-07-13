<?php

class Frame_Model_DbTable_Type extends Zend_Db_Table_Abstract
{

    protected $_name = 'frame_type';
    protected $_rowClass = 'Frame_Model_Type';
	protected $_dependentTables = array('package_package');


}