<?php

class Order_Service_Item
{
	
	/**
	 * 
	 * @param Order_Model_Item $order_item
	 * @param array $product_personalize_override
	 * @return Ambigous <DomDocument, NULL>
	 */
	public function toXml($order_item, array $product_personalize_overide = array()) {
		$xml = array();
		// order_item
		$xml['order_item'] = $order_item->toArray();
		
		// order_order
		$order_order = $order_item->getOrderOrder();
		$xml['order_order'] = $order_order->toArray();
		
		// product_personalize
		$xml['product_personalize'] = $order_item->getProductPersonalize();		
		$xml['product_personalize'] = array_merge($xml['product_personalize'], $product_personalize_overide);
		
		// partner_partner
		$partner_partner = $order_order->getPartnerPartner();
		$xml['partner_partner'] = $partner_partner->toArray();
		
		// partner_address_invoice
		$partner_address_invoice = $partner_partner->getPartnerAddressInvoice();
		$xml['partner_address_invoice'] = $partner_address_invoice->toArray();

		// partner_address_delivery
		$partner_address_delivery = $partner_partner->getPartnerAddressDelivery();
		$xml['partner_address_delivery'] = $partner_address_delivery->toArray();
	
		$plugin_classes = $order_item->getProductItem()->getProductLayout()->plugin_classes;
		
		$plugin_classes = explode(',', $plugin_classes);
		foreach ($plugin_classes as $plugin_class) {
			$plugin_class = trim($plugin_class);
			$plugin_obj = new $plugin_class(); 
			if ($plugin_obj instanceof Product_Service_Plugin) {
				$xml[$plugin_class] = $plugin_obj->execute($order_item, $xml);
			}
		}
		
		return Rest_Xml::encode('data', $xml);
	}
	
	public function setRecentOrderItem($order_item) {
		$this->_recentOrderItem = $order_item;
	}
	
	/**
	 * 
	 * @param string $authKey
	 * @return Order_Model_Item <Zend_Db_Table_Row_Abstract, NULL, unknown>
	 */
	public function getOrderItemByAuthKey($authkey) {
		$order_items = new Order_Model_DbTable_Item();
		return $order_items->fetchRow(Zend_Db_Table::getDefaultAdapter()->quoteInto('authkey = ?', $authkey));
		
	}

	/**
	 * 
	 * @param Order_Model_Item $order_item
	 * @param array $product_personalize_override
	 * @param integer $view
	 * @return boolean
	 */
	public function createPreview(Order_Model_Item $order_item, array $product_personalize_override = array(), $view = Product_Model_Layout::VIEW_PREVIEW_FRONT, $refresh = false) {

		if (!$order_item instanceof Order_Model_Item) return false;

		$authkey = $order_item->getAuthkey();	
		
		switch($view) {
			case Product_Model_Layout::VIEW_PREVIEW_BACK:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_back_preview;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_back_preview;
				$suffix = Product_Model_Layout::VIEW_PREVIEW_BACK_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PREVIEW_FRONT:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_front_preview;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_front_preview;
				$suffix = Product_Model_Layout::VIEW_PREVIEW_FRONT_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PRINT_BACK:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_back_print;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_back_print;
				$suffix = Product_Model_Layout::VIEW_PRINT_BACK_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PRINT_FRONT:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_front_print;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_front_print;
				$suffix = Product_Model_Layout::VIEW_PRINT_FRONT_SUFFIX;
				break;
		}
		
		if (file_exists(APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf') && empty($refresh)) return true;
		
		$xml = $this->toXml($order_item, $product_personalize_override);
		
		$xml->save(APPLICATION_PATH . '/../resource/xml/' . $authkey . '.xml');
		Rest_Pdf::fop(
			APPLICATION_PATH . '/../resource/xml/' . $authkey . '.xml',
			APPLICATION_PATH . '/../resource/' . $xsl,
			APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf'
		);

		Rest_Pdf::overlay(
			APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf',
			APPLICATION_PATH . '/../resource/' . $pdf,
			APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf'
		);

		Rest_Pdf::toImage(
			APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf', 
			'png'
		);
		
		return true;
	}
	
	public function sendPreview(Order_Model_Item $order_item) {		
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
		
		// assign valeues
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
		
		// render view
		$bodyText = $eml->render('send.phtml');
		
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($order_item->getOrderOrder()->getPartnerPartner()->email);
		$mail->setSubject('Druckvorschau');
		
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . Product_Model_Layout::VIEW_PREVIEW_FRONT_SUFFIX . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
		
		$mail->send();
		return true;
	}
	
	public function releasePreview(Order_Model_Item $order_item) {
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
	
		// assign values
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
	
		// render view
		$bodyText = $eml->render('release.phtml');
	
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($this->_partner['email']);
		$mail->setSubject('Druckvorschau');
	
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
	
		$mail->send();
	}
	
	public function checkState(Order_Model_Item $order_item, Order_Model_Item $order_item_recent) {
		if ($order_item->order_itemstate_id != $order_item_recent->order_itemstate_id) {
			$this->processState($order_item);
		}
	}
	
	protected function processState(Order_Model_Item $order_item, array $values = array()) {
	
		$this->logState($order_item, $values);
		
		switch ($order_item->order_itemstate_id) {
			case Order_Service_Itemstate::ORDER_ITEM_STATE_NEW:
				return $this->processStateNew($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASE:
				return $this->processStateRelease($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_DENY:
				return $this->processStateDeny($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED:
				return $this->processStateReleased($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_PRODUCTION:
				return $this->processStateProduction($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_DELIVERY:
				return $this->processStateDelivery($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_FINISHED:
				return $this->processStateFinished($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_CORRECTION:
				return $this->processStateCorrection($order_item, $values);
				break;
		}
	}
	
	protected function processStateNew(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateRelease(Order_Model_Item $order_item) {
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
	
		// assign valeues
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
	
		// render view
		$bodyText = $eml->render('release.phtml');
	
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($order_item->getOrderOrder()->getPartnerPartner()->email);
		$mail->setSubject('Druckvorschau');
	
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
	
		$mail->send();
	}
	
	protected function processStateDeny(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateReleased(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateProduction(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateDelivery(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateFinished(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateCorrection(Order_Model_Item $order_item, array $values = array()) {
		return $order_item->setProductPersonalize($values);
	}
	
	protected function logState(Order_Model_Item $order_item, array $values = null) {		
		$data = array(
				'order_item_id' => $order_item->id,
				'order_itemstate_id' => $order_item->order_itemstate_id,
				'datetime' => date('Y-m-d H:i:s'),
				'comment' => !empty($values['comment']) ? $values['comment'] : null,
		);
		$order_itemstatelogs = new Order_Model_DbTable_Itemstatelog();
		$order_itemstatelog = $order_itemstatelogs->createRow($data);
		return $order_itemstatelog->save();
	}
		
	/**
	 * 
	 * @param Order_Model_Item $order_item
	 * @param integer $order_itemstate_id
	 * @param array $values
	 * @return boolean|Ambigous <mixed, multitype:>
	 */
	public function changeState(Order_Model_Item $order_item, $order_itemstate_id, array $values = array()) {
		if (!$order_item instanceof Order_Model_Item) return false;			
		$order_item->order_itemstate_id = $order_itemstate_id;
		$order_item->save();
		return $this->processState($order_item, $values);
	}
	
	/**
	 * 
	 * @param Order_Model_Item $order_item
	 * @return Order_Form_Itemstatecorrection
	 */
	public function CorrectionFormFactory(Order_Model_Item $order_item) {
		$form = $order_item->getProductItem()->getProductLayout()->correction_form_class;
		return new $form();
	}

}