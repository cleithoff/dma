<?php

class Product_Model_DbTable_Property extends Rest_Db_Table
{

    protected $_name = 'product_property';
    protected $_rowClass = 'Product_Model_Property';
	protected $_dependentTables = array('product_product_has_product_property');
	protected $_referenceMap = array(
		'fk_product_property_product_datatype1' => array(
			'columns'		=> array('product_datatype_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Datatype',
			'refColumns'	=> array('id')
		)
	);

}