<?php

class Order_Service_Item
{
	
	protected $_secretKey = '%5Tgh54.7ZfpkurtXwweg7Z8wsDTvJWf';
	
	protected $_authKey = null;
	
	protected $_order_item = null; // Zend_Db_Table_Row_Abstract;
	
	protected $_product_personalize = array();

	public function getAuthkey() {
		if (empty($this->_authKey)) {
			$this->_authKey = MD5($this->_row->id . $this->_secretKey);
		}
		return $this->_authKey;
	}

	public function toXml() {
		$addressprint = array(
				'addresslines' => array(
						'addressline' => array(
								$this->_product_personalize['line1'],
								$this->_product_personalize['line2'],
								$this->_product_personalize['line3'],
								$this->_product_personalize['line4'],
								$this->_product_personalize['line5'],
								$this->_product_personalize['line6'],
								$this->_product_personalize['line7'],
						)
				)
		);
		return Rest_Xml::encode('addressprint', $addressprint);
	}
	
	public function setRow($row) {
		$t = new Order_Model_DbTable_Item();
		$this->_row = $t->find($row['id'])->current();
		
		$this->_product_personalize = array();
		$sql = 'SELECT order_item_id,product_personalize.key, order_item_has_product_personalize.value FROM order_item_has_product_personalize
			INNER JOIN product_personalize ON order_item_has_product_personalize.product_personalize_id = product_personalize.id
			WHERE order_item_has_product_personalize.order_item_id = ?';
		
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($row['id']))->fetchAll();
		
		foreach ($rowset as $row) {
			$this->_product_personalize[$row['key']] = $row['value'];
		}
		$this->_authKey = null;
	}

	public function createPreview() {
		if (empty($this->_row)) return false;

		$this->getAuthkey();
		
		$xml = $this->toXml();
		$xml->save(APPLICATION_PATH . '/../resource/xml/' . $this->_authKey . '.xml');
		Rest_Pdf::fop(
			APPLICATION_PATH . '/../resource/xml/' . $this->_authKey . '.xml',
			APPLICATION_PATH . '/../resource/addressprint.xsl',
			APPLICATION_PATH . '/../resource/pdf/' . $this->_authKey . '.pdf'
		);

		Rest_Pdf::overlay(
			APPLICATION_PATH . '/../resource/pdf/' . $this->_authKey . '.pdf',
			APPLICATION_PATH . '/../resource/addressprint.pdf',
			APPLICATION_PATH . '/../public/deploy/' . $this->_authKey . '.pdf'
		);
		$this->previewpdf = '/' . $this->_authKey . '.pdf';

		Rest_Pdf::toImage(
			APPLICATION_PATH . '/../public/release/' . $this->_authKey . '.pdf', 
			'png'
		);
		
		$this->previewpdf = '/' . $this->_authKey . '.pdf';
		$this->previewimage = '/' . $this->_authKey . '.png';
		
		return $this;
	}	

}