<?php 

class Ext_Architect {
	
	public $displayName = null;
	
	private $_userClassName = null;
	private $_userAlias = null;
	private $_customEvent = array();
	private $_eventBinding = array();
	private $_functions = array();
	
	protected $_metaType = null;
	protected $_metaReferenceName = 'items';
	protected $_metaReferenceType = 'array';
	
	protected $_requires = array();
	
	protected static $_counter = array();
	
	public function get($userClassName) {
		//echo $userClassName . ":"  . $this->getMetaType() . ':' . $this->getUserClassName() .  "\n";
		if ($this->getUserClassName() == $userClassName) {
			return $this;
		} else {
			foreach($this as $property => $value) {
				if ($value instanceof Ext_Base) {
					$ret = $value->get($userClassName);
					if (!empty($ret)) {
						return $ret;
					}
				} elseif (is_array($value)) {
					foreach ($value as $key => $val) {
						if ($val instanceof Ext_Base) {
							$ret = $val->get($userClassName);
							if (!empty($ret)) {
								return $ret;
							}
						}
					}
				}
			}
		}
		return null;
	}

	public static function getCounter($class) {
		if (empty(self::$_counter[$class])) {
			self::$_counter[$class] = 1;
		} else {
			self::$_counter[$class]++;
		}
		return self::$_counter[$class];
	}
		
	public function __construct() {
		$class = explode('_', get_class($this));
		array_shift($class);
		$class = implode('', $class);
		$count = Ext_app_Application::getCounter($class);		
		$this->_userClassName = $class . $count;
	}

	public function setMetaType($metaType) {
		$this->_metaType = $metaType;
	}
	
	public function getMetaType() {
		return $this->_metaType;
	}

	public function getMetaReferenceName() {
		return $this->_metaReferenceName;
	}
	
	public function getMetaReferenceType() {
		return $this->_metaReferenceType;
	}
	
	protected function _getDesignerId() {
		$id = md5($this->_userClassName);
		$id = substr($id,0,8) . '-' . substr($id,8,4) . '-' . substr($id,12,4) . '-' . substr($id,16,4) . '-' . substr($id,20,12);
		return $id;
	}
	
	public function getDesignerId() {
		return $this->_getDesignerId();
	}
	
	public function getMeta() {
		$meta = array();
		$meta['type'] = $this->getMetaType();
		$meta['reference']['name'] = $this->getMetaReferenceName();
		$meta['reference']['type'] = $this->getMetaReferenceType();
		$meta['codeClass'] = null;
		$meta['userConfig'] = Zend_Json::decode(Zend_Json::encode($this));
		
		foreach($meta['userConfig'] as $key => $value) {
			if ($value === null) {
				unset($meta['userConfig'][$key]);
			}
		}
		
		$meta['cn'] = array(); //$this->_getCn();
		
		foreach ($this as $property => $value) {
			if (!empty($value)) {
				switch ($property) {
					case 'region' :
						$meta['userConfig']['layout|' . $property] = $value;
						unset($meta['userConfig'][$property]);
						continue;
						break;
					case 'targetType':
					case 'controlQuery':
					case 'initialView':
						$meta['userConfig']['designer|' . $property] = $value;
						unset($meta['userConfig'][$property]);
						continue;
						break;
				}
			}
			if (is_array($value)) {
				$found = false;
				foreach ($value as $key => $val) {
					if ($val instanceof Ext_Base) {
						$found = true;
						$meta['cn'][] = $val->getMeta();
					}
				}
				if ($found) {
					unset($meta['userConfig'][$property]);
				}
				if (count($value) == 0) {
					unset($meta['userConfig'][$property]);
				}
			} else {
				if ($value instanceof Ext_Base) {
					$meta['cn'][] = $value->getMeta();
					unset($meta['userConfig'][$property]);
				}
			}
		}
		
		$meta['userConfig']['designer|userClassName'] = $this->_userClassName;
		if (!empty($this->_userAlias)) {
			$meta['userConfig']['designer|userAlias'] = $this->_userAlias;
		}
		$meta['id'] = 'ExtBox1-ext-gen' . + abs(intval(microtime(true) * 1000) - mt_rand());
		$meta['designerId'] = $this->_getDesignerId();

		if (count($meta['cn']) == 0) {
			unset($meta['cn']);
		}
		return $meta;
	}
	
	public function setMeta($meta) {
		$this->_meta = $meta;
	}
	
	private $_extapp = null;
	
	public function getExtend() {
		return str_replace('_', '.', get_class($this));
	}
	
	public function setExtApp($extapp) {
		$this->_extapp = $extapp;
	}
	
	public function getExtApp($extapp) {
		return $this->_extapp;
	}
	
	public function setUserClassName($name) {
		$this->_userClassName = $name;
	}
	
	public function getUserClassName() {
		return $this->_userClassName;
	}

	public function setUserAlias($name) {
		$this->_userAlias = $name;
	}
	
	public function getUserAlias() {
		return $this->_userAlias;
	}
	
	public function getUserAliasMeta() {
		if (!empty($this->_userAlias)) {
			return "'widget." . $this->_userAlias . "'";
		}
		return 'null';
	}
	
	protected function _getPath() {
		return 'view';
	}
	
	public function getPath() {
		return $this->_getPath();
	}
	
	protected function _getPattern() {
		return 'view';
	}
	
	public function getPattern() {
		return $this->_getPattern();
	}
	
	protected function _mkdir($filename) {
		//echo dirname($filename) . "\n";
		@mkdir(dirname($filename), 0755, true);
	}
	
	protected function _saveMeta($appRoot) {
		$filename = str_replace('//', '/', str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getMetaFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName);
		$this->_mkdir($filename);

		$json = Zend_Json::encode($this->getMeta());
		$data = Zend_Json::prettyPrint($json);

		file_put_contents($filename, $data);
	}
	
	public function saveMeta($appRoot) {
		$this->_saveMeta($appRoot);
	}
	
	protected function _saveApp($appRoot) {
		switch ($this->_getPattern()) {
			case 'view' :
				return $this->_saveAppView($appRoot);
				break;
			case 'store' :
				return $this->_saveAppStore($appRoot);
				break;
			case 'controller' :
				return $this->_saveAppController($appRoot);
				break;
			case 'model' :
				return $this->_saveAppModel($appRoot);
				break;
			default :
				return $this->_saveAppDefault($appRoot);
		}
	}
	
	protected function _cleanUp($json) {
		foreach ($json as $key => $value) {
			if (is_array($value)) {
				$json[$key] = $this->_cleanUp($value);
				if (count($json[$key]) == 0) {
					unset($json[$key]);
				}
			} else {
				if ($value === null) {
					unset($json[$key]);
				}
			}
		}
		return $json;
	}
	
	protected function _setJavascript($json) {
		foreach ($json as $key => $value) {
			if (is_array($value)) {
				$json[$key] = $this->_setJavascript($value);
			} else {
				if (strpos($value, 'javascript:') === 0) {
					$value = str_replace('javascript:','',$value);
					$json[$key] = new Zend_Json_Expr($value);
				}
			}
		}
		return $json;
	}
	
	protected function _saveAppDefault($appRoot) {
		$filename = str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName . '.js';
		$this->_mkdir($filename);
		
		$json = Zend_Json::decode(Zend_Json::encode($this));
		$json = $this->_cleanUp($json);
		$json = Zend_Json::encode($json);
		$json = Zend_Json::prettyPrint($json);
		//$json = "Ext.Define('" . Ext_app_Application::getInstance()->getName() . "." . $this->_getPattern() . "." . $this->getUserClassName() . "," . $json . ",)";
$content = <<<EOT
Ext.define('%s.%s.%s', {
	extend: '%s',
	


	constructor: function(cfg) {
		var me = this;
		cfg = cfg || {};
		me.callParent([Ext.apply(%s, cfg)]);
	}
});		
EOT;
//requires: [%s],
		$data = sprintf($content,
				Ext_app_Application::getInstance()->getName(),
				$this->_getPattern(),
				$this->getUserClassName(),
				$this->getExtend(),
				//$this->getRequires(),
				$json
				);

		file_put_contents($filename, $data);		
	}

	protected function _saveAppModel($appRoot) {
		$filename = str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName . '.js';
		$this->_mkdir($filename);
	
		$this->extend = $this->getExtend();
		
		$json = Zend_Json::decode(Zend_Json::encode($this));
		$json = $this->_cleanUp($json);
		$json = Zend_Json::encode($json);
		$json = Zend_Json::prettyPrint($json);
		//$json = "Ext.Define('" . Ext_app_Application::getInstance()->getName() . "." . $this->_getPattern() . "." . $this->getUserClassName() . "," . $json . ",)";
		
		$content = <<<EOT
		Ext.define('%s.%s.%s', %s);
EOT;
		//requires: [%s],
		$data = sprintf($content,
				Ext_app_Application::getInstance()->getName(),
				$this->_getPattern(),
				$this->getUserClassName(),
				//$this->getExtend(),
				//$this->getRequires(),
				$json
				);
	
				file_put_contents($filename, $data);
	}	
	
	protected function _saveAppStore($appRoot) {
		$filename = str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName . '.js';
		$this->_mkdir($filename);
	
		$json = Zend_Json::decode(Zend_Json::encode($this));
		$json['model'] = $this->getModelApp();
		$json = $this->_cleanUp($json);
		$json = Zend_Json::encode($json);
		$json = Zend_Json::prettyPrint($json);
		//$json = "Ext.Define('" . Ext_app_Application::getInstance()->getName() . "." . $this->_getPattern() . "." . $this->getUserClassName() . "," . $json . ",)";
		$content = <<<EOT
		Ext.define('%s.%s.%s', {
		extend: '%s',
	
		requires: [%s],
	
		constructor: function(cfg) {
		var me = this;
		cfg = cfg || {};
		me.callParent([Ext.apply(%s, cfg)]);
	}
	});
EOT;
	
		$data = sprintf($content,
				Ext_app_Application::getInstance()->getName(),
				$this->_getPattern(),
				$this->getUserClassName(),
				$this->getExtend(),
				$this->getRequires(),
				$json
				);
	
		file_put_contents($filename, $data);
	}	

	protected function _saveAppView($appRoot) {
		$filename = str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName . '.js';
		$this->_mkdir($filename);
	
		$json = Zend_Json::decode(Zend_Json::encode($this));
		$json = $this->_cleanUp($json);
		$json = $this->_setJavascript($json);
		$json = Zend_Json::encode($json,
					    false,
					    array('enableJsonExprFinder' => true)
					);
		$json = Zend_Json::prettyPrint($json);
		//$json = "Ext.Define('" . Ext_app_Application::getInstance()->getName() . "." . $this->_getPattern() . "." . $this->getUserClassName() . "," . $json . ",)";
		$content = <<<EOT
Ext.define('%s.%s.%s', {
	extend: '%s',

	alias: %s,
	
	closable: %s,
	
    layout: {
        type: 'border'
    },

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, %s);
        
        me.callParent(arguments);
	}
	});
EOT;
//requires: [%s],
		$data = sprintf($content,
				Ext_app_Application::getInstance()->getName(),
				$this->_getPattern(),
				$this->getUserClassName(),
				$this->getExtend(),
				//$this->getRequires(),
				$this->getUserAliasMeta(),
				!empty($this->closable) ? "true" : "null",
				$json
				);
	
		file_put_contents($filename, $data);
	}	
	
	protected function _saveAppController($appRoot) {
		$filename = str_replace('\\', '/', $appRoot . '/' . Ext_app_Application::getInstance()->getAppFolder() . '/' . $this->_getPath()) . '/' . $this->_userClassName . '.js';
		$this->_mkdir($filename);
	
		$json = Zend_Json::decode(Zend_Json::encode($this));
		$json = $this->_cleanUp($json);
		$json = Zend_Json::encode($json);
		$json = Zend_Json::prettyPrint($json);
		//$json = "Ext.Define('" . Ext_app_Application::getInstance()->getName() . "." . $this->_getPattern() . "." . $this->getUserClassName() . "," . $json . ",)";
		
		$control = array();
		
		foreach ($this->listener as $key => $action) {
			$control[$action->controlQuery] = array(
					$action->name => new Zend_Json_Expr('this.' . $action->fn),
					);
		}
		
		$json = array(
				'extend' => $this->getExtend(),
				'refs' => $this->refs,				
				'init' => new Zend_Json_Expr('function(application) {this.control(' . Zend_Json::encode($control,false,array('enableJsonExprFinder' => true)) . ');}'),
				);
		
		foreach ($this->listener as $key => $action) {
			$json[$action->fn] = new Zend_Json_Expr('function(' . Ext_enums_TargetType::$enum[$action->targetType][$action->name] . ') {' . implode("\r", $action->implHandler) . '}');
			
			/*"fn":"onMenuitemClick",
			"implHandler":[
			"Ext.getCmp('MainTabPanel').add(Ext.widget(item.href.replace('#','')));\r"
					],
					"name":"click",
					"scope":"me",
					"targetType":"Ext.menu.Item",
					"controlQuery":"menuitem",
					"xtype":"controlleraction"*/
		}
		
		$content = <<<EOT
Ext.define('%s.%s.%s', %s);
EOT;
	
		$data = sprintf($content,
				Ext_app_Application::getInstance()->getName(),
				$this->_getPattern(),
				$this->getUserClassName(),
				Zend_Json::prettyPrint(
					Zend_Json::encode(
					    $json,
					    false,
					    array('enableJsonExprFinder' => true)
					))
		);
	
		file_put_contents($filename, $data);
	}	
	
	public function addRequires($component) {
		array_push($this->_requires, $component);
		return $component;
	}
	
	public function getRequires() {
		return implode(',', $this->_requires);
	}
	
	public function save($appRoot) {
		$this->_saveApp($appRoot);
		$this->_saveMeta($appRoot);
	}
	
}