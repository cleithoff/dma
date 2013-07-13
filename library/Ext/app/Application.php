<?php

class Ext_app_Application extends Ext_app_Controller {

	public $name = null;
	public $appFolder = 'app';
	public $autoCreateViewport = true;
	public $controllers = array();
	
	private $_controllers = array();
	private $_resources = array();
	
	protected $_metaType = 'application';
	private $_metaFolder = 'metadata';
	
    /**
     * Singleton instance
     *
     * @var Zend_Auth
     */
    private static $_instance = null;
	
    /**
     * Returns an instance
     *
     * Singleton pattern implementation
     *
     * @return Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct(array $config = array()) {
    	parent::__construct($config);
    	 $library = new Ext_Resource(array('name' => 'Library'));
    	 $library->setUserClassName('Library');
    	 $this->_resources[] = $library;
    	 $this->setUserClassName('Application');
    }
	
    protected function _getPath() {
    	return '';
    }
    
	public function getName() {
		return $this->name;
	}
	
	public function getAppFolder() {
		return $this->appFolder;
	}

	public function getMetaFolder() {
		return $this->_metaFolder;
	}
	
	public function existsController($userClassName) {
		foreach ($this->controllers as $controller) {
			if ($userClassName == $controller) {
				return $this->_controllers[$userClassName];
			}
		}
		return false;
	}
	
	public function createController($userClassName) {
		$controller = $this->existsController($userClassName);
		if (empty($controller)) {
			$controller = new Ext_app_Controller();
			$controller->setUserClassName($userClassName);
			$this->controllers[] = $userClassName;
			$this->_controllers[$userClassName] = $controller;
		}
		return $controller;
	}
	
	public function getController($userClassName) {
		return $this->existsController($userClassName);
	}
	
	public function getControllers() {
		return $this->_controllers;
	}
	
	public function getResources() {
		return $this->_resources;
	}
	
	protected function _saveMeta($appRoot) {
		

		//parent::_saveMeta($appRoot);
		
		$meta = array(
				'name' => strtolower($this->name),
				'settings' => array(
						'urlPrefix' => '',
						'spacesToIndent' => 4,
						'exportPath' => '',
						'sdkPath' => '',
						'lineEnding' => 'CRLF',
						'genTimestamps' => false,
						'cacheBust' => false
						),
				'xdsVersion' => "2.1.0",
				'xdsBuild' => 676,
				'schemaVersion' => 1,
				'upgradeVersion' => 210000000498,
				'framework' => 'ext41',
				'topInstanceFileMap' => array(
						),				
				'viewOrderMap' => array(
						'view' => array(),
						"store" => array(),
						"controller" => array(),
						"model" => array(),
						"resource" => array(),
						"app" => array(
								"application"
								)						
						),				
				);
		
		foreach ($this->getStores() as $item) {
			$_item = array(
					'paths' => array(
								Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName(),
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/override/' . $item->getUserClassName() . '.js',
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName() . '.js',
								),
					'className' => $item->getUserClassName(),
					);
			$meta['topInstanceFileMap'][$item->getDesignerId()] = $_item;
			$meta['viewOrderMap']['store'][] = $item->getDesignerId();
		}

		foreach ($this->getControllers() as $item) {
			$_item = array(
					'paths' => array(
								Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName(),
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/override/' . $item->getUserClassName() . '.js',
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName() . '.js',
								),
					'className' => $item->getUserClassName(),
					);
			$meta['topInstanceFileMap'][$item->getDesignerId()] = $_item;
			$meta['viewOrderMap']['controller'][] = $item->getDesignerId();
		}

		foreach ($this->getResources() as $item) {
			$_item = array(
					'paths' => array(
							Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName(),
					),
					'className' => $item->getUserClassName(),
					);
			$meta['topInstanceFileMap'][$item->getDesignerId()] = $_item;
			$meta['viewOrderMap']['resource'][] = $item->getDesignerId();
		}
		
		foreach ($this->getModels() as $item) {
			$_item = array(
					'paths' => array(
								Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName(),
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/override/' . $item->getUserClassName() . '.js',
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName() . '.js',
								),
					'className' => $item->getUserClassName(),
					);
			$meta['topInstanceFileMap'][$item->getDesignerId()] = $_item;
			$meta['viewOrderMap']['model'][] = $item->getDesignerId();
		}
		
		foreach ($this->getViews() as $item) {
			$_item = array(
					'paths' => array(
								Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName(),
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/override/' . $item->getUserClassName() . '.js',
								Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/' . $item->getUserClassName() . '.js',
								),
					'className' => $item->getUserClassName(),
					);
			if ($item->initialView === true) {
				$_item['paths'][] = Ext_app_Application::getInstance()->getMetaFolder() . '/' . $item->getPath() . '/Viewport.js';
				
				$viewport = new Ext_container_Viewport();
				$viewport->setUserClassName('Viewport');
				$viewport->extend = Ext_app_Application::getInstance()->getName() . '.view.' .  $item->getUserClassName();
				
				$filename = str_replace('\\', '/', $appRoot) . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $item->getPath() . '/Viewport.js';
				$this->_mkdir($filename);
				
				$content = <<<EOT
Ext.define('%s.view.Viewport', {
	extend: '%s.view.%s',
	renderTo: Ext.getBody(),
	requires: [ %s
	]
});
EOT;
				$requires = array();
				foreach ($this->getViews() as $component) {
					$requires[] = "'" . Ext_app_Application::getInstance()->getName() . ".view." . $component->getUserClassName() . "'";
				}
				$requires = implode(',', $requires);
				$data = sprintf($content,
						Ext_app_Application::getInstance()->getName(),
						Ext_app_Application::getInstance()->getName(),
						$item->getUserClassName(),
						$requires
				);
				
				file_put_contents($filename, $data);
								

			}
			$meta['topInstanceFileMap'][$item->getDesignerId()] = $_item;
			$meta['viewOrderMap']['view'][] = $item->getDesignerId();
		}

		$filename = str_replace('\\', '/', $appRoot) . '/' . strtolower($this->name). '.xds';
		$this->_mkdir($filename);
		$json = Zend_Json::encode($meta);
		$data = Zend_Json::prettyPrint($json);
		file_put_contents($filename, $data);
		
		
		$filename = str_replace('//', '/', str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getMetaFolder() . '/' . $this->_getPath()) . '/' . $this->getUserClassName());
		$this->_mkdir($filename);
		
		$json = Zend_Json::encode($this->getMeta());
		$data = Zend_Json::prettyPrint($json);
		
		file_put_contents($filename, $data);		
	}
	
	protected function _saveApp($appRoot) {
		
		$filename = str_replace('\\', '/', $appRoot) . '/app.js';
		$this->_mkdir($filename);
		
		$json = Zend_Json::encode($this);
		$json = Zend_Json::prettyPrint($json);
		
$content = <<<EOT
		Ext.Loader.setConfig({
			enabled: true
		});
		
		Ext.application(%s);
EOT;
		$data = sprintf($content,
				$json
		);
		
		file_put_contents($filename, $data);
	}
	
	protected function _saveHtml($appRoot) {
		$filename = str_replace('\\', '/', $appRoot) . '/app.html';
		$this->_mkdir($filename);
		
$content = <<<EOT
<!DOCTYPE html>

<!-- Auto Generated with Sencha Architect -->
<!-- Modifications to this file will be overwritten. -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>%s</title>
    <script src="%sext-all-debug-w-comments.js"></script>
    <link rel="stylesheet" href="%sresources/css/ext-all.css">
    <script type="text/javascript" src="app.js?_dc=%s"></script>
    <link rel="stylesheet" href="ext-override.css">
</head>
<body></body>
</html>
EOT;
		$library = reset($this->_resources);

		$data = sprintf($content, $this->name, $library->basePath, $library->basePath, intval(microtime(true)*1000));

		file_put_contents($filename, $data);
		
		$filename = str_replace('\\', '/', $appRoot) . '/ext-override.css';
		$this->_mkdir($filename);
		
		$content = <<<EOT
		.x-mask {background:none}
		.x-item-disabled .x-form-item-label, .x-item-disabled .x-form-field, .x-item-disabled .x-form-cb-label, .x-item-disabled .x-form-trigger {opacity:0.6}
EOT;
		
		file_put_contents($filename, $content);		
	}
	
	public function save($appRoot) {
		foreach ($this->getControllers() as $object) {
			$object->save($appRoot);
		}
		foreach ($this->getStores() as $object) {
			$object->save($appRoot);
		}
		foreach ($this->getModels() as $object) {
			$object->save($appRoot);
		}
		foreach ($this->getViews() as $object) {
			$object->save($appRoot);
		}
		foreach ($this->getResources() as $object) {
			$object->saveMeta($appRoot);
		}
		
		$this->_saveApp($appRoot);
		$this->_saveMeta($appRoot);
		$this->_saveHtml($appRoot);
	}
	
}