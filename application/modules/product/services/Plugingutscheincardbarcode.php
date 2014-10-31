<?php

class Product_Service_Plugingutscheincardbarcode extends Product_Service_Plugin {

	public function merge(Order_Model_Item $order_item, DOMDocument $xml) {
		
		$reportReportService = new Report_Service_Report();
		
		$request = new Zend_Controller_Request_Http("http://" . $_SERVER['HTTP_HOST'] . "/report/report/index");
		
		$request->setParam('report_report_id', 15);
		$request->setParam('order_item_id', $order_item->id);
		
		$row = $reportReportService->getReport($request);
		
		$filename = $reportReportService->generateXml($row, $request);
		
		$doc = new DOMDocument('1.0', 'UTF-8');
		$doc->formatOutput = true;
		
		$doc->load($filename);
		
		// $child = $xmlGenerated->xpath('ReportDetail');
		// $xmlGenerated->
		
		// var_dump($child);die();
		$node = $xml->importNode($doc->documentElement, true);
		$xml->documentElement->appendChild($node->childNodes->item(0));
		
		// $xml->addChild('ReportDetail', $child);
		
		return $xml;
	}
	
}