<?php

class Ext_container_AbstractContainer extends Ext_Component {
	
	public $initialView = null;
	public $items = array();
	public $layout = null;
	
	public function __construct(array $config = array()) {
		parent::__construct($config);
	}
	
	protected function _create($component) {
		if ($component instanceof Ext_Base) {
			$class = $component;
		} else if ($component instanceof stdClass) {
			$class = Ext_Enums_Widget::$enum[$component->xtype];
			$config = (array) $component;
			$class = new $class($config);
		} else if (is_string($component)) {
			$class = Ext_Enums_Widget::$enum[$component];
			$config = (array) $component;
			$class = new $class($config);
		} else {
			return false;
		}		
		return $class;
	}
	
	public function add($component) {
		if (is_array($component)) {
			foreach ($component as $value) {
				$class = $this->_create($value);
				if (!empty($class)) {
					$class->setOwnerCt($this);
					array_push($this->items, $class);
				}
			}
		} else {
			$class = $this->_create($component);
			if (!empty($class)) {
				$class->setOwnerCt($this);
				array_push($this->items, $class);
				return $class; 
			}				
		}
		return null;
	}
	
	public function insert($index, $component) {	
		$class = $this->_create($component);
		array_splice( $this->items, $index, 0, array($class));
		return $class;
	}
	
}