<?php

class Import_ImportController extends Rest_Controller_Action_DbTable
{

	protected $_service = null;
	
	public function init()
	{
		parent::init();
		$this->_helper->contextSwitch()
		//->clearActionContexts()
		->addActionContext('index', 'json')
		->addActionContext('partner', 'json')
		->addActionContext('order', 'json')
		->addActionContext('execute', 'json')
		->setDefaultContext('json')
		->initContext('json')
		;
	}
	
    protected function getService() {    	
    	if ($this->_service == null) {
    		$this->_service = new Import_Service_Import();
    	}   	
    	return $this->_service;
    }
	
	public function partnerAction() {		
		$filename = $this->getRequest()->getParam('filename', null);
		$this->getService()->loadImportPartner($filename);
	}
	
	public function orderAction() {
		$filename = $this->getRequest()->getParam('filename', null);
		$product_item_id = $this->getRequest()->getParam('product_item_id', null);
		$this->getService()->loadImportOrder($filename, $product_item_id);
	}

	public function executeAction() {
		$filename = $this->getRequest()->getParam('filename', null);
		$this->getService()->importExecute($filename, $this->getRequest());
	}
	
}