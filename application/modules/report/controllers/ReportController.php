<?php

class Report_ReportController extends Rest_Controller_Action_DbTable
{

	public function indexAction() {
		Zend_Db_Table::getDefaultAdapter()->query("SET @counter:=0;");
		parent::indexAction();
	}
	
	public function executeAction() {
		$this->getService()->execute($this->getRequest());
		die();
	}
	
	
	public function exportcsvAction() {		
		$this->getService()->exportcsv($this->getRequest());
		die();
	}
	
	public function exportxmlAction() {
		$this->getService()->exportxml($this->getRequest());
		die();
	}
	
	public function exportxsdAction() {
		$this->getService()->exportxsd($this->getRequest());
		die();
	}
	
	public function exportpdfAction() {
		$this->getService()->exportpdf($this->getRequest());
		die();
	}
}
