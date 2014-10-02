<?php

class Package_Service_Packageorder
{

	const FRAME_PALETTE = 1;
	const FRAME_KARTON = 2;
	const FRAME_KREUZPACK = 3;
	
	const PACKAGE_100ER = 1;
	const PACKAGE_200ER = 2;
	const PACKAGE_300ER = 3;
	const PACKAGE_POS = 4;
	
	const PACKAGE_100ER_2014 = 5;
	const PACKAGE_200ER_2014 = 6;
	const PACKAGE_300ER_2014 = 7;
	const PACKAGE_POS_2014 = 8;
	
	const UMKARTON_WEIGHT = 0.75;
	
	public function createPackages($order_combine_id, $haspos = false, $weightpos = 0) {
		
		$orderCombine = new Order_Model_DbTable_Combine();
		$orderPool = new Order_Model_DbTable_Pool();
		$orderOrder = new Order_Model_DbTable_Order();
		$orderItem = new Order_Model_DbTable_Item();
		$productItem = new Product_Model_DbTable_Item();
		$productProduct = new Product_Model_DbTable_Product();
		$productPackage = new Product_Model_DbTable_Package();
		$productItemHasProductCustomize = new Product_Model_DbTable_Itemhasproductcustomize();
		$productCustomize = new Product_Model_DbTable_Customize();
		$packagesPackagesOrder = new Package_Model_DbTable_Packageorder();
		
		//$orderPoolRow = $orderPool->fetchRow("id = " . intval($order_pool_id));
		//$order_combine_id = $orderPoolRow->order_combine_id;

		$orderPoolRows = $orderPool->fetchAll("order_combine_id = " . intval($order_combine_id));
		
		$weight = self::UMKARTON_WEIGHT;
		
		foreach ($orderPoolRows as $orderPoolRow) {
			
			$orderItemRows = $orderItem->fetchAll("order_pool_id = " . intval($orderPoolRow->id));

			foreach ($orderItemRows as $orderItemRow) {
				$productItemRow = $productItem->find($orderItemRow->product_item_id)->current();
				$productProductRow = $productProduct->find($productItemRow->product_product_id)->current();
				$productPackageRow = $productPackage->find($productProductRow->id)->current();
				
				$productItemHasProductCustomizes = $productItemHasProductCustomize->fetchAll("product_item_id = " . $productItemRow->id);
				
				$ve = 1; // Verkaufseinheit;
				
				foreach ($productItemHasProductCustomizes as $productItemHasProductCustomizeRow) {
					$productCustomizeRow = $productCustomize->find($productItemHasProductCustomizeRow->product_customize_id)->current();
					if ($productCustomizeRow->key === "ve") {
						$ve = $productItemHasProductCustomizeRow->value;
					}
				}
				
				$amount = $orderItemRow->amount / $ve;
				$weight += $productItemRow->weight * $amount;
			}
			
		}
		
		$packagesPackagesOrderRow = $packagesPackagesOrder->fetchRow("order_combine_id = " . $order_combine_id);
		
		if (empty($packagesPackagesOrderRow)) {
			$packagesPackagesOrderRow = $packagesPackagesOrder->createRow(array(
					'frame_type_id' => self::FRAME_KARTON,
					'order_combine_id' => $order_combine_id,
					'amount' => 0,
					'weight' => $weight,
					'count' => 1,
			));
		} else {
			$packagesPackagesOrderRow->weight = $weight;
		}
		$packagesPackagesOrderRow->save();
		
		//$method = 'createPackagesFleurop2011';
		
		/*
		$orderItem = new Order_Model_DbTable_Item();
		$productItem = new Product_Model_DbTable_Item();
		$productProduct = new Product_Model_DbTable_Product();
		$productPackage = new Product_Model_DbTable_Package();
		
		$orderItemRow = $orderItem->find($order_item_id)->current();
		$productItemRow = $productItem->find($orderItemRow->product_item_id)->current();
		$productProductRow = $productProduct->find($productItemRow->product_product_id)->current();
		$productPackageRow = $productPackage->find($productProductRow->id)->current();

		$method = $productPackageRow->method;
		
		$this->$method($order_order_id, $haspos, $weightpos);
		*/
		
	}

}