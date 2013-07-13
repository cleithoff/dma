<?php

class Product_Model_DbTable_Datatype extends Zend_Db_Table_Abstract
{

    protected $_name = 'product_datatype';
    protected $_rowClass = 'Product_Model_Datatype';
	protected $_dependentTables = array('product_customize', 'product_personalize', 'product_property');


}