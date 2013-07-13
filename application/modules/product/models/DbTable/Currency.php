<?php

class Product_Model_DbTable_Currency extends Zend_Db_Table_Abstract
{

    protected $_name = 'product_currency';
    protected $_rowClass = 'Product_Model_Currency';
	protected $_dependentTables = array('product_product', 'user_credit');


}