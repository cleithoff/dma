<?php

class Order_Service_Item
{
	
	protected function isLockedRender($order_item, $viewmode) {
	
		switch($viewmode) {
			case Product_Model_Layout::VIEW_PREVIEW_FRONT:
				return (intval($order_item->locked_render_front_preview) === 1);
				break;
			case Product_Model_Layout::VIEW_PREVIEW_BACK:
				return (intval($order_item->locked_render_back_preview) === 1);
				break;
			case Product_Model_Layout::VIEW_PRINT_FRONT:
				return (intval($order_item->locked_render_front_print) === 1);
				break;
			case Product_Model_Layout::VIEW_PRINT_BACK:
				return (intval($order_item->locked_render_back_print) === 1);
				break;
			case Product_Model_Layout::VIEW_TEST_FRONT:
				return (intval($order_item->locked_render_front_test) === 1);
				break;
			case Product_Model_Layout::VIEW_TEST_BACK:
				return (intval($order_item->locked_render_back_test) === 1);
				break;
			default:
				return true;
		}
	
		return true;
	}
	

	protected function postProcess(Order_Model_Item $order_item, $viewmode, $resourcePdfFile, $publicDeployFile) {
		$plugin_classes = $order_item->getProductItem()->getProductLayout()->plugin_classes;
		
		$plugin_classes = explode(',', $plugin_classes);
		foreach ($plugin_classes as $plugin_class) {
			$plugin_class = trim($plugin_class);
			if (empty($plugin_class)) continue;
			$plugin_obj = new $plugin_class(); 
			if ($plugin_obj instanceof Product_Service_Plugin) {
				$plugin_obj->postProcess($order_item, $viewmode, $resourcePdfFile, $publicDeployFile);
			}
		}
	}
	
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
			if (empty($plugin_class)) continue;
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

	public function toImage(Order_Model_Item $order_item, array $product_personalize_override = array(), $view = Product_Model_Layout::VIEW_PREVIEW_FRONT, $imageformat = 'tiff') {
		
		if (!$order_item instanceof Order_Model_Item) return false;
		
		$authkey = $order_item->getAuthkey();
		
		switch($view) {
			case Product_Model_Layout::VIEW_PREVIEW_BACK:
				$suffix = Product_Model_Layout::VIEW_PREVIEW_BACK_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PREVIEW_FRONT:
				$suffix = Product_Model_Layout::VIEW_PREVIEW_FRONT_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PRINT_BACK:
				$suffix = Product_Model_Layout::VIEW_PRINT_BACK_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_PRINT_FRONT:
				$suffix = Product_Model_Layout::VIEW_PRINT_FRONT_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_TEST_FRONT:
				$suffix = Product_Model_Layout::VIEW_TEST_FRONT_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_TEST_BACK:
				$suffix = Product_Model_Layout::VIEW_TEST_BACK_SUFFIX;
				break;
		}
		
		Rest_Pdf::toImage(
			APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf',
			$imageformat
		);
		
		$filename = APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.' . $imageformat;
		
		return $filename;
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

		if ($this->isLockedRender($order_item, $view)) return false;
		
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
			case Product_Model_Layout::VIEW_TEST_FRONT:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_front_test;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_front_test;
				$suffix = Product_Model_Layout::VIEW_TEST_FRONT_SUFFIX;
				break;
			case Product_Model_Layout::VIEW_TEST_BACK:
				$xsl = $order_item->getProductItem()->getProductLayout()->xsl_back_test;
				$pdf = $order_item->getProductItem()->getProductLayout()->pdf_back_test;
				$suffix = Product_Model_Layout::VIEW_TEST_BACK_SUFFIX;
				break;
		}
		
		if (file_exists(APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf') && empty($refresh)) return true;
		
		$xml = $this->toXml($order_item, $product_personalize_override);
		
		if (file_exists(APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf')) {
			unlink(APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf');
		}
		
		$xml->save(APPLICATION_PATH . '/../resource/xml/' . $authkey . '.xml');
		Rest_Pdf::fop(
			APPLICATION_PATH . '/../resource/xml/' . $authkey . '.xml',
			APPLICATION_PATH . '/../resource/' . $xsl,
			APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf'
		);
		
		if (!empty($pdf)) {
			Rest_Pdf::overlay(
				APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf',
				APPLICATION_PATH . '/../resource/' . $pdf,
				APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf'
			);
		} else {
			copy (APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf', APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf');
		}
		
		$this->postProcess(
				$order_item,
				$view,
				APPLICATION_PATH . '/../resource/pdf/' . $authkey . $suffix . '.pdf',
				APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf'
				);
		/*
		Rest_Pdf::toImage(
			APPLICATION_PATH . '/../public/deploy/' . $authkey . $suffix . '.pdf', 
			'png'
		);
		*/
		return true;
	}
	
	public function sendPreview(Order_Model_Item $order_item, $comment = null) {		
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
		
		// assign valeues
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
		$eml->assign('comment', $comment);
		
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
	
	public function releasePreview(Order_Model_Item $order_item, $comment = null) {
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');

		// assign values
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
		$eml->assign('comment', $comment);
	
		// render view
		$bodyText = $eml->render('release.phtml');
	
		$mail = new Zend_Mail();
		$mail->setMimeBoundary('=_' . md5(microtime(1) . $order_item->getAuthkey()));
		
		$mail->addTo($this->_partner['email']);
		$mail->addHeader('Bcc', 'carsten.leithoff@cu-medien.com,fleurop@dm-mundschenk.de,cradlbeck@dm-mundschenk.de');
		$mail->setSubject('Druckvorschau');
		
		$at =& $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_INLINE;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
		
		$backFilename = realpath(APPLICATION_PATH . "/../public/deploy") . '/' . $order_item->getAuthkey() . "_preview_back.pdf";
		
		if (file_exists($backFilename)) {
			$at2 =& $mail->createAttachment(file_get_contents($backFilename), 'application/pdf');
			$at2->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at2->encoding    = Zend_Mime::ENCODING_BASE64;
			$at2->filename    = $order_item->getAuthkey() . '_preview_back.pdf'; //Hint! Hint!
		}
		
		$mail->setBodyText($bodyText);

		$mail->send();
		
	}
	
	public function checkState(Order_Model_Item $order_item, Order_Model_Item $order_item_recent, $comment = null) {
		if ($order_item->order_itemstate_id != $order_item_recent->order_itemstate_id) {
			$this->processState($order_item, array(), $comment);
		}
	}
	
	protected function processState(Order_Model_Item $order_item, array $values = array(), $comment = null) {
	
		$logvalues = array();
		
		foreach ($values as $key => $val) {
			$logvalues[$key] = $val;
		}
		
		if (!empty($comment) && empty($logvalues['comment'])) {
			$logvalues['comment'] = $comment;
		}
		
		$this->logState($order_item, $logvalues);
		
		switch ($order_item->order_itemstate_id) {
			case Order_Service_Itemstate::ORDER_ITEM_STATE_NEW:
				return $this->processStateNew($order_item);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASE:
				return $this->processStateRelease($order_item, $comment);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_DENY:
				return $this->processStateDeny($order_item, $values);
				break;
			case Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED:
				return $this->processStateReleased($order_item, $values);
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
			case Order_Service_Itemstate::ORDER_ITEM_STATE_CANCELLATION:
				return $this->processStateCancellation($order_item, $values);
				break;
		}
	}
	
	protected function processStateNew(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateRelease(Order_Model_Item $order_item, $comment = null) {
		
		// for transport config look at application.ini
		$eml = new Zend_View();
		$eml->setScriptPath(APPLICATION_PATH . '/modules/order/views/emails/item');
	
		// assign valeues
		$eml->assign('partner_partner', $order_item->getOrderOrder()->getPartnerPartner());
		$eml->assign('order_item', $order_item);
		$eml->assign('comment', $comment);
	
		// render view
		$bodyText = $eml->render('release.phtml');

		$mail = new Zend_Mail();
		$mail->setMimeBoundary('=_' . md5(microtime(1) . $order_item->getAuthkey()));
		
		//$mail->addTo('carsten.leithoff@cu-medien.com');
		$mail->addTo($order_item->getOrderOrder()->getPartnerPartner()->email);
		$mail->addBcc(array('carsten.leithoff@cu-medien.com','fleurop@dm-mundschenk.de','cradlbeck@dm-mundschenk.de'));
		$mail->setSubject(iconv("UTF-8", "ISO-8859-1", 'Druckvorschau ' . $order_item->getProductProduct()->title . ', ' . $order_item->getProductItem()->title . ' | PartnerNr ' . $order_item->getOrderOrder()->getPartnerPartner()->partner_nr . ' '));
		
		$at =& $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_INLINE;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
		
		$backFilename = realpath(APPLICATION_PATH . "/../public/deploy") . '/' . $order_item->getAuthkey() . "_preview_back.pdf";
		
		if (file_exists($backFilename)) {
			$at2 =& $mail->createAttachment(file_get_contents($backFilename), 'application/pdf');
			$at2->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at2->encoding    = Zend_Mime::ENCODING_BASE64;
			$at2->filename    = $order_item->getAuthkey() . '_preview_back.pdf'; //Hint! Hint!
		}
		
		$mail->setBodyText($bodyText);
		$mail->send();
		
		/*
		
		
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo($order_item->getOrderOrder()->getPartnerPartner()->email);
		$mail->setSubject('Druckvorschau');
	
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
	
		$mail->send();
		
		$mail = new Zend_Mail();
		$mail->setBodyText($bodyText);
		$mail->addTo('carsten.leithoff@cu-medien.com');
		$mail->setSubject('Druckvorschau');
		
		$at = $mail->createAttachment(file_get_contents(APPLICATION_PATH . '/../public/deploy/' . $order_item->getAuthkey() . '.pdf'), 'application/pdf');
		$at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
		$at->encoding    = Zend_Mime::ENCODING_BASE64;
		$at->filename    = $order_item->getAuthkey() . '.pdf'; //Hint! Hint!
		
		$mail->send();*/
	}
	
	protected function processStateDeny(Order_Model_Item $order_item, array $values = array()) {
		if (is_array($values) && count($values) > 0) {
			return $order_item->setProductPersonalize($values);
		}
	}
	
	protected function processStateReleased(Order_Model_Item $order_item, array $values = array()) {
		if (is_array($values) && count($values) > 0) {
			return $order_item->setProductPersonalize($values);
		}
	}
	
	protected function processStateProduction(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateDelivery(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateFinished(Order_Model_Item $order_item) {
	
	}
	
	protected function processStateCorrection(Order_Model_Item $order_item, array $values = array()) {
		if (is_array($values) && count($values) > 0) {
			return $order_item->setProductPersonalize($values);
		}
	}
	
	protected function processStateCancellation(Order_Model_Item $order_item, array $values = array()) {

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
		$correction_form_class = $order_item->getProductItem()->getProductLayout()->correction_form_class;
		$form = new $correction_form_class(array('order_item' => $order_item));
		return $form;
	}
	
	private function _getReplaces($matches, $doQuote, $csv, $result, $param, $exists = array()) {
	
		$replacements = array();
		foreach ($matches as $match) {
			$doForceQuote = $doQuote;
			$str = str_replace(array('{','}','"'), array('','','"'), $match);
			$str = explode('.', $str);
			$source = $str[0];
				
			$str1 = explode('|', $str[1]);
			$str[1] = $str1[0];
			switch(@$str1[1]) {
				case '#':
					$doForceQuote = false;
					break;
			}
				
			switch($source) {
				case 'csv':
					$col = str_replace('"', '', $str[1]);
					$col = str_replace('\'', '', $str[1]);
					$replacement = @$csv[$col];
					break;
				case 'result':
					$table = $str[1];
					$col = $str[2];
					$replacement = $result[$table][$col];
					break;
				case 'exists':
					$table = $str[1];
					$replacement = intval($exists[$table]);
					break;
				case 'param':
					$key = $str[1];
					$replacement = $param[$key];
					break;
				case 'static':
					$static = $str[1];
					switch($static) {
						case 'PHPSESSID':
							$replacement = Zend_Session::getId();
							break;
					}
					break;
				default:
					$replacement = '';
					break;
			}
			if ($doForceQuote) {
				$replacement = Zend_Db_Table::getDefaultAdapter()->quote($replacement); // '"' . $replacement . '"';
			}
			array_push($replacements, $replacement); // $replacements
		}
		return $replacements;
	}
	
	private function _getReplaced($str, $csv = array(), $result = array(), $param = array(), $exists = array(), $doQuote = true) {
		$matches = array();
		preg_match_all('/\{[^\{\}]*\}/', $str, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], $doQuote, $csv, $result, $param, $exists);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}','|','#'), array('\\{','\\.','\\}','\\|','\\#'), $match) . '/';
			}
			$str = preg_replace($matches[0], $replacements, $str);
		}
	
		$matches = array();
		preg_match_all('/\{.*?\}/', $str, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], $doQuote, $csv, $result, $param, $exists);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}'), array('\\{','\\.','\\}'), $match) . '/';
			}
			$str = preg_replace($matches[0], $replacements, $str);
		}
	
		return $str;
	}
	
	public function moveToHotfolder(Order_Model_Item $orderItem) {

		if ($orderItem->getOrderItemstate()->key != "production") return;
	
		$orderOrder = $orderItem->getOrderPool()->depOrderOrder()->current();
		
		$partnerPartner = $orderItem->getOrderPool()->getOrderCombine()->getPartnerPartner();
					
		$productLayout = $orderItem->getProductItem()->getProductLayout();
		
		if (!empty($productLayout->hotfolder) && !empty($productLayout->filename)) {

			$viewmode = 'print_front';
			$params = array(
					'partner_nr' => $partnerPartner->partner_nr,
					'order_no_external' => $orderOrder->order_no_external,
					'viewmode' => $viewmode,
			);
			$filenameSrc = APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public/deploy" . DIRECTORY_SEPARATOR . $orderItem->authkey . '_' . $viewmode . '.pdf';
			if (file_exists($filenameSrc)) {
				$filenameDst = $this->_getReplaced($productLayout->filename, array(), array(), $params, array(), false);
				if (file_exists($productLayout->hotfolder)) {
					copy($filenameSrc, $productLayout->hotfolder . '/' . $filenameDst);
				}
			}

			$viewmode = 'print_back';
			$params = array(
					'partner_nr' => $partnerPartner->partner_nr,
					'order_no_external' => $orderOrder->order_no_external,
					'viewmode' => $viewmode,
			);
			$filenameSrc = APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public/deploy" . DIRECTORY_SEPARATOR . $orderItem->authkey . '_' . $viewmode . '.pdf';
			if (file_exists($filenameSrc)) {
				$filenameDst = $this->_getReplaced($productLayout->filename, array(), array(), $params, array(), false);
				if (file_exists($productLayout->hotfolder)) {
					copy($filenameSrc, $productLayout->hotfolder . '/' . $filenameDst);
				}
			}

		}
	}

}