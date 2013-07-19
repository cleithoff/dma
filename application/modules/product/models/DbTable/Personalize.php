<?php

class Product_Model_DbTable_Personalize extends Rest_Db_Table
{

    protected $_name = 'product_personalize';
    protected $_rowClass = 'Product_Model_Personalize';
	protected $_dependentTables = array('order_item_has_product_personalize', 'product_layout_has_product_personalize');
	protected $_referenceMap = array(
		'fk_product_personalize_product_datatype1' => array(
			'columns'		=> array('product_datatype_id'),
			'refTableClass'	=> 'Product_Model_DbTable_Datatype',
			'refColumns'	=> array('id')
		)
	);

}