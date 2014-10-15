<?php

class Intern_GscbanderoleController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    protected function getOrderItem($authkey) {
    	$orderItems = new Order_Model_DbTable_Item();
    	
    	$orderItem = $orderItems->fetchRow("authkey = " . Zend_Db_Table::getDefaultAdapter()->quote($authkey));
    	
    	if (empty($orderItem)) {
    		throw new Exception("Produktcode nicht gefunden.");
    	}
    	
    	return $orderItem;
    }
    
    protected function getBarcodeCount($orderItem) {
    	$productCustomizes = new Product_Model_DbTable_Customize();	
    	
    	$productCustomizeVE = $productCustomizes->fetchRow('`key` = "ve"');
    	
    	if (empty($productCustomizeVE)) {
    		throw new Exception("Product Customize Key ve nicht gefunden.");
    	}
    	
    	$productItemhasproductcustomizes = new Product_Model_DbTable_Itemhasproductcustomize();
    	$productItemhasproductcustomizeVE = $productItemhasproductcustomizes->fetchRow('product_item_id = ' . intval($orderItem->product_item_id) . ' AND product_customize_id = ' . intval($productCustomizeVE->id));
    	
    	if (empty($productItemhasproductcustomizeVE)) {
    		throw new Exception("Product Customize Key ve für Product Item nicht gefunden.");
    	}
    	
    	
    	
    	$barcodeCount = floor($orderItem->amount/$productItemhasproductcustomizeVE->value);
    	if (empty($barcodeCount)) {
    		throw new Exception("Produkt benötigt keine Banderolencodes.");
    	}
    	return $barcodeCount;
    }
    
    protected function deleteBarcodes($orderItem) {
    	$orderItemHasOrderMetas = new Order_Model_DbTable_Itemhasordermeta();
    	$orderMetas = new Order_Model_DbTable_Meta();
    	$orderMeta = $orderMetas->fetchRow("`key` = \"barcode_banderole\"");
    	 
    	if (empty($orderMeta)) {
    		throw new Exception("Order Meta Key barcode_banderole nicht gefunden.");
    	}
    	
    	$orderItemHasOrderMetas->delete("order_item_id = " . intval($orderItem->id) . " AND order_meta_id = " . intval($orderMeta->id));
    }
    
    protected function saveBarcodes($orderItem) {
    	$orderItemHasOrderMetas = new Order_Model_DbTable_Itemhasordermeta();
    	$orderMetas = new Order_Model_DbTable_Meta();
    	$orderMeta = $orderMetas->fetchRow("`key` = \"barcode_banderole\"");
    	
    	if (empty($orderMeta)) {
    		throw new Exception("Order Meta Key barcode_banderole nicht gefunden.");
    	}
    	
    	$barcodes = $this->getRequest()->getParam('barcode', array());
    	if (is_array($barcodes)) {
    		foreach ($barcodes as $barcode) {
    			$orderItemHasOrderMeta = $orderItemHasOrderMetas->createRow();
    			$orderItemHasOrderMeta->order_item_id = $orderItem->id;
    			$orderItemHasOrderMeta->order_meta_id = $orderMeta->id;
    			$orderItemHasOrderMeta->order_meta_key = $orderMeta->key;
    			$orderItemHasOrderMeta->value = $barcode;
    			$orderItemHasOrderMeta->save();
    		}
    	}
    }
    
    protected function getBarcodes($orderItem) {
    	$orderItemHasOrderMetas = new Order_Model_DbTable_Itemhasordermeta();
    	$orderMetas = new Order_Model_DbTable_Meta();
    	$orderMeta = $orderMetas->fetchRow("`key` = \"barcode_banderole\"");
    	 
    	if (empty($orderMeta)) {
    		throw new Exception("Order Meta Key barcode_banderole nicht gefunden.");
    	}
    	
    	$orderItemHasOrderMetaRows = $orderItemHasOrderMetas->fetchAll("order_item_id = " . intval($orderItem->id) . " AND order_meta_id = " . intval($orderMeta->id));
    	
    	$barcode = array();
    	
    	foreach ($orderItemHasOrderMetaRows as $orderItemHasOrderMetaRow) {
    		array_push($barcode, $orderItemHasOrderMetaRow->value);
    	}
    	return $barcode;
    }
    
    public function indexAction()
    {
        // action body
    	$this->_helper->layout->setLayout('intern');
    	
        $cmd = $this->getRequest()->getParam('cmd', null);
        
        $authkey = $this->getRequest()->getParam('authkey', null);
        
        if ($cmd == "Laden") {
        	try {
        		$orderItem = $this->getOrderItem($authkey);
        	} catch (Exception $ex) {
        		$this->view->assign('message', $ex->getMessage());
        	}
        }
        
        if ($cmd == "Speichern") {
        	try {
	        	$orderItem = $this->getOrderItem($authkey);
	        	$this->deleteBarcodes($orderItem);
	        	$this->saveBarcodes($orderItem);
	        	$this->view->assign('success', "Barcodes wurden gespeichert.");
        	} catch (Exception $ex) {
        		$this->view->assign('message', $ex->getMessage());
        	}
        }
        
        if (!empty($orderItem)) {
        	$this->view->assign('barcodes', $this->getBarcodes($orderItem));
        	$this->view->assign('barcodeCount', $this->getBarcodeCount($orderItem));
        }
        
        $this->view->assign('cmd', $cmd);
        $this->view->assign('authkey', $authkey);
        
    }


}

