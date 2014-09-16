<?php

class Order_ItemController extends Rest_Controller_Action_DbTable
{

	public function init()
	{
		parent::init();
		$this->_helper->contextSwitch()
		->addActionContext('refresh', 'json')
		->addActionContext('send', 'json')
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
		$order_item_recent = null;
		if (!empty($payload['id'])) {
			$order_item_recent = $order_items->find($payload['id'])->current();
		}
		
		$row = parent::putAction();
		
		$row = reset($row);
		
		$order_item = $order_items->find($row['id'])->current();
		$this->getService()->createPreview(
				$order_item, 
				array(), 
				$this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT),
				$this->getRequest()->getParam('refresh', false)
				);
		$this->getService()->checkState($order_item, $order_item_recent);
	}
	
	public function refreshAction() {
		$order_items = new Order_Model_DbTable_Item();
		$order_item = $order_items->find($this->getRequest()->getParam('id'))->current();
		$this->getService()->createPreview(
				$order_item, 
				array(), 
				$this->getRequest()->getParam('viewmode', Product_Model_Layout::VIEW_PREVIEW_FRONT),
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
	
}
