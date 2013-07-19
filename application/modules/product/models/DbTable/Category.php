<?php

class Product_Model_DbTable_Category extends Rest_Db_Table
{

    protected $_name = 'product_category';
    protected $_rowClass = 'Product_Model_Category';
	protected $_dependentTables = array('product_category', 'product_product_has_product_category');
	protected $_referenceMap = array(
		'fk_product_category_product_category' => array(
			'columns'		=> array('product_category_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Category',
			'refColumns'	=> array('id')
		)
	);

}