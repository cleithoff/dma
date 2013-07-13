<?php

class Import_LinesController extends Rest_Controller_Action_DbTable
{

	public function importAction() {
		$this->getService()->import();
	}
	
	public function assembleAction() {
		$this->getService()->assemble();
	}
}
