<?php

class Rest_Controller_Action_DbTable extends Zend_Controller_Action
{

	protected $_mapper = null;
	protected $_service = null;
	
    public function init()
    {
    	$this->_helper->contextSwitch()
    	->clearActionContexts()
    	->addActionContext('index', 'json')
    	->addActionContext('put', 'json')
    	->addActionContext('get', 'json')
    	->addActionContext('post', 'json')
    	->addActionContext('delete', 'json')
    	->addActionContext('meta','json')
    	->setDefaultContext('json')
    	->initContext('json')
    	;
    }

    public function indexAction()
    {
        if($this->getRequest()->getMethod() === 'POST')
    	{
    		return $this->_forward('post');
    	}

    	if($this->getRequest()->getMethod() === 'GET')
    	{
    		return $this->_forward('get');
    	}
    	
    	if($this->getRequest()->getMethod() === 'PUT')
    	{
    		return $this->_forward('put');
    	}
    	
    	if($this->getRequest()->getMethod() === 'DELETE')
    	{
    		return $this->_forward('delete');
    	}
    	if($this->getRequest()->getMethod() === 'META')
    	{
    		return $this->_forward('meta');
    	}
    	if($this->getRequest()->getMethod() === 'EXPORT')
    	{
    		return $this->_forward('export');
    	}
    }
    
    protected function getService() {
    	if (empty($this->_service)) {
    		$module = $this->getRequest()->getModuleName();
    		if ($module == 'default') {
    			$module = $this->getInvokeArg('bootstrap')->getAppnamespace();
    		}
    		$controller = $this->getRequest()->getControllerName();
    		$service = ucfirst($module) . '_Service_' . ucfirst($controller);
    		$this->_service = new $service();
    	}
    	return $this->_service;
	}
    
    protected function getMapper() {
    	if (empty($this->_mapper)) {
    		$module = $this->getRequest()->getModuleName();
    		if ($module == 'default') {
    			$module = $this->getInvokeArg('bootstrap')->getAppnamespace();    			
    		}
    		$controller = $this->getRequest()->getControllerName();
    		$mapper = ucfirst($module) . '_Model_Mapper_' . ucfirst($controller);
    		$this->_mapper = new $mapper();
    	}
    	return $this->_mapper;
    }
    
    public function postAction() {
    	$body = $this->getRequest()->getRawBody();
    	$data = Zend_Json::decode($body);
    	return $this->view->data = $this->getMapper()->insert($data)->toArray();
    }
    
    public function getAction() {
    	//$table = new Eja_Model_DbTable_User();
    	
    	$data = $this->getMapper()->fetch($this->getRequest());
    	
    	if (is_array($data)) {
    		$this->view->data = $data;
    		$this->view->total = count($data);
    	} else {
    		$this->view->data = $this->getMapper()->fetch($this->getRequest())->toArray();
    		$this->view->total = $this->getMapper()->rowCount();
    	}
    	return $this->view->data;
    }
    
    public function exportAction() {
    	
    }
    
    public function putAction() {
    	$body = $this->getRequest()->getRawBody();
    	$data = Zend_Json::decode($body);    	
    	$row = $this->getMapper()->update($data);
    	return $this->view->data = is_array($row) ? $row : $row->toArray();  	
    }
    
    public function deleteAction() {
    	$body = $this->getRequest()->getRawBody();
    	$data = Zend_Json::decode($body);    

    	$total = $this->getMapper()->delete($data);
    	if (empty($total)) {
    		$this->view->success = false;    		
    	} else {
    		$this->view->success = true;
    		$this->view->total = $total;
    	}
    	
    	return $this->view->success;
    }
    
    public function metaAction() {
    	return $this->view->data = $this->getMapper()->fetchMeta($this->getRequest());
    }

}