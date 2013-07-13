<?php

class Rest_Cli {
	
	protected $_opt = null;
	protected $_application = null;
	protected $_db = null;
	
	public function __construct($application) {
		$this->_application = $application;
	}
	
	// ### OBJECT GETTER ### OBJECT GETTER ### OBJECT GETTER ### OBJECT GETTER ### OBJECT GETTER ### 
	
	protected function getApplication() {
		return $this->_application;
	}
	
	protected function _getDb() {
		if (empty($this->_db)) {
			$this->_db = Zend_Db_Table::getDefaultAdapter();
		}
		return $this->_db;
	} 
	
	protected function getOpt() {
		if (empty($this->_opt)) {
			$this->_opt = new Zend_Console_Getopt(
					array(
							'help|h' => 'Displays usage information.',
							'rest|r-s' => 'Create rest backends from database table as zend php files. If optional parameter is set then only for a specific table.',
							'prefix|p=s' => 'Creates only php files for tables with the prefix.',
							'store|s-s' => 'Create json stores and models for given table. Requires an extjs module app (-m).',
							'module|m=s' => 'Sets the specific extjs module app. Requires an extjs module app (-m).',
							'applytabs|t' => 'Apply tabs to combine forms and grids within a module. This allows GUI with tabpanels. Optional an extjs module app (-m); if none module given it will combine all forms per module.',
							'createapp|a' => 'Creates the extjs module app.',
							'createform|f-s' => 'Create an extjs form panel for the specified table. Requires an extjs module app (-m).',
							'creategrid|g-s' => 'Create an extjs grid panel for the specified table. Requires an extjs module app (-m).',
							'overwrite|o' => 'Overwrite existing files.',
							'verbose|v' => 'Verbose messages will be dumped to the default output.',
					)
			);			
		}
		return $this->_opt;
	}
	
	// ### CLI EXECUTION ### CLI EXECUTION ### CLI EXECUTION ### CLI EXECUTION ### CLI EXECUTION ### CLI EXECUTION ###
	
	protected function parse() {
		try {
			$this->getOpt()->parse();
		} catch (Zend_Console_Getopt_Exception $e) {
			exit($e->getMessage() ."\n\n". $e->getUsageMessage());
		}
	
		$this->help();
	
		if(isset($this->getOpt()->r)) {
			if ($this->getOpt()->r === true) {
				$this->_createRestBackend();
			} else {
				$this->_createRestBackendOnce($this->getOpt()->r);
			}
		}

		if (isset($this->getOpt()->s)) {
			if (empty($this->getOpt()->m)) {
				exit("Please specify your extjs module: -m <module_name>");
			}
			if ($this->getOpt()->s === true) {
				$this->_createJsonStore($this->getOpt()->m);
			} else {
				$this->_createJsonStoreOnce($this->getOpt()->s, $this->getOpt()->m);
			}
		}

		if (isset($this->getOpt()->f)) {
			if (empty($this->getOpt()->m)) {
				exit("Please specify your extjs module: -m <module_name>");
			}
			if ($this->getOpt()->f === true) {
				$this->_createFormPanel($this->getOpt()->m);
			} else {
				$this->_createFormPanelOnce($this->getOpt()->f, $this->getOpt()->m);
			}
		}
		
		if (isset($this->getOpt()->g)) {
			if (empty($this->getOpt()->m)) {
				exit("Please specify your extjs module: -m <module_name>");
			}
			if ($this->getOpt()->g === true) {
				$this->_createGridPanel($this->getOpt()->m);
			} else {
				$this->_createGridPanelOnce($this->getOpt()->g, $this->getOpt()->m);
			}
		}
		
		
		if (isset($this->getOpt()->t)) {
			if (empty($this->getOpt()->m)) {
				$this->_applyTabs();
			} else {
				$this->_applyTabsOnce($this->getOpt()->m);
			}
		}		
		
		if (isset($this->getOpt()->a)) {
			if (empty($this->getOpt()->m)) {
				exit("Please specify your extjs module: -m <module_name>");
			}
			$this->createExtjsApp($this->getOpt()->m);
		}		
		
		if (empty($this->getOpt()->m)) {
			$modules = $this->_getModules();
			foreach ($modules as $module) {
				$this->_setMVS($this->getOpt()->m, 'model');
				$this->_setMVS($this->getOpt()->m, 'view');
				$this->_setMVS($this->getOpt()->m, 'store');
				$this->_setMVS($this->getOpt()->m, 'controller');
			}
		} else {
			$this->_setMVS($this->getOpt()->m, 'model');
			$this->_setMVS($this->getOpt()->m, 'view');
			$this->_setMVS($this->getOpt()->m, 'store');
			$this->_setMVS($this->getOpt()->m, 'controller');
		}
	}	
	
	public function run() {
		$this->parse();
	}	
	
	protected function help() {
		if (isset($this->getOpt()->h)) {
			echo $this->getOpt()->getUsageMessage();
			exit;
		}
	}		
	
	// ### HELPER  ### HELPER  ### HELPER  ### HELPER  ### HELPER  ### HELPER  ### HELPER  ### HELPER  ### HELPER ###
	
	protected function _getModules() {
		$modules = array();
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {
			$module = explode('_', $table);
			if (count($module) == 1) {
				$modules['default'] = 'default';
			} else {
				$modules[$module[0]] = $module[0];
			}
		}
		return $modules;
	}
	
	protected function _createFiles($dir, $search, $replace) {
		foreach ($dir as $filename) {
			$search['%extjsgenid%'] = '%extjsgenid%';
			$replace['%extjsgenid%'] = intval(microtime(true)*1000);
			$dest = str_replace($search, $replace, $filename);
			if (!file_exists($dest) && is_dir($filename)) {
				mkdir($dest, 0777, true);
			} elseif ((!file_exists($dest) || isset($this->getOpt()->o)) && is_file($filename))  {
				$data = file_get_contents($filename);
				$data = str_replace($search, $replace, $data);
				file_put_contents($dest, $data);
			}
		}		
	}

	/**
	 * Get an array that represents directory tree
	 * @param string $directory     Directory path
	 * @param bool $recursive       Include sub directories
	 * @param bool $listDirs        Include directories on listing
	 * @param bool $listFiles       Include files on listing
	 * @param regex $exclude        Exclude paths that matches this regex
	 */
	private function _getDirArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
		$arrayItems = array();
		$skipByExclude = false;
		$handle = opendir($directory);
		if ($handle) {
			while (false !== ($file = readdir($handle))) {
				preg_match("/(^(([\.]){1,2})$|(\.(svn|git|md))|(Thumbs\.db|\.DS_STORE))$/iu", $file, $skip);
				if($exclude){
					preg_match($exclude, $file, $skipByExclude);
				}
				if (!$skip && !$skipByExclude) {
					if (is_dir($directory. DIRECTORY_SEPARATOR . $file)) {
						if($recursive) {
							$arrayItems = array_merge($arrayItems, $this->_getDirArray($directory. DIRECTORY_SEPARATOR . $file, $recursive, $listDirs, $listFiles, $exclude));
						}
						if($listDirs){
							$file = $directory . DIRECTORY_SEPARATOR . $file;
							$arrayItems[] = $file;
						}
					} else {
						if($listFiles){
							$file = $directory . DIRECTORY_SEPARATOR . $file;
							$arrayItems[] = $file;
						}
					}
				}
			}
			closedir($handle);
		}
		return $arrayItems;
	}
	
	private function getDir($directory) {
		//echo $directory; echo "\n";
		$dir = $this->_getDirArray($directory, true, true, true);
		$dir = array_reverse($dir);		
		return $dir;
	}
	
	protected function _setMVS($module, $type) {
		$this->_verbose("Update " . $type . "s in app.js for module " . $module . ".");
		$module = strtolower($module);
		if (file_exists(APPLICATION_PATH . '/../public/module/' . $module . '/metadata/' . $type)) {
			$pattern = "/" . $type . "s: \[([a-zA-Z0-9',\n\r\t\s]+)\]/";
			$files = $this->_getDirArray(APPLICATION_PATH . '/../public/module/' . $module . '/metadata/' . $type, false);
			$replacement = array();
			foreach ($files as $file) {
				$replacement[] = "'" . basename($file) . "'";
			}
			$replacement = $type . 's: [' . implode(',', $replacement) . ']';
			$subject = file_get_contents(APPLICATION_PATH . '/../public/module/' . $module . '/app.js');
			$string = preg_replace($pattern, $replacement, $subject);
			file_put_contents(APPLICATION_PATH . '/../public/module/' . $module . '/app.js', $string);
		} else {
			$this->_verbose("No " . $type . "s for module " . $module . ".");
		}
	}
	
	protected function _verbose($string) {
		if (isset($this->getOpt()->v)) {
			echo $string . "\n";
		}
	}
	
	// ### REST ### REST ### REST ### REST ### REST ### REST ### REST ### REST ### REST ### REST ### REST ### 
	
	protected function getReferenceMap($table) {
		$refmap_outer = <<<EOT
protected \$_referenceMap = array(
%s
    );
EOT;
		
		$refmap_inner = <<<EOT
        '%s' => array(
            'columns'          => array('%s'),
            'refTableClass'  => '%s',
            'refColumns'        => array('%s')
        )
EOT;
		
		$toTable = new Zend_Filter_Inflector(
				':tbl',
				array(':tbl' => array('Word_CamelCaseToUnderscore', 'StringToLower'))
		);
		
		
		
		$sql = "
		select
		tc.constraint_name,
		kcu.table_name,
		kcu.column_name,
		kcu.referenced_table_name,
		kcu.referenced_column_name
		from
		information_schema.table_constraints tc,
		information_schema.key_column_usage kcu
		where
		tc.table_name = " . $this->_getDb()->quote($table) . "
		and tc.constraint_type = 'FOREIGN KEY'
		and kcu.constraint_name = tc.constraint_name
		";
		$keys = $this->_getDb()->fetchAll($sql);
	
		$refs = array();
		if (!empty($keys)) {
			foreach ($keys as $key) {
				$class = explode('_', $key['referenced_table_name']);
				if (count($class) == 1) {
					$class = ucfirst($this->getApplication()->getBootstrap()->getAppnamespace()) . '_Model_DbTable_' . ucfirst($class[0]);
				} elseif (count($class) == 2) {
					$class = ucfirst($class[0]) . '_Model_DbTable_' . ucfirst($class[1]);
				}
				$refs[] = sprintf($refmap_inner,
						$key['referenced_table_name'],
						$key['column_name'],
						$class,
						$key['referenced_column_name']
				);
			}
		}
		return (!empty($refs) ? sprintf($refmap_outer, join(",\n", $refs)) : '');
	}
	
	protected function _createRestBackendOnce($table) {
		$type = explode('_', $table);
		if (count($type) == 1) {
			$search = array('%module%', '%modulelc%', '%controller%', '%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/default_controller/');
			$replace = array('Application', '', ucfirst($type[0]), strtolower($type[0]), APPLICATION_PATH . '/');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/default_controller/');
		} elseif (count($type) == 2) {
			$search = array('%module%','%modulelc%','%controller%','%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/module_controller/');
			$replace = array(ucfirst($type[0]), strtolower($type[0]), ucfirst($type[1]), strtolower($type[1]), APPLICATION_PATH . '/');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/module_controller/');
		} else {
			return;
		}
		
		$search[] = '%referencemap%';
		$replace[] = $this->getReferenceMap($table);
		
		$this->_createFiles($dir, $search, $replace);
	}
	
	protected function _createRestBackend() {
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {			
			if (empty($this->getOpt()->p)) {
				$this->_createRestBackendOnce($table);
			} else {
				if (strpos($table, $this->getOpt()->p) !== false) {
					$this->_createRestBackendOnce($table);
				}
			}
		}
	}
	
	// ### CREATE EXTJS APP ### CREATE EXTJS APP ### CREATE EXTJS APP ### CREATE EXTJS APP ### CREATE EXTJS APP ###
	
	protected function _getInitialView($extjsModule) {
		$dir = $this->_getDirArray(APPLICATION_PATH . '/../public/module/' . $extjsModule . '/metadata/view/' , false);
		$file = reset($dir);
		return basename($file);
	}
	
	protected function createExtjsApp($extjsModule) {
		$search = array('%extjsmodule%', '%extjsmodulelc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_app/');
		$replace = array(ucfirst($extjsModule), strtolower($extjsModule), APPLICATION_PATH . '/../');
		$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_app/', APPLICATION_PATH . '/../');
	
		$search[] = '%initialview%';
		$replace[] = $this->_getInitialView($extjsModule);

		$this->_createFiles($dir, $search, $replace);
	}

	// ### CREATE EXTJS JSON STORE + MODEL ### CREATE EXTJS JSON STORE + MODEL ### CREATE EXTJS JSON STORE + MODEL ### 
	
	protected function _getExtjsField($type) {
		$type = explode('(', $type);		
		$length = 0;
		if (!empty($type[1])) {
			$length = trim($type[1], ')');
		}
		$type = $type[0];
		
		switch($type) {
			case 'bit' :
			case 'bool' :
			case 'boolean' : 
				return 'boolean';
				break;
			case 'date' : 
			case 'datetime' :
			case 'time' :
			case 'timestamp' :
				return 'date';
				break;
			case 'decimal' :
			case 'double' :
			case 'float' :
				return 'float';
				break;
			case 'year' :
			case 'bigint' :
			case 'int' :
			case 'mediumint' :
			case 'smallint' :
				return 'int';
				break;
			case 'tinyint' :
				return intval($length) == 1 ? 'boolean' : 'int';
				break;
			case 'char' :
			case 'varchar' :
			case 'longtext' :
			case 'mediumtext' :
			case 'text' :
			case 'tinytext' :
			case 'enum' :
			case 'set' :
				return 'string';
				break;				
			default:
				return 'auto';
		}
	}
	
	protected function _createFieldMetadata($field) {		
		return Zend_Json::encode(array(
				'type' => 'datafield',
				'reference' => array(
						'name' => 'fields',
						'type' => 'array', 
						),
				'codeClass' => null,
				'userConfig' => array(
						'designer|userClassName' => Zend_Filter::filterStatic($field['TABLE_NAME'] . '_' . $field['COLUMN_NAME'], 'Word_UnderscoreToCamelCase') . 'Field',
						'name' => $field['COLUMN_NAME'],
						'type' => $this->_getExtjsField($field['DATA_TYPE']),
						'useNull' => $field['NULLABLE'] == 1 ? true : false,
						'defaultValue' => $field['DEFAULT'],
						),
		));
	}
	
	protected function _createFieldsMetadata($table) {
		$table = new Zend_Db_Table(array('name' => $table));
		$info = $table->info();
		//file_put_contents(APPLICATION_PATH . '/../fields.txt', print_r($info, true));
		$fields = array();
		foreach ($info['metadata'] as $field) {
			$fields[] = $this->_createFieldMetadata($field);
		}
		return implode(',', $fields);
	}
		
	protected function _createFieldApp($field) {
		return array(
				'name' => $field['COLUMN_NAME'],
				'type' => $this->_getExtjsField($field['DATA_TYPE']),
				'useNull' => $field['NULLABLE'] == 1 ? true : false,
				'defaultValue' => $field['DEFAULT'],
		);
	}
	
	protected function _createFieldsApp($table) {
		$table = new Zend_Db_Table(array('name' => $table));
		$info = $table->info();
		
		//file_put_contents(APPLICATION_PATH . '/../fields.txt', print_r($info, true));
		$fields = array();
		foreach ($info['metadata'] as $field) {
			$fields[] = $this->_createFieldApp($field);
		}
		return 'fields:' . Zend_Json::encode($fields); 
	}	
	
	protected function _createJsonStoreOnce($table, $extjsModule) {
		$type = explode('_', $table);
		if (count($type) == 1) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%', '%modulelc%', '%controller%', '%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_store/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), 'Application', '', ucfirst($type[0]), strtolower($type[0]), APPLICATION_PATH . '/../');			
		} elseif (count($type) == 2) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%','%modulelc%','%controller%','%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_store/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), ucfirst($type[0]), strtolower($type[0]), ucfirst($type[1]), strtolower($type[1]), APPLICATION_PATH . '/../');
		} else {
			return;
		}
		
		$search[] = '%extjsfieldsmetadata%';
		$replace[] = $this->_createFieldsMetadata($table);

		$search[] = '%extjsfieldsapp%';
		$replace[] = $this->_createFieldsApp($table);
		
		$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_store/');
		$this->_createFiles($dir, $search, $replace);
	}
	
	protected function _createJsonStore($extjsModule) {
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {
			if (empty($this->getOpt()->p)) {
				$this->_createJsonStoreOnce($table, $extjsModule);
			} else {
				if (strpos($table, $this->getOpt()->p) !== false) {
					$this->_createJsonStoreOnce($table, $extjsModule);
				}
			}
		}
	}
	
	// ### CREATE EXTJS FORM PANEL ### CREATE EXTJS FORM PANEL ### CREATE EXTJS FORM PANEL ### CREATE EXTJS FORM PANEL ###
	
	protected function _getAvailableItems($field) {
		$ret = array();
		$items = explode('(', $field['DATA_TYPE']);
		$items = explode(',', trim($items[1], ')'));
		foreach($items as $item)
		{
			$item = trim($item, '\'');
			$ret[] = array(
				'xtype' => 'checkboxfield',
				'inputValue' => $item,
				'boxLabel' => $item,
				);
		}
		return $ret;
	}
	
	protected function _getAvailableItemsMetadata($field) {
		$ret = array();
		$items = explode('(', $field['DATA_TYPE']);
		$items = explode(',', trim($items[1], ')'));
		foreach($items as $item)
		{
			$item = trim($item, '\'');
			$ret[] = array(
					'type' => 'checkboxfield',					
					'reference' => array(
							'name' => 'items',
							'type' => 'array',
					),
					'codeClass' => null,
					'userConfig' => array(
							'designer|userClassName' => ucfirst($item) . 'Checkbox',
							'fieldLabel' => null,
							'boxLabel' => $item,
							'inputValue' => $item,
					),
			);
		}
		return $ret;
	}
	
	protected function _getClass($table) {
		$class = explode('_', $table);
		if (count($class) == 1) {
			$class = ucfirst($this->getApplication()->getBootstrap()->getAppnamespace()) . '_Model_DbTable_' . ucfirst($class[0]);
		} elseif (count($class) == 2) {
			$class = ucfirst($class[0]) . '_Model_DbTable_' . ucfirst($class[1]);
		}
		return $class;
	}
	
	protected function _getExtjsPrefix($class) {
		$class = explode('_', $class);
		if (count($class) == 1) {
			$class = ucfirst($this->getApplication()->getBootstrap()->getAppnamespace()) . ucfirst($class[0]);
		} else {
			$class = ucfirst(reset($class)) . ucfirst(end($class));
		}		
		return $class;
	}
	
	protected function _getReference($field) {
		$class = $this->_getClass($field['TABLE_NAME']);
		$table = new $class();
		$refs = $table->info(Zend_Db_Table_Abstract::REFERENCE_MAP);
		if (is_array($refs)) {
			foreach ($refs as $table => $ref) {
				if (in_array($field['COLUMN_NAME'], $ref['columns'])) {
					return $ref;
				}
			}
		}
		return false;
	}
	
	protected function _getNextFieldName($class, $columns) {
		$ret = reset($columns);
		$table = new $class();
		$info = $table->info(Zend_Db_Table_Abstract::METADATA);
		$get = false;
		foreach ($info as $field) {
			if ($get) {
				$type = explode('(', $field['DATA_TYPE']);
				$type = reset($type);
				switch ($type) {
					case 'varchar' :
					case 'char' :
					case 'tinytext' :
						return $field['COLUMN_NAME'];
					break;
				}
			}
			if ($field['COLUMN_NAME'] == $ret) {
				$get = true;
			}
		}
	}
		
	protected function _getExtjsFormFieldMetadata($field) {
		$type = explode('(', $field['DATA_TYPE']);
		$length = 0;
		if (!empty($type[1])) {
			$length = trim($type[1], ')');
		}
		$type = $type[0];
	
		$ret = array(
				'reference' => array(
						'name' => 'items',
						'type' => 'array',
						),
				'codeClass' => null,
				'userConfig' => array(
						'layout|anchor' => '100%',
						'fieldLabel' => ucfirst($field['COLUMN_NAME']),
						'name' => $field['COLUMN_NAME'],						
						)
		);
		
		if ($field['IDENTITY']) {
			$ret['userConfig']['readOnly'] = true;
		}

		switch($type) {
			case 'bit' :
			case 'bool' :
			case 'boolean' :
				$ret['type'] = 'checkboxfield';
				$ret['boxLabel'] = ucfirst($field['COLUMN_NAME']);
				break;
			case 'enum' :
				$ret['type'] = 'radiogroup';
				$ret['cn'] = $this->_getAvailableItemsMetadata($field);
				break;
			case 'set' :
				$ret['type'] = 'checkboxgroup';
				$ret['cn'] = $this->_getAvailableItemsMetadata($field);
				break;
			case 'date' :
			case 'datetime' :
			case 'timestamp' :
				$ret['type'] = 'datefield';
				break;
			case 'time' :
				$ret['type'] = 'timefield';
				break;
			case 'bigint' :
			case 'int' :
			case 'mediumint' :
			case 'smallint' :
				if (($ref = $this->_getReference($field)) === false) {
					$ret['type'] = 'numberfield';
				} else {
					$ret['type'] = 'combobox';
					$ret['userConfig']['displayField'] = $this->_getNextFieldName($ref['refTableClass'], $ref['refColumns']);
					$ret['userConfig']['store'] = $this->_getExtjsPrefix($ref['refTableClass']) . 'JsonStore';
					$ret['userConfig']['valueField'] = reset($ref['refColumns']);
				}
				break;
			case 'year' :
			case 'decimal' :
			case 'double' :
			case 'float' :
				$ret['type'] = 'numberfield';
				break;
			case 'tinyint' :
				if (intval($length) == 1) {
					$ret['type'] = 'checkboxfield';
					$ret['boxLabel'] = ucfirst($field['COLUMN_NAME']);
				} else {
					$ret['type'] = 'numberfield';
				}
				break;
			case 'binary':
			case 'varbinary':
			case 'blob':
			case 'tinyblob':
			case 'mediumblob':
			case 'longblob':
				$ret['type'] = 'filefield';
				break;				
			case 'char' :
			case 'varchar' :
				$ret['type'] = 'textfield';
				break;
			case 'longtext' :
			case 'mediumtext' :
			case 'text' :
			case 'tinytext' :
				$ret['type'] = 'textareafield';
				break;
			default:
				$ret['type'] = 'textfield';
				break;
		}
		$ret['userConfig']['designer|userClassName'] = ucfirst($field['COLUMN_NAME']) . ucfirst($ret['type']);

		if ($field['IDENTITY']) {
			$ret['type'] = 'hiddenfield';
		}
		
		return $ret;
	}
	
	
	protected function _getExtjsFormFieldApp($field) {
		$type = explode('(', $field['DATA_TYPE']);
		$length = 0;
		if (!empty($type[1])) {
			$length = trim($type[1], ')');
		}
		$type = $type[0];

		$ret = array(
                'anchor' => '100%',
                'fieldLabel' => ucfirst($field['COLUMN_NAME']),
				'name' => $field['COLUMN_NAME'],
		);
		
		if ($field['IDENTITY']) {
			$ret['readOnly'] = true;
		}
		
		switch($type) {
			case 'bit' :
			case 'bool' :
			case 'boolean' :
				$ret['xtype'] = 'checkboxfield';
				$ret['boxLabel'] = ucfirst($field['COLUMN_NAME']);
				break;
			case 'enum' :
				$ret['xtype'] = 'radiogroup';
				$ret['items'] = $this->_getAvailableItems($field);
				break;
			case 'set' :
				$ret['xtype'] = 'checkboxgroup';
				$ret['items'] = $this->_getAvailableItems($field);
				break;				
			case 'date' :
			case 'datetime' :
			case 'timestamp' :
				$ret['xtype'] = 'datefield';
				break;
			case 'time' :			
				$ret['xtype'] = 'timefield';
				break;
			case 'bigint' :
			case 'int' :
			case 'mediumint' :
			case 'smallint' :
				if (($ref = $this->_getReference($field)) === false) {
					$ret['xtype'] = 'numberfield';
				} else {
					$ret['xtype'] = 'combobox';
					$ret['displayField'] = $this->_getNextFieldName($ref['refTableClass'], $ref['refColumns']);
					$ret['store'] = $this->_getExtjsPrefix($ref['refTableClass']) . 'JsonStore';
					$ret['valueField'] = reset($ref['refColumns']);
				}
				break;
			case 'year' :
			case 'decimal' :
			case 'double' :
			case 'float' :
				$ret['xtype'] = 'numberfield';
				break;
			case 'binary':
			case 'varbinary':
			case 'blob':
			case 'tinyblob':
			case 'mediumblob':
			case 'longblob':
				$ret['xtype'] = 'filefield';
				break;
			case 'tinyint' :
				if (intval($length) == 1) {
					$ret['xtype'] = 'checkboxfield';
					$ret['boxLabel'] = ucfirst($field['COLUMN_NAME']);
				} else {
					$ret['xtype'] = 'numberfield';
				}
				break;
			case 'char' :
			case 'varchar' :
				$ret['xtype'] = 'textfield';
				break;
			case 'longtext' :
			case 'mediumtext' :
			case 'text' :
			case 'tinytext' :
				$ret['xtype'] = 'textareafield';
				break;
			default:
				$ret['xtype'] = 'textfield';
				break;
		}
		
		if ($field['IDENTITY']) {
			$ret['xtype'] = 'hiddenfield';
		}

		return $ret;
	}
		
	/*protected function _createFormFieldMetadata($field) {
		return Zend_Json::encode(array(
				'type' => 'datafield',
				'reference' => array(
						'name' => 'fields',
						'type' => 'array',
				),
				'codeClass' => null,
				'userConfig' => array(
						'designer|userClassName' => Zend_Filter::filterStatic($field['TABLE_NAME'] . '_' . $field['COLUMN_NAME'], 'Word_UnderscoreToCamelCase') . 'Field',
						'name' => $field['COLUMN_NAME'],
						'type' => $this->_getExtjsFieldMetadata($field),
						'useNull' => $field['NULLABLE'] == 1 ? true : false,
						'defaultValue' => $field['DEFAULT'],
				),
		));
	}*/
	
	protected function _createFormFieldsMetadata($table) {
		$class = $this->_getClass($table);
		$table = new $class();
		$info = $table->info(Zend_Db_Table_Abstract::METADATA);
		$fields = array();
		foreach ($info as $field) {
			$fields[] = Zend_Json::encode($this->_getExtjsFormFieldMetadata($field));
		}
		return implode(',', $fields);
	}
	
	/*protected function _createFormFieldApp($field) {
		return array(
				'name' => $field['COLUMN_NAME'],
				'type' => $this->_getExtjsFieldApp($field),
				'useNull' => $field['NULLABLE'] == 1 ? true : false,
				'defaultValue' => $field['DEFAULT'],
		);
	}*/
	
	protected function _createFormFieldsApp($table) {
		$table = new Zend_Db_Table(array('name' => $table));
		$info = $table->info();
		$fields = array();
		foreach ($info['metadata'] as $field) {
			$fields[] = $this->_getExtjsFormFieldApp($field);
		}
		return 'items:' . Zend_Json::encode($fields);
	}

	protected function _createFormPanelOnce($table, $extjsModule) {
		$type = explode('_', $table);
		if (count($type) == 1) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%', '%modulelc%', '%controller%', '%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_formpanel/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), 'Application', '', ucfirst($type[0]), strtolower($type[0]), APPLICATION_PATH . '/../');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_formpanel/');
		} elseif (count($type) == 2) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%','%modulelc%','%controller%','%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_formpanel/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), ucfirst($type[0]), strtolower($type[0]), ucfirst($type[1]), strtolower($type[1]), APPLICATION_PATH . '/../');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_formpanel/');
		} else {
			return;
		}

		$search[] = '%extjsformfieldsmetadata%';
		$replace[] = $this->_createFormFieldsMetadata($table);
	
		$search[] = '%extjsformfieldsapp%';
		$replace[] = $this->_createFormFieldsApp($table);
	
		$this->_createFiles($dir, $search, $replace);
	}
	
	protected function _createFormPanel($extjsModule) {
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {			
			if (empty($this->getOpt()->p)) {
				$this->_createFormPanelOnce($table, $extjsModule);
			} else {
				if (strpos($table, $this->getOpt()->p) !== false) {
					$this->_createFormPanelOnce($table, $extjsModule);
				}
			}
		}
	}
	
	// ### CREATE EXTJS GRID PANEL ### CREATE EXTJS GRID PANEL ### CREATE EXTJS GRID PANEL ### CREATE EXTJS GRID PANEL ###

	protected function _getExtjsColumnMetadata($field) {
		$type = explode('(', $field['DATA_TYPE']);
		$length = 0;
		if (!empty($type[1])) {
			$length = trim($type[1], ')');
		}
		$type = $type[0];
	
		$ret = array(
				'reference' => array(
						'name' => 'columns',
						'type' => 'array',
				),
				'codeClass' => null,
				'userConfig' => array(
						'dataIndex' => $field['COLUMN_NAME'],
						'text' => ucfirst($field['COLUMN_NAME']),
						
				)
		);
	
		switch($type) {
			case 'bit' :
			case 'bool' :
			case 'boolean' :
				$ret['type'] = 'booleancolumn';
				$ret['boxLabel'] = ucfirst($field['COLUMN_NAME']);
				break;
			case 'enum' :
				$ret['type'] = 'gridcolumn';
				//$ret['cn'] = $this->_getAvailableItemsMetadata($field);
				break;
			case 'set' :
				$ret['type'] = 'gridcolumn';
				//$ret['cn'] = $this->_getAvailableItemsMetadata($field);
				break;
			case 'date' :
			case 'datetime' :
			case 'timestamp' :
				$ret['type'] = 'datecolumn';
				break;
			case 'time' :
				$ret['type'] = 'datecolumn';
				break;
			case 'bigint' :
			case 'int' :
			case 'mediumint' :
			case 'smallint' :
				$ret['type'] = 'numbercolumn';
				/*if (($ref = $this->_getReference($field)) === false) {
					
				} else {
					$ret['type'] = 'combobox';
					$ret['userConfig']['displayField'] = $this->_getNextFieldName($ref['refTableClass'], $ref['refColumns']);
					$ret['userConfig']['store'] = $this->_getExtjsPrefix($ref['refTableClass']) . 'JsonStore';
					$ret['userConfig']['valueField'] = reset($ref['refColumns']);
				}*/
				break;
			case 'year' :
			case 'decimal' :
			case 'double' :
			case 'float' :
				$ret['type'] = 'numbercolumn';
				break;
			case 'tinyint' :
				if (intval($length) == 1) {
					$ret['type'] = 'booleancolumn';
				} else {
					$ret['type'] = 'numbercolumn';
				}
				break;
			case 'binary':
			case 'varbinary':
			case 'blob':
			case 'tinyblob':
			case 'mediumblob':
			case 'longblob':
				$ret['type'] = 'gridcolumn';
				break;
			case 'char' :
			case 'varchar' :
				$ret['type'] = 'gridcolumn';
				break;
			case 'longtext' :
			case 'mediumtext' :
			case 'text' :
			case 'tinytext' :
				$ret['type'] = 'gridcolumn';
				break;
			default:
				$ret['type'] = 'gridcolumn';
			break;
		}
		$ret['userConfig']['designer|userClassName'] = ucfirst($field['COLUMN_NAME']) . ucfirst($ret['type']);
	
		if ($field['IDENTITY']) {
			$ret['type'] = 'numbercolumn';
		}
	
		return $ret;
	}	
	
	protected function _createColumnsMetadata($table) {
		$class = $this->_getClass($table);
		$table = new $class();
		$info = $table->info(Zend_Db_Table_Abstract::METADATA);
		$fields = array();
		foreach ($info as $field) {
			$fields[] = Zend_Json::encode($this->_getExtjsColumnMetadata($field));
		}
		return implode(',', $fields);
	}
	
	protected function _getExtjsColumnApp($field) {
		$type = explode('(', $field['DATA_TYPE']);
		$length = 0;
		if (!empty($type[1])) {
			$length = trim($type[1], ')');
		}
		$type = $type[0];
	
		$ret = array(
				'text' => ucfirst($field['COLUMN_NAME']),
				'dataIndex' => $field['COLUMN_NAME'],
		);
	
		switch($type) {
			case 'bit' :
			case 'bool' :
			case 'boolean' :
				$ret['xtype'] = 'booleancolumn';
				break;
			case 'enum' :
				$ret['xtype'] = 'gridcolumn';
				//$ret['items'] = $this->_getAvailableItems($field);
				break;
			case 'set' :
				$ret['xtype'] = 'gridcolumn';
				//$ret['items'] = $this->_getAvailableItems($field);
				break;
			case 'date' :
			case 'datetime' :
			case 'timestamp' :
				$ret['xtype'] = 'datecolumn';
				break;
			case 'time' :
				$ret['xtype'] = 'gridcolumn';
				break;
			case 'bigint' :
			case 'int' :
			case 'mediumint' :
			case 'smallint' :
				$ret['xtype'] = 'numbercolumn';
				break;
			case 'year' :
			case 'decimal' :
			case 'double' :
			case 'float' :
				$ret['xtype'] = 'numbercolumn';
				break;
			case 'binary':
			case 'varbinary':
			case 'blob':
			case 'tinyblob':
			case 'mediumblob':
			case 'longblob':
				$ret['xtype'] = 'gridcolumn';
				break;
			case 'tinyint' :
				if (intval($length) == 1) {
					$ret['xtype'] = 'booleancolumn';
				} else {
					$ret['xtype'] = 'numbercolumn';
				}
				break;
			case 'char' :
			case 'varchar' :
				$ret['xtype'] = 'gridcolumn';
				break;
			case 'longtext' :
			case 'mediumtext' :
			case 'text' :
			case 'tinytext' :
				$ret['xtype'] = 'gridcolumn';
				break;
			default:
				$ret['xtype'] = 'gridcolumn';
			break;
		}
	
		if ($field['IDENTITY']) {
			$ret['xtype'] = 'numbercolumn';
		}
	
		return $ret;
	}	
	
	protected function _createColumnsApp($table) {
		$table = new Zend_Db_Table(array('name' => $table));
		$info = $table->info();
		$fields = array();
		foreach ($info['metadata'] as $field) {
			$fields[] = $this->_getExtjsColumnApp($field);
		}
		return 'columns:' . Zend_Json::encode($fields);
	}
		
	protected function _createGridPanelOnce($table, $extjsModule) {
		$type = explode('_', $table);
		if (count($type) == 1) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%', '%modulelc%', '%controller%', '%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_gridpanel/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), 'Application', '', ucfirst($type[0]), strtolower($type[0]), APPLICATION_PATH . '/../');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_gridpanel/');
		} elseif (count($type) == 2) {
			$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%','%modulelc%','%controller%','%controllerlc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_gridpanel/');
			$replace = array(ucfirst($extjsModule), strtolower($extjsModule), ucfirst($type[0]), strtolower($type[0]), ucfirst($type[1]), strtolower($type[1]), APPLICATION_PATH . '/../');
			$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_gridpanel/');
		} else {
			return;
		}

		$search[] = '%extjscolumnsmetadata%';
		$replace[] = $this->_createColumnsMetadata($table);
	
		$search[] = '%extjscolumnsapp%';
		$replace[] = $this->_createColumnsApp($table);
	
		$this->_createFiles($dir, $search, $replace);
	}
	
	protected function _createGridPanel($extjsModule) {
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {
			if (empty($this->getOpt()->p)) {
				$this->_createGridPanelOnce($table, $extjsModule);
			} else {
				if (strpos($table, $this->getOpt()->p) !== false) {
					$this->_createGridPanelOnce($table, $extjsModule);
				}
			}
		}
	}	
	
	// ### COMBINE FORMS TO TABBING ### COMBINE FORMS TO TABBING ### COMBINE FORMS TO TABBING ### COMBINE FORMS TO TABBING ### COMBINE FORMS TO TABBING ###

	protected function _getCodeMetadata($item, $type, array $attribs = array()) {
		$code = null;
		$filename = $item; //str_replace('/metadata/', '/app/', $item) . '.js';
		$content = file_get_contents($filename);
		
		if (count($attribs) == 0) return $content;
		$add = array();
		foreach ($attribs as $attrib => $value) {
			$add[] = '"' . $attrib . '": ' . (is_string($value) ? '"' . $value . '"' : (is_bool($value) ? ($value == true ? 'true' : 'false') : $value));
		}
		
		if (preg_match("/\"userConfig\": \{([a-zA-Z0-9 :;()%\"',.{}[\]\n\r\t\s\w\W\S\D]+?)\}\,/", $content, $matches) > 0) {
			
			$replace = '"userConfig": {' . trim($matches[1], ',') . ',' . implode(',', $add) . '},';
			
			$content = preg_replace("/\"userConfig\": \{([a-zA-Z0-9 :;()%\"',.{}[\]\n\r\t\s\w\W\S\D]+?)\}\,/", $replace, $content, 1);
		}
		return $content;
		/*if (preg_match("/applyIf\(me, ([a-zA-Z0-9 :;()%\"',.{}[\]\n\r\t\s\w\W\S\D]+)me.callParent/", $content, $matches) > 0) {
			if (!empty($matches[1])) {
				$code = $matches[1];
				$code = trim($code);
				// remove the least two chars to get valid json
				$code = trim($code, ';');
				$code = trim($code, ')');
				$code = trim($code, '{');
				$code = trim($code, '}');
				$add  = '';
				$add .= '"xtype": "' . strtolower($type) . '",';
				$add .= '"title": "' . $this->_getTitleFromCode($content) .'",';
				$add .= '"bodyPadding": ' . $this->_getBodyPaddingFromCode($content) .',';
	
				foreach ($attribs as $attrib => $value) {
					$add .= '"' . $attrib . '": ' . (is_string($value) ? '"' . $value . '"' : (is_bool($value) ? ($value == true ? 'true' : 'false') : $value)) . ',';
				}
	
				$code = '{' . $add . $code . '}';
			}
		}
		return $code;*/
	}	
	
	protected function _applyTabMetadata($module, $elements) {
		$ret = array();
		
		foreach ($elements as $element => $types) {
			if (count($types) == 1) {
				// einfach ins tab einfügen
				list($type, $item) = each($types);
				$ret[] = $this->_getCodeMetadata($item, $type);
			} elseif (count($types) > 1) {
				// panel erstellen
				$ret2 = array();
				foreach ($types as $type => $item) {
					switch ($type) {
						case 'Form' :
							$ret2[] = $this->_getCodeMetadata($item, $type, array(
									'layout|region' => 'center',
									'header' => false,
							));
							break;
						case 'GridPanel' :
							$ret2[] = $this->_getCodeMetadata($item, $type, array(
									'layout|region' => 'west',
									'layout|split' => true,
									'header' => false,
							));
							break;
						default :
							$ret2[] = $this->_getCodeMetadata($item, $type, array(
									'layout|region' => 'south',
									'layout|split' => true,
							));
						break;
					}
				}
				$ret2 = implode(',', $ret2);
				$name = str_replace(array(ucfirst($module),$type), array('',''), basename($item));
				$ret[] = '{
					"type": "panel",
					"reference": {
						"name": "items",
						"type": "array"
					},
					"codeClass": null,
					"userConfig": {
						"designer|userClassName": "' . ucfirst($module) . 'Panel",
						"height": 250,
						"width": 400,
						"layout": "border",
						"header": false,
						"title": "' . $name . '"
					},
					"cn": [' . $ret2 . ']
				}';
			}
		}
		return $ret = implode(',', $ret);
	}
	
	protected function _getTitleFromCode($content) {
		$ret = '';
		if (preg_match("/title: '([\s\w]+?)',/", $content, $matches) > 0) {
			$ret = $matches[1];
		}
		return $ret;
	}

	protected function _getIdFromCode($content) {
		$ret = '';
		if (preg_match("/id: '([\s\w]+?)',/", $content, $matches) > 0) {
			$ret = $matches[1];
		}
		return $ret;
	}
	
	protected function _getItemIdFromCode($content) {
		$ret = '';
		if (preg_match("/itemId: '([\s\w]+?)',/", $content, $matches) > 0) {
			$ret = $matches[1];
		}
		return $ret;
	}
	
	protected function _getStoreFromCode($content) {
		$ret = '';
		if (preg_match("/store: '([\s\w]+?)',/", $content, $matches) > 0) {
			$ret = $matches[1];
		}
		return $ret;
	}	

	protected function _getBodyPaddingFromCode($content) {
		$ret = 0;
		if (preg_match("/bodyPadding: ([\s\w]+?),/", $content, $matches) > 0) {
			$ret = $matches[1];
		}
		return $ret;		
	}
	
	protected function _getCodeApp($item, $type, array $attribs = array()) {
		$code = null;
		$filename = str_replace('/metadata/', '/app/', $item) . '.js';
		$content = file_get_contents($filename);
		if (preg_match("/applyIf\(me, ([a-zA-Z0-9 :;()%\"',.{}[\]\n\r\t\s\w\W\S\D]+)me.callParent/", $content, $matches) > 0) {			
			if (!empty($matches[1])) {
				$code = $matches[1];
				$code = trim($code);
				// remove the least two chars to get valid json
				$code = trim($code, ';');
				$code = trim($code, ')');
				$code = trim($code, '{');
				$code = trim($code, '}');
				$add  = '';				
				$add .= '"xtype": "' . strtolower($type) . '",';
				$add .= '"title": "' . $this->_getTitleFromCode($content) .'",';
				if ($type == 'GridPanel') {
					$add .= '"store": "' . $this->_getStoreFromCode($content) .'",';
				}
				$add .= '"bodyPadding": ' . $this->_getBodyPaddingFromCode($content) .',';
				$add .= '"id": "' . $this->_getIdFromCode($content) .'",';
				$add .= '"itemId": "' . $this->_getItemIdFromCode($content) .'",';
				foreach ($attribs as $attrib => $value) {
						$add .= '"' . $attrib . '": ' . (is_string($value) ? '"' . $value . '"' : (is_bool($value) ? ($value == true ? 'true' : 'false') : $value)) . ',';
				}
				
				$code = '{' . $add . $code . '}';
			}
		}
		return $code;
	}
	
	protected function _applyTabApp($module, $elements) {
		$ret = array();
		
		foreach ($elements as $element => $types) {
			if (count($types) == 1) {
				// einfach ins tab einfügen				
				list($type, $item) = each($types);				
				$ret[] = $this->_getCodeApp($item, $type);								
			} elseif (count($types) > 1) {
				// panel erstellen
				$ret2 = array();
				foreach ($types as $type => $item) {
					switch ($type) {
						case 'Form' :
							$ret2[] = $this->_getCodeApp($item, $type, array(
									'region' => 'center',
									'split' => false,
									'header' => false,
							));
							break;
						case 'GridPanel' :
							$ret2[] = $this->_getCodeApp($item, $type, array(
									'region' => 'west',
									'split' => true,
									'header' => false,
							));							
							break;
						default :
							$ret2[] = $this->_getCodeApp($item, $type, array(
									'region' => 'south',
									'split' => true,
							));						
							break;
					}
				}
				$ret2 = implode(',', $ret2);
				$name = str_replace(array(ucfirst($module),$type), array('',''), basename($item));
				$ret[] = "{
                    xtype: 'panel',
                    height: 250,
                    width: 400,
                    layout: {
                        type: 'border'
                    },
                    header: false,
                    title: '" . $name . "',
                    items: [" . $ret2 . "]}";
			}
		}
		return $ret = implode(',', $ret);	
	}
	
	protected function _applyTabs() {
		$modules = $this->_getModules();
		foreach($modules as $module) {
			$this->_applyTabsOnce($module);
		}
	}
	
	protected function _applyTabsOnce($module, array $suffix = array('Form','GridPanel')) {
		$items = $this->_getDirArray(APPLICATION_PATH . '/../public/module/' . $module . '/metadata/view', false);
		$elements = array();
		foreach ($items as $item) {
			foreach ($suffix as $type) {
				if (preg_match("/" . $type . "$/", $item) > 0) {
					$element = preg_replace("/" . $type . "$/", '', $item);
					$elements[$element][$type] = $item;
				}
			}
		}
		
		$search = array('%extjsmodule%', '%extjsmodulelc%', '%module%', '%modulelc%', APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_tabpanel/');
		$replace = array(ucfirst($module), strtolower($module), ucfirst($module), strtolower($module), APPLICATION_PATH . '/../');
		$dir = $this->getDir(APPLICATION_PATH . '/../library/Rest/Cli/Template/extjs_tabpanel/');
		
		$search[] = '%tabpanelmetadataitems%';
		$replace[] = $this->_applyTabMetadata($module, $elements);
		
		$search[] = '%tabpanelappitems%';
		$replace[] = $this->_applyTabApp($module, $elements);
		
		$this->_createFiles($dir, $search, $replace);

		foreach ($elements as $element) {
			foreach ($element as $item) {
				unlink($item);
				$itemapp = str_replace('/metadata/', '/app/', $item) . '.js';
				unlink($itemapp);
			}
		}
	}
	
}