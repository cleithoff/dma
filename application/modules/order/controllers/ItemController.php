<?php

class Order_ItemController extends Rest_Controller_Action_DbTable
{

	public function init()
	{
		parent::init();
		$this->_helper->contextSwitch()
		->addActionContext('refresh', 'json')
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
		$row = parent::putAction();
		
		$this->getService()->setRow($row);
		$this->getService()->createPreview();
	}
	
	public function refreshAction() {
		$this->getService()->setRow(array('id' => $this->getRequest()->getParam('id')));
		$this->getService()->createPreview();
		$this->view->success = true;
	}
	
}
