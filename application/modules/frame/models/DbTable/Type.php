<?php

class Frame_Model_DbTable_Type extends Rest_Db_Table
{

    protected $_name = 'frame_type';
    protected $_rowClass = 'Frame_Model_Type';
	protected $_dependentTables = array('package_package');


}