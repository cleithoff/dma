<?php

class Product_Model_DbTable_LayoutHasProductPersonalize extends Rest_Db_Table
{

    protected $_name = 'product_layout_has_product_personalize';
    protected $_rowClass = 'Product_Model_Layouthasproductpersonalize';

	protected $_referenceMap = array(
		'product_layout' => array(
			'columns'		=> array('product_layout_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Layout',
			'refColumns'	=> array('id')
		),
		'product_personalize' => array(
			'columns'		=> array('product_personalize_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Personalize',
			'refColumns'	=> array('id')
		)
	);

}