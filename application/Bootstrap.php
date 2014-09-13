<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initSession() {
		Zend_Session::start();		
	}
	
	protected function _initConfig()
	{
		date_default_timezone_set('Europe/Berlin');
		$config = new Zend_Config($this->getOptions(), true);
		Zend_Registry::set('config', $config);
		return $config;
	}

}

