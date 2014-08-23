<?php

class Order_Model_DbTable_Meta extends Zend_Db_Table_Abstract
{

    protected $_name = 'order_meta';
    protected $_rowClass = 'Order_Model_Meta';
    protected $_dependentTables = array('order_item_has_order_meta');
    

}

