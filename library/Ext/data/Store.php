<?php

class Ext_data_Store extends Ext_data_AbstractStore {
	
	public $model = null;
	public $remoteGroup = false;
	public $sortOnFilter = false;
	
	protected function _getPattern() {
		return 'store';
	}
	
	protected function _getPath() {
		return 'store';
	}
	
	public function setModel($name) {
		$this->model = $name;
		$this->addRequires('"' . Ext_app_Application::getInstance()->name . '.model.' . $name . '"');
	}
	
	public function getModelApp() {
		return Ext_app_Application::getInstance()->name . '.model.' . $this->model;
	}
}