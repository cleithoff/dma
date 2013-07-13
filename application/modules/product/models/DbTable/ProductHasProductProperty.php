<?php

class Product_Model_DbTable_ProductHasProductProperty extends Zend_Db_Table_Abstract
{

    protected $_name = 'product_product_has_product_property';
    protected $_rowClass = 'Product_Model_ProductHasProductProperty';

	protected $_referenceMap = array(
		'fk_product_product_has_product_property_product_product1' => array(
			'columns'		=> array('product_product_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Product',
			'refColumns'	=> array('id')
		),
		'fk_product_product_has_product_property_product_property1' => array(
			'columns'		=> array('product_property_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Property',
			'refColumns'	=> array('id')
		)
	);

}