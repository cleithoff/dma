<?php

class User_Model_DbTable_Resource extends Rest_Db_Table
{

    protected $_name = 'user_resource';
    protected $_rowClass = 'User_Model_Resource';

    protected $_dependentTables = array('user_role_has_user_resource');

}