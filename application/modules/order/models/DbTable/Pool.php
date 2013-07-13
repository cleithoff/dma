<?php

class Order_Model_DbTable_Pool extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_pool';
    protected $_rowClass = 'Order_Model_Pool';
	protected $_dependentTables = array('order_cart', 'order_item', 'order_order');


}