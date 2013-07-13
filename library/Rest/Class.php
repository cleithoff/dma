<?php 

class Rest_Class {
	
	public static function load($class) {
		if (!class_exists($class)) {
			$class = explode('_', $class);
			$module = reset($class);
			$controller = end($class);
			require_once(APPLICATION_PATH . '/modules/' . strtolower($module) . '/models/' . $controller . '.php');
			require_once(APPLICATION_PATH . '/modules/' . strtolower($module) . '/models/DbTable/' . $controller . '.php');
		}
	}
	
}