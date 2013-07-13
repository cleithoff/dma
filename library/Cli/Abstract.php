<?php

abstract class Cli_Abstract {
	
	protected $_cli = null;
	
	public function __construct(Cli_Cli $cli) {
		$this->_cli = $cli;
	}
	
	public function getCli() {
		return $this->_cli;
	}

	public function run() {
		$this->_parse();
	}
	
	protected function _getDb() {
		if (empty($this->_db)) {
			$this->_db = Zend_Db_Table::getDefaultAdapter();
		}
		return $this->_db;
	}
	
	protected function _getDir($directory) {
		//echo $directory; echo "\n";
		$dir = $this->_getDirArray($directory, true, true, true);
		$dir = array_reverse($dir);
		return $dir;
	}
	
	protected function _createFiles($dir, $search, $replace) {
		foreach ($dir as $filename) {
			$search['%extjsgenid%'] = '%extjsgenid%';
			$replace['%extjsgenid%'] = intval(microtime(true)*1000);
			$dest = str_replace($search, $replace, $filename);
			if (!file_exists($dest) && is_dir($filename)) {
				mkdir($dest, 0777, true);
			} elseif ((!file_exists($dest) || isset(Cli_Opt::getInstance()->o)) && is_file($filename))  {
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
	protected function _getDirArray($directory, $recursive = true, $listDirs = false, $listFiles = true, $exclude = '') {
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
	
	protected function _getName($table) {
		$parts = explode('_', $table);
		foreach($parts as $key => $part) {
			$parts[$key] = ucfirst($part);
		}
		return implode('', $parts);
	}
	
	protected function _getTableMaster($table) {
		$parts = explode('_has_', $table);
		return $parts[0];
	}

	protected function _getTableSlave($table) {
		$parts = explode('_has_', $table);
		return $parts[1];
	}
	
	protected function _getModule($table) {
		$parts = explode('_has_', $table);
		$parts = reset($parts);
		$parts = explode('_', $parts);
		if (count($parts) == 1) {
			$module = 'default';
		} else {
			$module = strtolower(reset($parts));
		}
		return $module;
	}
	
	public function getModule($table) {
		return $this->_getModule($table);
	}
	
	protected function _getController($table) {
		$parts = explode('_has_', $table);
		$partsleft = reset($parts);
		$partsleft = explode('_', $partsleft);
		$controller[] = end($partsleft);
		if (count($parts) == 2) {
			$controller[] = 'has';
			$partsright = end($parts);
			$partsright = explode('_', $partsright);
			foreach($partsright as $part) {
				$controller[] = $part;
			}
		}
		foreach ($controller as $key => $val) {
			$controller[$key] = ucfirst(strtolower($val));
		}
		return implode('', $controller);
	}
	
	public function getController($table) {
		return $this->_getController($table);
	}
}