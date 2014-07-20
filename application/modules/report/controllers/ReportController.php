<?php

class Report_ReportController extends Rest_Controller_Action_DbTable
{

	public function executeAction() {
		$this->getService()->execute($this->getRequest());
		die();
	}
	
	
	public function exportAction() {		
		$this->getService()->export($this->getRequest());
		die();
	}
}
