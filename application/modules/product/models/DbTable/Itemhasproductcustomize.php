<?php

class Product_Model_DbTable_Itemhasproductcustomize extends Rest_Db_Table
{

    protected $_name = 'product_item_has_product_customize';
    protected $_rowClass = 'Product_Model_Itemhasproductcustomize';

	protected $_referenceMap = array(
		'product_customize' => array(
			'columns'		=> array('product_customize_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Customize',
			'refColumns'	=> array('id')
		),
		'product_item' => array(
			'columns'		=> array('product_item_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Item',
			'refColumns'	=> array('id')
		)
	);

}