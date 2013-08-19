<?php

class Order_Model_Item extends Rest_Model_DbRow
{

	protected $_secretkey = '%5Tgh54.7ZfpkurtXwweg7Z8wsDTvJWf';
	
	protected $_order_order = null;
	protected $_product_personalize = null;
	protected $_product_item = null;
	
	/**
	 * @return string
	 */
	public function getAuthkey() {
		if (empty($this->authkey)) {
			$this->authkey = MD5($this->id . $this->_secretkey);
		}
		return $this->authkey;
	}
	
	/**
	 * 
	 * @param bool $refresh
	 * @return Order_Model_Order
	 */
	public function getOrderOrder(bool $refresh = null) {
		if (empty($this->_order_order) || $refresh) {
			$order_orders = new Order_Model_DbTable_Order();
			$this->_order_order = $order_orders->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('order_pool_id = ?', $this->order_pool_id));			
		}
		return $this->_order_order;
	}
	
	/**
	 * 
	 * @param integer $id
	 * @return Order_Model_Itemhasproductpersonalize <Zend_Db_Table_Row_Abstract, NULL, multitype:>
	 */
	public function getOrderItemHasProductPersonalize($id) {
		$order_item_has_product_personalizes = new Order_Model_DbTable_Itemhasproductpersonalize();
		return $order_item_has_product_personalizes->find($id)->current();
	}
	
	/**
	 * 
	 * @param bool $refresh
	 * @return array
	 */
	public function getProductPersonalize(bool $refresh = null) {
		if (empty($this->_product_personalize) || $refresh) {
			$this->_product_personalize = array();
			$sql = 'SELECT order_item_id,product_personalize.key, order_item_has_product_personalize.value FROM order_item_has_product_personalize
				INNER JOIN product_personalize ON order_item_has_product_personalize.product_personalize_id = product_personalize.id
				WHERE order_item_has_product_personalize.order_item_id = ?';
			$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($this->id))->fetchAll();
			foreach ($rowset as $row) {
				$this->_product_personalize[$row['key']] = $row['value'];
			}
		}
		return $this->_product_personalize;
	}
	
	public function setProductPersonalize(array $values = array()) {
		$sql = 'SELECT order_item_id,product_personalize.key, order_item_has_product_personalize.value, order_item_has_product_personalize.id FROM order_item_has_product_personalize
				INNER JOIN product_personalize ON order_item_has_product_personalize.product_personalize_id = product_personalize.id
				WHERE order_item_has_product_personalize.order_item_id = ?';
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($this->id))->fetchAll();
		foreach ($rowset as $row) {
			$product_personalize[$row['key']] = $row;
		}
		
		foreach ($values as $key => $value) {
			if (array_key_exists($key, $product_personalize)) {
				$row = $product_personalize[$key];
				$order_item_has_product_personalize = $this->getOrderItemHasProductPersonalize($row['id']);
				$order_item_has_product_personalize->value = $value;
				$order_item_has_product_personalize->save();
			}
		}
	}
	
	/**
	 * 
	 * @param bool $refresh
	 * @return Product_Model_Item
	 */
	public function getProductItem(bool $refresh = null) {
		if (empty($this->_product_item) || $refresh) {
			$product_items = new Product_Model_DbTable_Item();
			$this->_product_item = $product_items->find($this->product_item_id)->current();			
		}
		return $this->_product_item;
	}
	
}

