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

	public function postAction() {		
		$row = parent::postAction();
		
		$this->getService()->setRow($row);
		$this->getService()->createPreview();
		
	}
	
	public function putAction() {
		$payload = $this->getJsonPayload();
		if (!empty($payload['id'])) {
			$this->getService()->setRecentRow($payload);
		}
		
		$row = parent::putAction();
		
		$this->getService()->setRow($row);
		$this->getService()->createPreview();
		$this->getService()->checkState();
	}
	
	public function refreshAction() {
		$this->getService()->setRow(array('id' => $this->getRequest()->getParam('id')));
		$this->getService()->createPreview();
		$this->view->success = true;
	}
	
	public function sendAction() {
		$this->getService()->setRow(array('id' => $this->getRequest()->getParam('id')));
		$this->view->success = $this->getService()->sendPreview();
		
	}
	
	public function releasedAction() {
		$this->view->result = $this->getService()->changeState($this->getRequest()->getParam('authKey', null), Order_Service_Itemstate::ORDER_ITEM_STATE_RELEASED);		
	}
	
	public function denyAction() {
		
		$this->view->result = false;
		$form = new Order_Form_Itemstatedeny();
		if ($this->getRequest()->isPost()) {
			$values = $this->getRequest()->getPost();
            if ($form->isValid($values)) {            	
                $values = $form->getValues();
				$this->view->result = $this->getService()->changeState(
						$this->getRequest()->getParam('authKey', null), 
						Order_Service_Itemstate::ORDER_ITEM_STATE_DENY, 
						$values['comment']
				);
            }
            $form->populate($values);
        }
        $this->view->form = $form;
		
	}
	
	
}
