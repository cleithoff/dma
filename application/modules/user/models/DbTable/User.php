<?php

class User_Model_DbTable_User extends Rest_Db_Table
{

    protected $_name = 'user_user';
    protected $_rowClass = 'User_Model_User';

    protected $_referenceMap = array(
    		'user_role' => array(
    				'columns'		=> array('user_role_id'),
    				'refTableClass'	=> 'User_Model_DbTable_Role',
    				'refColumns'	=> array('id')
    		),
    );

}