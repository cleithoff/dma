<?php

class Product_Model_DbTable_Layout extends Rest_Db_Table
{

    protected $_name = 'product_layout';
    protected $_rowClass = 'Product_Model_Layout';
	protected $_dependentTables = array('product_item', 'product_layout_has_product_personalize');


}