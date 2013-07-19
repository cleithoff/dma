<?php

class Import_Model_DbTable_Ordercompare extends Rest_Db_Table
{

    protected $_name = 'import_ordercompare';
    protected $_rowClass = 'Import_Model_Ordercompare';
	protected $_index = array(
			'unique' => array(
					'import_ordercompare_unique' => array(
							'partner_nr' => 'partner_nr',
							'key' => 'key'
					),
			)
	);

}