<?php

class Partner_Model_DbTable_Absence extends Rest_Db_Table
{

    protected $_name = 'partner_absence';
    protected $_rowClass = 'Partner_Model_Absence';

    protected $_referenceMap = array(
		'partner_partner' => array(
				'columns'		=> array('partner_partner_id'),
				'refTableClass'	=> 'Partner_Model_DbTable_Partner',
				'refColumns'	=> array('id')
		),	
    );

}