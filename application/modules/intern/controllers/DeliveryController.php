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
    
    public function indexAction()
    {
		// action body
    	$this->_helper->layout->setLayout('intern');
    	
        $cmd = $this->getRequest()->getParam('cmd', null);
        
        $authkey = $this->getRequest()->getParam('authkey', null);
        
        if ($cmd == "Speichern") {
        	try {
	        	$packagePackageorders = $this->getOrderCombine($authkey)->depPackagePackageorder();
	        	if (count($packagePackageorders) == 0) {
	        		throw new Exception("Keine Pakete gefunden.");
	        	}
	        	foreach ($packagePackageorders as $packagePackageorder) {
		        	$packagePackageorder->outgoing = date('Y-m-d H:i:s', time());
		        	$packagePackageorder->save();
	        	}
	        	$this->view->assign('success', "Liefertermin wurde gespeichert.");
        	} catch (Exception $ex) {
        		$this->view->assign('message', $ex->getMessage());
        	}
        }
        
        $this->view->assign('cmd', $cmd);
        $this->view->assign('authkey', $authkey);
    }


}

