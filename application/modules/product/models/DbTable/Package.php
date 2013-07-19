<?php

class Product_Model_DbTable_Package extends Rest_Db_Table
{

    protected $_name = 'product_package';
    protected $_rowClass = 'Product_Model_Package';
	protected $_dependentTables = array('product_product');


}