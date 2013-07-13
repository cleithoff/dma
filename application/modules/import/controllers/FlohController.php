<?php

class Import_FlohController extends Rest_Controller_Action_DbTable
{


	public function importAction() {
		$this->getService()->import();
	}
	
}
