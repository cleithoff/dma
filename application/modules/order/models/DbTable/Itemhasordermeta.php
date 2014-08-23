<?php

class Order_Model_DbTable_Itemhasordermeta extends Rest_Db_Table
{

    protected $_name = 'order_item_has_order_meta';
    protected $_rowClass = 'Order_Model_Itemhasordermeta';
    
    protected $_referenceMap = array(
    		'order_item' => array(
    				'columns'		=> array('order_item_id'),
    				'refTableClass'	=> 'Order_Model_DbTable_Item',
    				'refColumns'	=> array('id')
    		),
    		'order_meta' => array(
    				'columns'		=> array('order_meta_id'),
    				'refTableClass'	=> 'Order_Model_DbTable_Meta',
    				'refColumns'	=> array('id')
    		),
    );

}

