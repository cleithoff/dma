<?php

class Intern_DeliveryController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    protected function getOrderCombine($authkey) {
    	$orderCombines = new Order_Model_DbTable_Combine();
    	 
    	$orderCombine = $orderCombines->fetchRow("authkey = " . Zend_Db_Table::getDefaultAdapter()->quote($authkey));
    	
    	if (empty($orderCombine)) {
    		throw new Exception("Bestellcode nicht gefunden.");
    	}
    	 
    	return $orderCombine;
    }
    
    protected function getPackagePackageorder($authkey) {
    	$packagePackageorders = new Package_Model_DbTable_Packageorder();
    
    	$packagePackageorder = $packagePackageorders->fetchRow("authkey = " . Zend_Db_Table::getDefaultAdapter()->quote($authkey));
    	 
    	if (empty($packagePackageorder)) {
    		throw new Exception("Paketcode nicht gefunden.");
    	}
    
    	return $packagePackageorder;
    }
    
    public function indexAction()
    {
		// action body
    	$this->_helper->layout->setLayout('intern');
    	
        $cmd = $this->getRequest()->getParam('cmd', null);
        
        $authkey = $this->getRequest()->getParam('authkey', null);
        $sendingnumber = $this->getRequest()->getParam('sendingnumber', null);
        
        if ($cmd == "Speichern" && !empty($authkey) && !empty($sendingnumber)) {
        	try {
	        	$packagePackageorder = $this->getPackagePackageorder($authkey);
	        	$packagePackageorder->outgoing = date('Y-m-d H:i:s', time());
	        	$packagePackageorder->sendingnumber = $sendingnumber;
	        	$packagePackageorder->save();
	        	$this->view->assign('success', "Sendungsnummer und Versandtermin wurden gespeichert.");
        	} catch (Exception $ex) {
        		$this->view->assign('message', $ex->getMessage());
        	}
        }
        
        $this->view->assign('cmd', $cmd);
        $this->view->assign('authkey', $authkey);
        $this->view->assign('sendingnumber', $sendingnumber);
    }


}

