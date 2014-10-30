<?php

class Order_ItemController extends Rest_Controller_Action_DbTable
{

	public function init()
	{
		parent::init();
		$this->_helper->contextSwitch()
		->addActionContext('refresh', 'json')
		->addActionContext('send', 'json')
		->addActionContext('takeover', 'json')
		->setDefaultContext('json')
		->initContext('json')
		;
	}
	
	/**
	 * (non-PHPdoc)
	 * @return Order_Service_Item
	 * @see Rest_Controller_Action_DbTable::getService()
	 */
	protected function getService() {
		return parent::getService();
	}

	public function postAction() {		
		$row = parent::postAction();
		
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($row['id'])->current();
		$this->getService()->createPreview(
				$order_item, 
				array(), 
				$this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT),
				$this->getRequest()->getParam('refresh', false)
				);
	}
	
	public function putAction() {
		$order_items = new Order_Model_DbTable_Item();
		$payload = $this->getJsonPayload();
		
		$comment = $payload['comment'];
		
		$order_item_recent = null;
		if (!empty($payload['id'])) {
			$order_item_recent = $order_items->find($payload['id'])->current();
		}
		
		$row = parent::putAction();
		
		$row = reset($row);
		
		$order_item = $order_items->find($row['id'])->current();
		
		
		
		
		/* $this->getService()->createPreview(
				$order_item, 
				array(), 
				$this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT),
				$this->getRequest()->getParam('refresh', false)
				);
		*/
		$this->getService()->checkState($order_item, $order_item_recent, $comment);
		
		$this->getService()->moveToHotFolder($order_item);
	}
	
	public function toimageAction() {
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($this->getRequest()->getParam('id'))->current();
		$viewmode = $this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT);
		$format = $this->getRequest()->getParam('imageformat', 'tiff');
		
		$filename = $this->getService()->toImage(
				$order_item,
				array(),
				$viewmode,
				$format
		);

		$filename = realpath($filename);
		
		$info = pathinfo($filename);
		
		header('Content-type: image/' . $format);
		header('Content-Disposition: attachment; filename="' . $info['basename']);

		readfile($filename);
		
		die();
	}
	
	public function refreshAction() {
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($this->getRequest()->getParam('id'))->current();
		$viewmode = $this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT);

			$this->getService()->createPreview(
					$order_item, 
					array(), 
					$viewmode,
					$this->getRequest()->getParam('refresh', false)
					);

		$this->view->success = true;
		$this->view->total = 1;
		$this->view->data = array();
		$data = new stdClass();
		$data->authkey = $order_item->getAuthkey();
		array_push($this->view->data, $data);
	}
	
	public function sendAction() {
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($this->getRequest()->getParam('id'))->current();
		$this->view->success = $this->getService()->sendPreview($order_item);
	}
	
	public function releasedAction() {
		$this->view->order_item = $order_item = $this->getService()->getOrderItemByAuthKey($this->getRequest()->getParam('authKey', null));
		$this->view->result = $this->getService()->changeState($order_item, Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED);		
	}
	
	public function denyAction() {	
		$this->view->order_item = $order_item = $this->getService()->getOrderItemByAuthKey($this->getRequest()->getParam('authKey', null));
		$this->view->result = false;
		$form = new Order_Form_Itemstatedeny();
		if ($this->getRequest()->isPost()) {
			$values = $this->getRequest()->getPost();
            if ($form->isValid($values)) {            	
                $values = $form->getValues();
				$this->view->result = $this->getService()->changeState(
						$order_item, 
						Order_Service_Itemstate::ORDER_ITEM_STATE_DENY, 
						$values
				);
            }
            $form->populate($values);
        }
        $this->view->form = $form;	
	}
		
	public function correctionAction() {
		$command = null;
		
		$this->view->result = false;
		
		$this->view->order_item = $order_item = $this->getService()->getOrderItemByAuthKey($this->getRequest()->getParam('authKey', null));		
		
		if (intval($order_item->order_itemstate_id) !== intval(Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASE)) {
			$this->view->form = null;
			return;
		}
		
		$form = $this->getService()->CorrectionFormFactory($order_item);
		
		if ($this->getRequest()->isPost()) {
			$values = $this->getRequest()->getPost();
			if ($form->isValid($values)) {
				if (!empty($values['_deny'])) $command = 'deny';
				if (!empty($values['_preview'])) $command = 'preview';
				if (!empty($values['_correction'])) $command = 'correction';
				
				$values = $form->getValues();
				if ($command === 'preview') {

					$this->getService()->createPreview(
							$order_item,
							$values,
							Product_Model_Layout::VIEW_PREVIEW_FRONT,
							true
					);
					$this->getService()->createPreview(
							$order_item,
							$values,
							Product_Model_Layout::VIEW_PREVIEW_BACK,
							true
					);
				} else 
				if ($command === 'deny') {
					$this->view->result = $this->getService()->changeState(
							$order_item,
							Order_Service_Itemstate::ORDER_ITEM_STATE_DENY,
							$values,
							true
					);
				} else
				if ($command === 'correction') {
					if (empty($values['comment'])) {
						$this->view->result = $this->getService()->changeState(
								$order_item,
								Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED,
								$values,
								true
						);
					} else {
						$this->view->result = $this->getService()->changeState(
								$order_item,
								Order_Service_Itemstate::ORDER_ITEM_STATE_CORRECTION,
								$values,
								true
						);
					}
				}
			}			
		} else {
			$values = $order_item->getProductPersonalize(); //$this->getService()->getProductPersonalizeValues($order_item);
		}
		
		$form->populate($values);
		$this->view->form = $form;
	}
	
	protected function copyPdf($fileSrc, $fileDst) {
		$dirResource = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resource' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;
		$dirDeploy = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'deploy' . DIRECTORY_SEPARATOR;
		
		if (file_exists($dirResource . $fileSrc)) {
			copy($dirResource . $fileSrc, $dirResource . $fileDst);
			copy($dirResource . $fileSrc, $dirDeploy . $fileDst);
		}
		
		if (file_exists($dirDeploy . $fileSrc)) {
			copy($dirDeploy . $fileSrc, $dirDeploy . $fileDst);
		}
		
	}
	
	protected function takeoverPdf(Order_Model_Item $orderItemSrc, Order_Model_Item $orderItemDst) {
		$dir = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'resource' . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR;

		$this->copyPdf($orderItemSrc->authkey . '.pdf', $orderItemDst->authkey . '.pdf');
		$this->copyPdf($orderItemSrc->authkey . '_preview_back.pdf', $orderItemDst->authkey . '_preview_back.pdf');
		$this->copyPdf($orderItemSrc->authkey . '_print_front.pdf', $orderItemDst->authkey . '_print_front.pdf');
		$this->copyPdf($orderItemSrc->authkey . '_print_back.pdf', $orderItemDst->authkey . '_print_back.pdf');
		$this->copyPdf($orderItemSrc->authkey . '_test_front.pdf', $orderItemDst->authkey . '_test_front.pdf');
		$this->copyPdf($orderItemSrc->authkey . '_test_back.pdf', $orderItemDst->authkey . '_test_back.pdf');
	}
	
	protected function takeoverPersonalize(Order_Model_Item $orderItemSrc, Order_Model_Item $orderItemDst) {
		$productpersonalizes = $orderItemSrc->depOrderItemhasproductpersonalize();
		$orderItemHasProductPersonalizes = new Order_Model_DbTable_Itemhasproductpersonalize();
		foreach ($productpersonalizes as $productpersonalizeSrc) {
			
			$productpersonalizeDst = $orderItemHasProductPersonalizes->fetchRow("order_item_id = " . intval($orderItemDst->id) . " AND product_personalize_id = " . intval($productpersonalizeSrc->product_personalize_id));
			
			if (!empty($productpersonalizeDst)) {
				$productpersonalizeDst->value = $productpersonalizeSrc->value;
				$productpersonalizeDst->save();
			}
		}
	}
	
	public function takeoverAction() {
		
		$iddst = $this->getRequest()->getParam('iddst', null);
		$idsrc = $this->getRequest()->getParam('idsrc', null);
		
		if (!empty($iddst) && !empty($idsrc)) {
			
			$orderItems = new Order_Model_DbTable_Item();
			
			$orderItemSrc = $orderItems->find($idsrc)->current();
			$orderItemDst = $orderItems->find($iddst)->current();
			
			if ($orderItemSrc instanceof Order_Model_Item && $orderItemDst instanceof Order_Model_Item) {
				
				$pdf = $this->getRequest()->getParam('pdf', null);
				$personalize = $this->getRequest()->getParam('personalize', null);
				
				if (!empty($pdf) && $pdf !== "false") {
					$this->takeoverPdf($orderItemSrc, $orderItemDst);
				}
				
				if (!empty($personalize) && $personalize !== "false") {
					$this->takeoverPersonalize($orderItemSrc, $orderItemDst);
				}
			}
		}
		
		$this->view->data = array();
		$this->view->success = true;
	}
}
