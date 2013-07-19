<?php

class Product_Model_DbTable_Customize extends Rest_Db_Table
{

    protected $_name = 'product_customize';
    protected $_rowClass = 'Product_Model_Customize';
	protected $_dependentTables = array('product_item_has_product_customize');
	protected $_referenceMap = array(
		'fk_product_customize_product_datatype1' => array(
			'columns'		=> array('product_datatype_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Datatype',
			'refColumns'	=> array('id')
		)
	);

}