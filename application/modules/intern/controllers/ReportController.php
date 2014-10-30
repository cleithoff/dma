<?php

class Intern_ReportController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function gsclieferscheinAction()
    {
        // action body
        $order_combine_id = $this->getRequest()->getParam('order_combine_id');
        
        
        $orderCombines = new Order_Model_DbTable_Combine();
        
        $orderCombine = $orderCombines->find($order_combine_id)->current();
        
        $service = new Report_Service_Report();
        
        $this->getRequest()->setParam('partner_nr', $orderCombine->getPartnerPartner()->partner_nr);
        $this->getRequest()->setParam('import_stack_id', $orderCombine->import_stack_id);
        
        $service->exportpdf($this->getRequest());
                
    }


}



