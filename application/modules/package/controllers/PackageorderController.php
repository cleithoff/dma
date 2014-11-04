<?php

class Package_PackageorderController extends Rest_Controller_Action_DbTable
{

	public function refreshAction() {
		$filter = $this->getRequest()->getParam('filter', false);
		
		$filters = json_decode($filter);
		
		$order_combine_id = $this->getRequest()->getParam('order_combine_id', 0);
		$order_item_id = $this->getRequest()->getParam('order_item_id', 0);;
		
		if (!empty($order_item_id)) {
			$service = new Package_Service_Package();
			$service->createPackages($order_item_id, 0, 0);
		} else {
			$service = $this->getService();
			$service->createPackages($order_combine_id);
		}
		
		return $this->forward('get');
		
		// return $this->getAction();
	}
	
	public function repairAction() {
		
		$service = $this->getService();
		
		$orderCombine = new Order_Model_DbTable_Combine();
		//$importStack = new Import_Model_DbTable_
		
		$orderCombineRows = $orderCombine->fetchAll();
		
		foreach ($orderCombineRows as $orderCombineRow) {
			if (strpos($orderCombineRow->getImportStack()->title, 'GSC_') !== false) {
				$service->createPackages($orderCombineRow->id);
			}
		}
	}
	
}
