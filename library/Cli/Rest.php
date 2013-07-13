<?php 

class Cli_Rest extends Cli_Abstract {
	
	protected function _parse() {
		if (Cli_Opt::getInstance()->r === true) {
			$this->_createRestBackend();
		}
	}
	
	protected function getDependentTables($table) {
		$depTables = <<<EOT
	protected \$_dependentTables = array(%s);
EOT;
		$tables = array();
		$sql = "select * from information_schema.key_column_usage where referenced_table_name = '" . $table . "' and table_schema = '" . Cli_Opt::getInstance()->e . "'";
		$rows = $this->_getDb()->fetchAll($sql);
		if (!empty($rows)) {
			foreach ($rows as $row) {
				$tables[$row['TABLE_NAME']] = "'" . $row['TABLE_NAME'] . "'";
			}
		}
		return (!empty($tables) ? sprintf($depTables, join(", ", $tables)) : '');
	}
	
	protected function getReferenceMap($table) {
		$refmap_outer = <<<EOT
	protected \$_referenceMap = array(
%s
	);
EOT;
	
		$refmap_inner = <<<EOT
		'%s' => array(
			'columns'		=> array('%s'),
			'refTableClass'	=> '%s',
			'refColumns'	=> array('%s')
		)
EOT;

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
				and tc.table_schema = '" . Cli_Opt::getInstance()->e . "'
				and tc.constraint_type = 'FOREIGN KEY'
			 	and kcu.table_schema = tc.table_schema
				and kcu.constraint_name = tc.constraint_name
				";
		$keys = $this->_getDb()->fetchAll($sql);
		$refs = array();
		if (!empty($keys)) {
			foreach ($keys as $key) {
				$module = $this->_getModule($key['referenced_table_name']);
				$controller = $this->_getController($key['referenced_table_name']);
				if ($module == 'default') {
					$class = ucfirst($this->_cli->getApplication()->getBootstrap()->getAppnamespace()) . '_Model_DbTable_' . $controller;
				} else {
					$class = ucfirst($module) . '_Model_DbTable_' . $controller;
				}
				$refs[$key['constraint_name']] = sprintf($refmap_inner,
						$key['constraint_name'],
						$key['column_name'],
						$class,
						$key['referenced_column_name']
						);
			}
		}
		
		return (!empty($refs) ? sprintf($refmap_outer, join(",\n", $refs)) : '');
	}
	
	protected function _createRestBackendOnce($table) {
		Cli_Opt::getInstance()->verbose('Create REST backend for ' . $table);
		$module = $this->_getModule($table);
		$controller = $this->_getController($table);
		if ($module == 'default') {
			$search = array('%table%', '%module%', '%modulelc%', '%controller%', '%controllerlc%', APPLICATION_PATH . '/../library/Cli/Template/Rest/default_controller/');
			$replace = array($table, ucfirst($this->getCli()->getApplication()->getBootstrap()->getAppnamespace()), '', $controller, strtolower($controller), APPLICATION_PATH . '/');
			$dir = $this->_getDir(APPLICATION_PATH . '/../library/Cli/Template/Rest/default_controller/');
		} else {
			$search = array('%table%', '%module%','%modulelc%','%controller%','%controllerlc%', APPLICATION_PATH . '/../library/Cli/Template/Rest/module_controller/');
			$replace = array($table, ucfirst($module), $module, $controller, strtolower($controller), APPLICATION_PATH . '/');
			$dir = $this->_getDir(APPLICATION_PATH . '/../library/Cli/Template/Rest/module_controller/');
		}
	
		$search[] = '%referencemap%';
		$replace[] = $this->getReferenceMap($table);

		$search[] = '%depentendtables%';
		$replace[] = $this->getDependentTables($table);
		
		
		
		$this->_createFiles($dir, $search, $replace);
	}
	
	protected function _createRestBackend() {
		$rowset = $this->_getDb()->listTables();
		foreach ($rowset as $table) {
			$this->_createRestBackendOnce($table);
		}
	}
}