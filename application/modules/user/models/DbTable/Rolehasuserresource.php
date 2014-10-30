<?php

class User_Model_DbTable_Rolehasuserresource extends Rest_Db_Table
{

    protected $_name = 'user_role_has_user_resource';
    protected $_rowClass = 'User_Model_Rolehasuserresource';

    protected $_referenceMap = array(
    		'user_role' => array(
    				'columns'		=> array('user_role_id'),
    				'refTableClass'	=> 'User_Model_DbTable_Role',
    				'refColumns'	=> array('id')
    		),
    		'user_resource' => array(
    				'columns'		=> array('user_resource_id'),
    				'refTableClass'	=> 'User_Model_DbTable_Resource',
    				'refColumns'	=> array('id')
    		),
    );

}