<?php

class Report_ReportController extends Rest_Controller_Action_DbTable
{

	public function exportAction() {		
		$this->getService()->export($this->getRequest());
		die();
	}
}
