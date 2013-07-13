<?php

class Partner_AddressController extends Rest_Controller_Action_DbTable
{
		
	public function init()
	{
		parent::init();
		$this->_helper->contextSwitch()
		->addActionContext('zipcleanup', 'json')
		->initContext('json')
		;
	}	
	
	public function zipcleanupAction() {
		$this->view->success = $this->getService()->zipcleanup();
	}

}