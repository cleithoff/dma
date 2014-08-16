<?php

class Product_Model_DbTable_Producthasproductproperty extends Rest_Db_Table
{

    protected $_name = 'product_product_has_product_property';
    protected $_rowClass = 'Product_Model_Producthasproductproperty';

	protected $_referenceMap = array(
		'product_product' => array(
			'columns'		=> array('product_product_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Product',
			'refColumns'	=> array('id')
		),
		'product_property' => array(
			'columns'		=> array('product_property_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Property',
			'refColumns'	=> array('id')
		)
	);

}