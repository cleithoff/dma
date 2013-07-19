<?php

class Product_Model_DbTable_ProductHasProductCategory extends Rest_Db_Table
{

    protected $_name = 'product_product_has_product_category';
    protected $_rowClass = 'Product_Model_ProductHasProductCategory';

	protected $_referenceMap = array(
		'fk_product_product_has_product_category_product_category1' => array(
			'columns'		=> array('product_category_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Category',
			'refColumns'	=> array('id')
		),
		'fk_product_product_has_product_category_product_product1' => array(
			'columns'		=> array('product_product_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Product',
			'refColumns'	=> array('id')
		)
	);

}