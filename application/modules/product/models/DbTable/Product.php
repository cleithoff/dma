<?php

class Product_Model_DbTable_Product extends Zend_Db_Table_Abstract
{

    protected $_name = 'product_product';
    protected $_rowClass = 'Product_Model_Product';
	protected $_dependentTables = array('product_item', 'product_product_has_product_category', 'product_product_has_product_property');
	protected $_referenceMap = array(
		'fk_product_product_product_currency1' => array(
			'columns'		=> array('idproduct_currency'),
			'refTableClass'	=> 'Product_Model_DbTable_Currency',
			'refColumns'	=> array('id')
		),
		'fk_product_product_product_package1' => array(
			'columns'		=> array('idproduct_package'),
			'refTableClass'	=> 'Product_Model_DbTable_Package',
			'refColumns'	=> array('id')
		)
	);

}