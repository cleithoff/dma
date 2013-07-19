<?php

class Order_Model_DbTable_Pool extends Rest_Db_Table
{

    protected $_name = 'order_pool';
    protected $_rowClass = 'Order_Model_Pool';
	protected $_dependentTables = array('order_cart', 'order_item', 'order_order');


}