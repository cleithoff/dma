<?php

class Product_Model_DbTable_Package extends Zend_Db_Table_Abstract
{

    protected $_name = 'product_package';
    protected $_rowClass = 'Product_Model_Package';
	protected $_dependentTables = array('product_product');


}