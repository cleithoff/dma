<?php

class Product_Model_Item extends Rest_Model_DbRow
{

	protected $_product_layout = null;
	
	/**
	 *
	 * @param bool $refresh
	 * @return Product_Model_Layout
	 */
	public function getProductLayout(bool $refresh = null) {
		if (empty($this->_product_layout) || $refresh) {
			$product_layouts = new Product_Model_DbTable_Layout();
			$this->_product_layout = $product_layouts->find($this->product_layout_id)->current();
		}
		return $this->_product_layout;
	}
	
	/**
	 *
	 * @param bool $refresh
	 * @return Product_Model_Product
	 */
	public function getProductProduct(bool $refresh = null) {
		if (empty($this->_product_product) || $refresh) {
			$product_products = new Product_Model_DbTable_Product();
			$this->_product_product = $product_products->find($this->product_product_id)->current();
		}
		return $this->_product_product;
	}
	
}

