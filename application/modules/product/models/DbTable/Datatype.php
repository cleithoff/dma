<?php

class Product_Model_DbTable_Datatype extends Rest_Db_Table
{

    protected $_name = 'product_datatype';
    protected $_rowClass = 'Product_Model_Datatype';
	protected $_dependentTables = array('product_customize', 'product_personalize', 'product_property');


}