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
    
    protected function checkPartnerAbsence($packagePackageorder) {
    	
    	// $packagePackageorder->getOrderCombine()->partner;
    	
    	$partnerAbsences = new Partner_Model_DbTable_Absence();
    	
    	$partnerAbsence = $partnerAbsences->fetchRow("SUBDATE(`from`, INTERVAL 4 DAY) >= NOW() AND NOW() <= `until` AND partner_partner_id = " . $packagePackageorder->getOrderCombine()->partner_partner_id); // . Zend_Db_Table::getDefaultAdapter()->quote($authkey));

    	//$partnerAbsence = $partnerAbsences->fetchRow("partner_partner_id = 1780"); // . Zend_Db_Table::getDefaultAdapter()->quote($authkey));
    	   
    	if (!empty($partnerAbsence)) {
    		throw new Exception("Sendungsnummer und Versandtermin wurden NICHT gespeichert, da eine Annahme zwischen " . date('d.m.Y', strtotime($partnerAbsence->from)) . " bis " . date('d.m.Y', strtotime($partnerAbsence->until)) . " nicht möglich ist.");
    	}
    	
    	return true;
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
        			if ($this->checkPartnerAbsence($packagePackageorder)) {
	        			$packagePackageorder->outgoing = date('Y-m-d H:i:s', time());
	        			$packagePackageorder->sendingnumber = $sendingnumber;
	        			$packagePackageorder->save();
	        			$this->view->assign('success', "Sendungsnummer und Versandtermin wurden gespeichert.");
        			}
        		} catch (Exception $ex) {
        			$this->view->assign('message', $ex->getMessage());
        		}
        		
        }
        
        $this->view->assign('cmd', $cmd);
        $this->view->assign('authkey', $authkey);
        $this->view->assign('sendingnumber', $sendingnumber);
    }


}

