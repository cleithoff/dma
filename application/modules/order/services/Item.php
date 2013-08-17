<?php

class Order_Service_Item
{
	
	protected $_secretKey = '%5Tgh54.7ZfpkurtXwweg7Z8wsDTvJWf';
	
	protected $_authKey = null;
	
	//protected $_order_item = null; // Zend_Db_Table_Row_Abstract;
	
	protected $_product_personalize = array();
	
	protected $_row = null;
	
	protected $_recentRow = null;

	protected $_partner = null;
	
	public function getAuthkey() {
		if (empty($this->_authKey)) {
			$this->_authKey = MD5($this->_row->id . $this->_secretKey);
		}
		return $this->_authKey;
	}

	public function toXml($order_item_id) {
		$xml = array();
		// order_item
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($order_item_id)->current();		
		$xml['order_item'] = $order_item->toArray();
		
		// order_order
		$order_orders = new Order_Model_DbTable_Order();
		$order_order = $order_orders->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('order_pool_id = ?', $order_item->order_pool_id));
		$xml['order_order'] = $order_order->toArray();
		
		// product_personalize
		$sql = 'SELECT order_item_id,product_personalize.key, order_item_has_product_personalize.value FROM order_item_has_product_personalize
			INNER JOIN product_personalize ON order_item_has_product_personalize.product_personalize_id = product_personalize.id
			WHERE order_item_has_product_personalize.order_item_id = ?';		
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($order_item_id))->fetchAll();
		foreach ($rowset as $row) {
			$xml['product_personalize'][$row['key']] = $row['value'];
		}
				
		// partner_partner
		$partner_partners = new Partner_Model_DbTable_Partner();
		$partner_partner = $partner_partners->find($order_order->partner_partner_id)->current();
		$xml['partner_partner'] = $partner_partner->toArray();
		
		// partner_address_invoice
		$partner_addresses = new Partner_Model_DbTable_Address();
		$partner_address = $partner_addresses->find($partner_partner->partner_address_id_invoice)->current();
		$xml['partner_address_invoice'] = $partner_address->toArray();

		// partner_address_delivery
		$partner_addresses = new Partner_Model_DbTable_Address();
		$partner_address = $partner_addresses->find($partner_partner->partner_address_id_delivery)->current();
		$xml['partner_address_delivery'] = $partner_address->toArray();
	
		return Rest_Xml::encode('data', $xml);
	}
	
	public function setRecentRow($row) {
		$t = new Order_Model_DbTable_Item();
		$this->_recentRow = $t->find($row['id'])->current();
		$this->_authKey = null;
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
		
		$sql = 'SELECT `partner_partner`.* FROM `order_order` INNER JOIN `partner_partner` ON `partner_partner`.`id` = `order_order`.`partner_partner_id` WHERE order_pool_id = ?';
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql, array($this->_row['order_pool_id']))->fetchAll();
		$this->_partner = reset($rowset);
		$this->_authKey = null;
	}

	public function createPreview() {
		if (empty($this->_row)) return false;

		$this->getAuthkey();
		
		$xml = $this->toXml($this->_row->id);
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

		Rest_Pdf::toImage(
			APPLICATION_PATH . '/../public/deploy/' . $this->_authKey . '.pdf', 
			'png'
		);
		
		$this->previewpdf = '/' . $this->_authKey . '.pdf';
		$this->previewimage = '/' . $this->_authKey . '.png';
		
		return $this;
	}
	
	public function sendPreview() {		
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
		
		// assign valeues
		$eml->assign('partner', $this->_partner);
		$eml->assign('row', $this->_row);
		
		// render view
		$bodyText = $eml->render('send.phtml');
		
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($this->_partner['email']);
		$mail->setSubject('Druckvorschau');
		
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $this->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $this->getAuthkey() . '.pdf'; //Hint! Hint!
		
		$mail->send();
	}
	
	public function releasePreview() {
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
	
		// assign values
		$eml->assign('partner', $this->_partner);
		$eml->assign('row', $this->_row);
	
		// render view
		$bodyText = $eml->render('release.phtml');
	
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($this->_partner['email']);
		$mail->setSubject('Druckvorschau');
	
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $this->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $this->getAuthkey() . '.pdf'; //Hint! Hint!
	
		$mail->send();
	}
	
	public function checkState() {
		if ($this->_row['order_itemstate_id'] != $this->_recentRow['order_itemstate_id']) {
			$itemStateService = new Order_Service_Itemstate();
			$this->processState($this->_row);
		}
	}
	
	protected function processState() {
	
		$this->logState($this->_row['id'], $this->_row['order_itemstate_id']);
		
		switch ($this->_row['order_itemstate_id']) {
			case Order_Service_Itemstate::ORDER_ITEM_STATE_NEW:
				return $this->processStateNew();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASE:
				return $this->processStateRelease();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_DENY:
				return $this->processStateDeny();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED:
				return $this->processStateReleased();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_PRODUCTION:
				return $this->processStateProduction();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_DELIVERY:
				return $this->processStateDelivery();
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_FINISHED:
				return $this->processStateFinished();
				break;
		}
	}
	
	protected function processStateNew() {
	
	}
	
	protected function processStateRelease() {
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
	
		// assign valeues
		$eml->assign('partner', $this->_partner);
		$eml->assign('row', $this->_row);
		$eml->assign('authKey', $this->getAuthkey());
	
		// render view
		$bodyText = $eml->render('release.phtml');
	
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($this->_partner['email']);
		$mail->setSubject('Druckvorschau');
	
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $this->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $this->getAuthkey() . '.pdf'; //Hint! Hint!
	
		$mail->send();
	}
	
	protected function processStateDeny() {
	
	}
	
	protected function processStateReleased() {
	
	}
	
	protected function processStateProduction() {
	
	}
	
	protected function processStateDelivery() {
	
	}
	
	protected function processStateFinished() {
	
	}
	
	protected function logState($order_item_id, $state, $comment = null) {
		
		$data = array(
				'order_item_id' => $order_item_id,
				'order_itemstate_id' => intval($state),
				'datetime' => date('Y-m-d H:i:s'),
				'comment' => $comment,
		);
		$orderItemStateLogMapper = new Order_Model_DbTable_Itemstatelog();
		$orderItemStateLog = $orderItemStateLogMapper->createRow($data);
		return $orderItemStateLog->save();		
	}
		
	public function changeState($authKey, $state, $comment = null) {
		$t = new Order_Model_DbTable_Item();
		$orderItem = $t->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('authkey = ?', $authKey));
		if (is_object($orderItem)) {
			$orderItem->order_itemstate_id = $state;
			$orderItem->save();
			return $this->logState($orderItem->id, $state, $comment);
		}
		return false;
	}

}