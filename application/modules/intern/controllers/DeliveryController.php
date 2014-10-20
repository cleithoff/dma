<?php

class Intern_DeliveryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
		// action body
    	$this->_helper->layout->setLayout('intern');
    	
        $cmd = $this->getRequest()->getParam('cmd', null);
        
        $authkey = $this->getRequest()->getParam('authkey', null);
        
        if ($cmd == "Speichern") {
        	try {
	        	$packagePackageorder = $this->getOrderCombine($authkey)->getPackagePackageorder();
	        	$packagePackageorder->outgoing = date('Y-m-d H:i:s', time());
	        	$packagePackageorder->save();
	        	$this->view->assign('success', "Liefertermin wurde gespeichert.");
        	} catch (Exception $ex) {
        		$this->view->assign('message', $ex->getMessage());
        	}
        }
        
        $this->view->assign('cmd', $cmd);
        $this->view->assign('authkey', $authkey);
    }


}

