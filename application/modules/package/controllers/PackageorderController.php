<?php

class Package_PackageorderController extends Rest_Controller_Action_DbTable
{

	public function refreshAction() {
		$filter = $this->getRequest()->getParam('filter', false);
		
		$filters = json_decode($filter);
		
		$order_combine_id = 0;
		
		foreach ($filters as $filter) {
			if ($filter->property == "order_combine_id") {
				$order_combine_id = $filter->value;
			}
		}
		
		$service = $this->getService();
		
		$service->createPackages($order_combine_id);
		
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
