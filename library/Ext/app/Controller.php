<?php

class Ext_app_Controller extends Ext_Base {
	
	public $models = array();
	public $stores = array();
	public $views = array();
	public $refs = array();
	public $associations = array();
	
	public $listener = array();
	
	private $_models = array();
	private $_stores = array();
	private $_views = array();
	private $_refs = array();
	
	protected $_metaType = 'controller';
	
	public function addListener(Ext_Action $action) {
		array_push($this->listener, $action);
		return $action;
	}
	
	
	protected function _getPattern() {
		return 'controller';
	}	
	
	protected function _getPath() {
		return 'controller';
	}	

	public function existsRef($userClassName) {
		foreach ($this->refs as $ref) {
			if ($userClassName == $ref) {
				return $this->_refs[$userClassName];
			}
		}
		return false;
	}
	
	public function createRef($userClassName, $config) {
		$ref = $this->existsRef($userClassName);
		if (empty($ref)) {
			$ref = new Ext_data_Ref($config);
			$ref->setUserClassName($userClassName);
			$this->refs[] = $ref;
			$this->_refs[$userClassName] = $ref;
		}
		return $ref;
	}
	
	public function getRef($userClassName) {
		return $this->existsRef($userClassName);
	}	
	
	public function getRefs() {
		return $this->_refs;
	}
	
	public function existsModel($userClassName) {
		foreach ($this->models as $model) {
			if ($userClassName == $model) {
				return $this->_models[$userClassName];
			}			
		}
		return false;
	}
	
	public function createModel($userClassName) {
		$model = $this->existsModel($userClassName);
		if (empty($model)) {
			$model = new Ext_data_Model();
			$model->setUserClassName($userClassName);
			$this->models[] = $userClassName;
			$this->_models[$userClassName] = $model;
		}
		return $model;
	}
	
	public function addAssociationHasMany($modelUserClassName, $association) {
		$this->associations[$modelUserClassName]['hasMany'][] = $association;
	}
	
	public function addAssociationBelongsTo($modelUserClassName, $association) {
		$this->associations[$modelUserClassName]['belongsTo'][] = $association;
	}
	
	
	public function getModel($userClassName) {
		return $this->existsModel($userClassName);
	}
	
	public function getModels() {
		return $this->_models;
	}
	
	public function existsStore($userClassName) {
		foreach ($this->stores as $store) {
			if ($userClassName == $store) {
				return $this->_stores[$userClassName];
			}
		}
		return false;
	}
	
	public function createStore($userClassName, $metaType) {
		$store = $this->existsStore($userClassName);
		if (empty($store)) {
			$store = new Ext_data_Store();
			$store->setUserClassName($userClassName);
			$store->setMetaType($metaType);
			$store->setUserAlias($userClassName);
			$store->storeId = $userClassName;
			$this->stores[] = $userClassName;
			$this->_stores[$userClassName] = $store;	
		}
		return $store;
	}
	
	public function getStore($userClassName) {
		return $this->existsStore($userClassName);
	}
	
	public function getStores() {
		return $this->_stores;
	}	
	
	public function existsView($userClassName) {
		foreach ($this->views as $view) {
			if ($userClassName == $view) {
				return $this->_views[$userClassName];
			}
		}
		return false;
	}
	
	public function createView($class, $userClassName, $metaType = null) {
		$view = $this->existsView($userClassName);
		if (empty($view)) {
			$view = new $class();
			$view->setUserClassName($userClassName);
			if (!empty($metaType)) {
				$view->setMetaType($metaType);
			}
			$this->views[] = $userClassName;
			$this->_views[$userClassName] = $view;
		}
		return $view;
	}
	
	public function getView($userClassName) {
		return $this->existsView($userClassName);
	}
	
	public function getViews() {
		return $this->_views;
	}
	
}