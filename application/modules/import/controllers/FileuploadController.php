<?php

class Import_FileuploadController extends Zend_Controller_Action
{
	
	protected $_service = null;

    public function init()
    {
    	$this->_helper->contextSwitch()
    	->clearActionContexts()
    	->addActionContext('index', 'json')
    	->addActionContext('partner', 'json')
    	->addActionContext('order', 'json')
    	->addActionContext('execute', 'json')
    	->addActionContext('logo', 'json')
    	->addActionContext('pdf', 'json')
    	->setDefaultContext('json')
    	->initContext('json')
    	;    	
    }
    
    protected function getService() {    	
    	if ($this->_service == null) {
    		$this->_service = new Import_Service_Fileupload();
    	}
    	
    	return $this->_service;
    }

    public function indexAction()
    {
        // action body
    }

    public function partnerAction() {
    	$result = $this->getService()->upload('partner');
    	foreach ($result as $key => $value) {
    		$this->view->$key = $value;
    	}
    }
    
    public function orderAction() {
    	$result = $this->getService()->upload('order');
    	foreach ($result as $key => $value) {
    		$this->view->$key = $value;
    	} 
    }

    public function executeAction() {
    	$result = $this->getService()->upload('execute');
    	foreach ($result as $key => $value) {
    		$this->view->$key = $value;
    	}
    }

    public function logoAction() {
    	$result = $this->getService()->upload('logo');
    	
    	if ($result->success == "true") {
    		$pdfoptions = "";
    		if (strpos(strtolower($result->filename), ".pdf") > 0) {
    			$pdfoptions = " -density 300 -depth 8 -quality 85 ";
    		}
    		$exec = "convert " . $pdfoptions . $result->file . DIRECTORY_SEPARATOR . $result->filename . " " . APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "resource/logo_original" . DIRECTORY_SEPARATOR . $this->getRequest()->getParam('partner_nr') . ".png";
    		// $exec = "convert " . $pdfoptions . $result->file . DIRECTORY_SEPARATOR . $result->filename . " " . APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "resource/logo_original" . DIRECTORY_SEPARATOR . $this->getRequest()->getParam('partner_nr') . ".gif";
    		exec($exec);
    	}
    	
    	foreach ($result as $key => $value) {
    		$this->view->$key = $value;
    	}
    }
    
    public function pdfAction() {
    	$result = $this->getService()->upload('pdf');
    	
    	if ($result->success == "true") {
			copy ($result->file . DIRECTORY_SEPARATOR . $result->filename, APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "resource/pdf" . DIRECTORY_SEPARATOR . $this->getRequest()->getParam('full_filename'));    		
			copy ($result->file . DIRECTORY_SEPARATOR . $result->filename, APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public/deploy" . DIRECTORY_SEPARATOR . $this->getRequest()->getParam('full_filename'));    		
    	}
    	
    	foreach ($result as $key => $value) {
    		$this->view->$key = $value;
    	}
    }
    
}