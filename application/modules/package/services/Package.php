<?php

class Package_Service_Package
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
	
	public function createPackages($order_item_id, $haspos, $weightpos) {
		
		//$method = 'createPackagesFleurop2011';
		
		$orderItem = new Order_Model_DbTable_Item();
		$productItem = new Product_Model_DbTable_Item();
		$productProduct = new Product_Model_DbTable_Product();
		$productPackage = new Product_Model_DbTable_Package();
		
		$orderItemRow = $orderItem->find($order_item_id)->current();
		$productItemRow = $productItem->find($orderItemRow->product_item_id)->current();
		$productProductRow = $productProduct->find($productItemRow->product_product_id)->current();
		$productPackageRow = $productPackage->find($productProductRow->idproduct_package)->current();

		$method = $productPackageRow->method;
		
		$this->$method($order_item_id, $haspos, $weightpos);
		
	}
	
	public function createPackagesFleurop2011($order_item_id, $haspos, $weightpos) {
	
		$package_package_ids = array();
	
		if (empty($order_item_id)) return false;
	
		$package = new Package_Model_DbTable_Package();
		$pi = new Package_Model_DbTable_Item();
	
		$package->delete('order_item_id = ' . intval($order_item_id) . ' AND readytosend IS NULL AND outgoing IS NULL AND sendingnumber IS NULL');
	
		//Rest_Class::load('Order_Model_DbTable_Order');
			
		$order = new Order_Model_DbTable_Item();
		$orderRow = $order->find($order_item_id)->current();
	
		$weight = array();
		$weightTable = new Product_Model_DbTable_Weight();
		$weightRowset = $weightTable->fetchAll('product_item_id = ' . intval($orderRow->product_item_id));
	
		foreach($weightRowset as $weightRow) {
			$weight[$weightRow['amount']] = $weightRow['weight'];
		}
	
		$amount = $orderRow->amount;
		if ($packageRowset = $package->fetchAll('order_item_id = ' . intval($order_item_id))) {
			foreach ($packageRowset as $packageRow) {
				$amount -= $packageRow->amount;
				/*if ($packageRow->haspos == 1) {
				 $orderRow->haspos = 0;
				}*/
			}
		}
			
		if ($amount <= 0) return false;
	
		$count = 1;
		$amount = intval(ceil($amount/100)*100);
	
		if ($amount > 3000) {
			$row = $package->createRow(array(
					'order_item_id' => $orderRow->id,
					'frame_type_id' => self::FRAME_PALETTE,
					'amount' => $amount,
					'count' => $count,
					//'haspos' => $orderRow->haspos,
			));
			$package_package_ids[] = $row->save();
		} else {
			$karton = intval($amount / 1200);
			$rest = $amount % 1200;
			for($i = 0; $i < $karton; $i++) {
				$row = $package->createRow(array(
						'order_item_id' => $orderRow->id,
						'frame_type_id' => self::FRAME_KARTON,
						'amount' => 1200,
						'count' => $count,
						'weight' => $weight['1200'],
				));
				$package_package_id = $row->save();
				$package_package_ids[] = $package_package_id;
	
	
				$pirow = $pi->createRow(array(
						'amount' => 1200,
						'count' => 4,
						'package_package_id' => $package_package_id,
						'package_type_id' => self::PACKAGE_300ER,
				));
				$pirow->save();
	
				$count++;
			}
	
			if (empty($rest)) {
				// POS als neues Paket einfügen
				if ($haspos) {
					$row = $package->createRow(array(
							'order_item_id' => $orderRow->id,
							'frame_type_id' => self::FRAME_KARTON,
							'amount' => 0,
							'count' => $count,
							'weight' => $weightpos,
					));
					$package_package_id = $row->save();
					$package_package_ids[] = $package_package_id;
						
					$pirow = $pi->createRow(array(
							'amount' => 0,
							'count' => 1,
							'package_package_id' => $package_package_id,
							'package_type_id' => self::PACKAGE_POS,
					));
					$pirow->save();
				}
	
			} else {
				$amount = $rest;
				$amount = intval(ceil($amount/100)*100);
	
				$row = $package->createRow(array(
						'order_item_id' => $orderRow->id,
						'frame_type_id' => self::FRAME_KARTON,
						'amount' => $amount,
						'count' => $count,
						'weight' => $weight[$amount] + ($haspos == 1 ? $weightpos : 0),
						//'haspos' => $orderRow->haspos,
				));
				$package_package_id = $row->save();
				$package_package_ids[] = $package_package_id;
	
				if ($amount > 200) {
					if ($amount == 400) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 400,
								'count' => 2,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_200ER,
						));
						$pirow->save();
					} else {
						$rest = $amount % 300;
						$pirow = $pi->createRow(array(
								'amount' => floor($amount / 300)*300,
								'count' => floor($amount / 300),
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_300ER,
						));
						$pirow->save();
					}
	
				}
	
				$amount = $rest;
	
				if ($amount > 0) {
					if ($amount == 100) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 100,
								'count' => 1,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_100ER,
						));
						$pirow->save();
					} else if ($amount == 200) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 200,
								'count' => 1,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_200ER,
						));
						$pirow->save();
					}
				}
	
				// POS einfügen
				if ($haspos) {
					$pirow = $pi->createRow(array(
							'amount' => 0,
							'count' => 1,
							'package_package_id' => $package_package_id,
							'package_type_id' => self::PACKAGE_POS,
					));
					$pirow->save();
				}
			}
		}
	
		foreach ($package_package_ids as $package_package_id) {
			$sql = 'UPDATE package_package SET maxcount = ' . intval(count($package_package_ids)) . ' WHERE id = ' . $package_package_id;
			Zend_Db_Table::getDefaultAdapter()->query($sql);
		}
	
		return true;
	}
	
	public function createPackagesFleurop2014($order_item_id, $haspos, $weightpos) {
		
		$package_package_ids = array();
		
		if (empty($order_item_id)) return false;
		
		$package = new Package_Model_DbTable_Package();
		$pi = new Package_Model_DbTable_Item();
		
		$package->delete('order_item_id = ' . intval($order_item_id) . ' AND readytosend IS NULL AND outgoing IS NULL AND sendingnumber IS NULL');
		  
		//Rest_Class::load('Order_Model_DbTable_Order');
		 
		$order = new Order_Model_DbTable_Item();
		$orderRow = $order->find($order_item_id)->current();

		$weight = array();
		$weightTable = new Product_Model_DbTable_Weight();		
		$weightRowset = $weightTable->fetchAll('product_item_id = ' . intval($orderRow->product_item_id));
		
		foreach($weightRowset as $weightRow) {
			$weight[$weightRow['amount']] = $weightRow['weight'];
		}
		
		$amount = $orderRow->amount;
		if ($packageRowset = $package->fetchAll('order_item_id = ' . intval($order_item_id))) {
			foreach ($packageRowset as $packageRow) {
				$amount -= $packageRow->amount;
				/*if ($packageRow->haspos == 1) {
					$orderRow->haspos = 0;
				}*/
			}
		}
		 
		if ($amount <= 0) return false;

		$count = 1;
		$amount = intval(ceil($amount/100)*100);

		if ($amount > 3000) {
			$row = $package->createRow(array(
					'order_item_id' => $orderRow->id,
					'frame_type_id' => self::FRAME_PALETTE,
					'amount' => $amount,
					'count' => $count,
					//'haspos' => $orderRow->haspos,
			));
			$package_package_ids[] = $row->save();
		} else {
			$karton = intval($amount / 1200);
			$rest = $amount % 1200;
			for($i = 0; $i < $karton; $i++) {
				$row = $package->createRow(array(
						'order_item_id' => $orderRow->id,
						'frame_type_id' => self::FRAME_KARTON,
						'amount' => 1200,
						'count' => $count,
						'weight' => $weight['1200'],
				));
				$package_package_id = $row->save();
				$package_package_ids[] = $package_package_id;
				
				
				$pirow = $pi->createRow(array(
						'amount' => 1200,
						'count' => 4,
						'package_package_id' => $package_package_id,
						'package_type_id' => self::PACKAGE_300ER_2014,
						));
				$pirow->save();
								
				$count++;
			}
	
			if (empty($rest)) {
				// POS als neues Paket einfügen
				if ($haspos) {
					$row = $package->createRow(array(
							'order_item_id' => $orderRow->id,
							'frame_type_id' => self::FRAME_KARTON,
							'amount' => 0,
							'count' => $count,
							'weight' => $weightpos,
					));
					$package_package_id = $row->save();
					$package_package_ids[] = $package_package_id;
					
					$pirow = $pi->createRow(array(
							'amount' => 0,
							'count' => 1,
							'package_package_id' => $package_package_id,
							'package_type_id' => self::PACKAGE_POS_2014,
					));
					$pirow->save();
				}
				
			} else {
				$amount = $rest;
				$amount = intval(ceil($amount/100)*100);
				
				$row = $package->createRow(array(
						'order_item_id' => $orderRow->id,
						'frame_type_id' => self::FRAME_KARTON,
						'amount' => $amount,
						'count' => $count,
						'weight' => $weight[$amount] + ($haspos == 1 ? $weightpos : 0),
						//'haspos' => $orderRow->haspos,
				));
				$package_package_id = $row->save();
				$package_package_ids[] = $package_package_id;
				
				if ($amount > 200) {	
					if ($amount == 400) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 400,
								'count' => 2,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_200ER_2014,
						));
						$pirow->save();						
					} else {						
						$rest = $amount % 300;
						$pirow = $pi->createRow(array(
								'amount' => floor($amount / 300)*300,
								'count' => floor($amount / 300),
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_300ER_2014,
						));
						$pirow->save();
					}

				} 
				
				$amount = $rest;
				
				if ($amount > 0) {
					if ($amount == 100) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 100,
								'count' => 1,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_100ER_2014,
						));
						$pirow->save();					
					} else if ($amount == 200) {
						$rest = 0;
						$pirow = $pi->createRow(array(
								'amount' => 200,
								'count' => 1,
								'package_package_id' => $package_package_id,
								'package_type_id' => self::PACKAGE_200ER_2014,
						));
						$pirow->save();						
					}
				}
				
				// POS einfügen
				if ($haspos) {
					$pirow = $pi->createRow(array(
							'amount' => 0,
							'count' => 1,
							'package_package_id' => $package_package_id,
							'package_type_id' => self::PACKAGE_POS_2014,
					));
					$pirow->save();
				}
			}
		}
		
		foreach ($package_package_ids as $package_package_id) {
			$sql = 'UPDATE package_package SET maxcount = ' . intval(count($package_package_ids)) . ' WHERE id = ' . $package_package_id;
			Zend_Db_Table::getDefaultAdapter()->query($sql);
		}
		
		return true;
	}
}

