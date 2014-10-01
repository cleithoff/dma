<?php

class Report_Service_Report
{
	
	protected function getFieldsFromInfo($info) {
		
		$fields = array();
		
		foreach($info as $field) {
			$fields[] = $field['name'];
		}
		
		return $fields;
		
	}
	
	protected function getTypesFromInfo($info) {
	
		$types = array();
	
		foreach($info as $field) {
			$types[$field['name']] = $field['native_type'];
		}
	
		return $types;
	
	}	

	protected function writeCsv($fields, $rowset, $types) {
		
		$fp = tmpfile();
		
		fputcsv($fp, $fields, ';');
		
		foreach ($rowset as $row) {
			foreach ($row as $key => $value) {
				switch ($types[$key]) {
					case 'DOUBLE':
						$row[$key] = number_format($value, 2, ',', '');
						break;
					default:
						$row[$key] = utf8_decode($value); 
						break;
				}
			}
			fputcsv($fp, $row, ';');
		}
		
		fseek($fp, 0);
		fpassthru($fp);
		
		fclose($fp);
	}

	private function _getReplaces($matches, $doQuote, $csv, $result, $param) {
	
		$replacements = array();
		foreach ($matches as $match) {
			$str = str_replace(array('{','}','"'), array('','','"'), $match);
			$str = explode('.', $str);
			$source = $str[0];
			switch($source) {
				case 'csv':
					$col = str_replace('"', '', $str[1]);
					$replacement = $csv[$col];
					break;
				case 'result':
					$table = $str[1];
					$col = $str[2];
					$replacement = $result[$table][$col];
					break;
				case 'param':
					$key = $str[1];
					$replacement = $param[$key];
					break;
				case 'config':
					$replacement = Zend_Registry::getInstance()->config;
					foreach ($str as $k => $s) {
						if ($k == 0) continue;
						$replacement = $replacement->$s;
					}
					break;
				case 'static':
					$static = $str[1];
					switch($static) {
						case 'APPLICATION_PATH':
							$replacement = str_replace(DIRECTORY_SEPARATOR, '/', APPLICATION_PATH);
							break;
						case 'PHPSESSID':
							$replacement = Zend_Session::getId();
							break;
					}
					break;
				default:
					$replacement = '';
					break;
			}
			if ($doQuote) {
				$replacement = '"' . $replacement . '"';
			}
			array_push($replacements, $replacement); // $replacements
		}
		return $replacements;
	}
	
	private function _getQuery($sql, $csv, $result, $param) {
		
		$matches = array();
		preg_match_all('/\{[^\{\}]*\}/', $sql, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], true, $csv, $result, $param);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}'), array('\\{','\\.','\\}'), $match) . '/';
			}
			$sql = preg_replace($matches[0], $replacements, $sql);
		}
	
		$matches = array();
		preg_match_all('/\{.*?\}/', $sql, $matches);
		if (count($matches) > 0) {
			$replacements = $this->_getReplaces($matches[0], true, $csv, $result, $param);
			foreach($matches[0] as $key => $match) {
				$matches[0][$key] = '/' . str_replace(array('{','.','}'), array('\\{','\\.','\\}'), $match) . '/';
			}
			$sql = preg_replace($matches[0], $replacements, $sql);
		}
	
		return $sql;
	}	

	public function exportcsv(Zend_Controller_Request_Http $request) {
		
		$row = $this->getReport($request);
		
		$sql = $this->_getQuery($row->sql, array(), array(), $request->getParams());
		
		$result = Zend_Db_Table::getDefaultAdapter()->query($sql);
		
		$i = 0;
		$info = array();
		while ($meta = $result->getColumnMeta($i)) {
			$info[] = $meta;
			$i++;
		}
		
		$fields = $this->getFieldsFromInfo($info);
		
		$types = $this->getTypesFromInfo($info);
		
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql);

		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . @date('YmdHis') . '.csv"');
		
		$file = $this->writeCsv($fields, $rowset, $types);
		
	}
	
	protected function generateXml($row, Zend_Controller_Request_Http $request) {
		$sql = $this->_getQuery($row->sql, array(), array(), $request->getParams());
		Zend_Db_Table::getDefaultAdapter()->query("SET @counter:=0;");
		$result = Zend_Db_Table::getDefaultAdapter()->query($sql);
		
		$i = 0;
		$info = array();
		while ($meta = $result->getColumnMeta($i)) {
			$info[] = $meta;
			$i++;
		}
		
		$fields = $this->getFieldsFromInfo($info);
		
		$types = $this->getTypesFromInfo($info);
		Zend_Db_Table::getDefaultAdapter()->query("SET @counter:=0;");
		$rowset = Zend_Db_Table::getDefaultAdapter()->query($sql);
		
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . @date('YmdHis') . '.xml"');
		
		$filename = $this->createXmlFile();
		
		$this->writeXml($filename, $fields, $rowset, $types, $row->xmlgrouping, 'ReportDetail');
		
		return $filename;
	}
	
	protected function getReport(Zend_Controller_Request_Http $request) {
		$report_report_id = $request->getParam('report_report_id', null);
		
		if ($report_report_id === null) return;
		
		$report = new Report_Model_DbTable_Report();
		
		$row = $report->find($report_report_id)->current();
		
		return $row;
	}
	
	protected function generateXsd($xmlfilename, $row, $request) {
		$xsdfilename = tempnam(sys_get_temp_dir() , "exportxsd") . ".xsd";
		
		$exec = "java -jar " . APPLICATION_PATH . Zend_Registry::getInstance()->config->cli->trang . " " . $xmlfilename . " " . $xsdfilename;

		exec($exec);
		
		return $xsdfilename;
	}
	
	public function exportxsd(Zend_Controller_Request_Http $request) {
		
		$row = $this->getReport($request);
		
		$filename = $this->generateXml($row, $request);
		
		$filename = $this->generateXsd($filename, $row, $request);
		
		header('Content-type: text/xsd');
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . @date('YmdHis') . '.xsd"');
		
		readfile($filename);
		
	}
	
	public function exportxml(Zend_Controller_Request_Http $request) {
		
		$row = $this->getReport($request);
		
		$filename = $this->generateXml($row, $request);
		
		header('Content-type: text/xml');
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . @date('YmdHis') . '.xml"');
		
		readfile($filename);
		
	}
	
	public function exportpdf(Zend_Controller_Request_Http $request) {

		$row = $this->getReport($request);
	
		$xmlfilename = $this->generateXml($row, $request);
		
		$pdffilename = APPLICATION_PATH . '/../resource/report_pdf/' . $row->fileprefix . '_' . @date('YmdHis') . '.pdf';
		
		Rest_Pdf::fop($xmlfilename, APPLICATION_PATH . '/../resource/report_xsl/' . $row->xslfile, $pdffilename);
		
		header('Content-type: application/pdf');
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . @date('YmdHis') . '.pdf"');
		
		readfile($pdffilename);
		unlink($pdffilename);
		die();
	}
	
	protected function createXMLFile($filename = null) {
		if (empty($filename)) {
			$filename = tempnam(sys_get_temp_dir() , "exportxml") . ".xml";
		}
		$handle = fopen($filename, "w+");
		fwrite($handle, '<?xml version="1.0" encoding="UTF-8" ?><data></data>');
		fclose($handle);
		
		return $filename;
	}
	
	protected function writeXmlRecursive($node, $fields, $rowset, $types, $xmlgrouping = array(), $nodename, $lvl) {
		
		$listnode = $node->addChild('ListOf' . $node->getName());
		
		foreach($rowset as $row) {
			$itemnode = $listnode->addChild('ItemOf' . $node->getName());
			foreach($fields as $field) {
				$propertyNode = $itemnode->addChild($field, $row[$field]);
			}
			
			if (!empty($xmlgrouping[$lvl])) {
				$this->writeXmlRecursive($node, $fields, $rowset, $types, $xmlgrouping = array(), $nodename, $lvl);
			}
			
		}
		
	}
	
	protected function writeXml($filename, $fields, $rowset, $types, $xmlgrouping, $nodename) {
		
		$xml = simplexml_load_file($filename);
		
		$xmlgrouping = explode(',', $xmlgrouping);
		
		$node = $xml->addChild($nodename);
		
		if (count($xmlgrouping) === 0) {
			$listnode = $node->addChild('ListOf' . $node->getName());
			foreach($rowset as $row) {
				$itemnode = $listnode->addChild('ItemOf' . $node->getName());
				foreach($fields as $field) {
					$propertyNode = $itemnode->addChild($field, $row[$field]);
				}
			}				
		} else {
			$listnode = $node->addChild('ListOf' . $node->getName());
			$xmlgroupvals = array();
			/*foreach ($xmlgrouping as $xmlgroup) {
				$xmlgroupvals[$xmlgroup] = null;
			}*/
			
			foreach($rowset as $row) {

				$activelistnode = $listnode;
				
				foreach ($xmlgrouping as $key => $xmlgroup) {
					
					$listname = 'ListOf' . $node->getName() . $xmlgroup;
					//echo $row[$xmlgroup];echo "|";
					
					if (empty($row[$xmlgroup])) continue;
					
					if (!isset($xmlgroupvals[$xmlgroup]) || $row[$xmlgroup] !== $xmlgroupvals[$xmlgroup]) {
//echo "@";
						$xmlgroupvals[$xmlgroup] = $row[$xmlgroup];
						$activelistnode = $activelistnode->addChild($listname);
						/*
						if (empty($activelistnode->$listname) || empty($activelistnode->$listname[0])) {
							$activelistnode = $activelistnode->addChild($listname);
						} else {
							$activelistnode = $activelistnode->$listname; //[0];
						}
						*/
						$activenode = $activelistnode->addChild('ListItemOf' . $node->getName() . $xmlgroup);

						foreach($fields as $field) {
							$propertyNode = $activenode->addChild($field, $row[$field]);
						}
						
						for ($i = $key; $i < count($xmlgrouping);$i++) {
							//$xmlgroupvals[$xmlgrouping[$i]] = null;
							//unset($xmlgroupvals[$xmlgrouping[$i]]);
						}
					} else {
						 $children = $activelistnode->children();
						 $activelistnode = $children[$activelistnode->count()-1];
						//var_dump($count);
						//$activelistnode = end($activelistnodes);
					}
					
					$activenode = $activelistnode->addChild('ItemOf' . $node->getName() . $xmlgroup);
					foreach($fields as $field) {
						$propertyNode = $activenode->addChild($field, $row[$field]);
					}
					
					$activelistnode = $activenode;
				}

				/*$activenode = $activelistnode->addChild('ItemOf' . $node->getName() . $xmlgroup);
				foreach($fields as $field) {
					$propertyNode = $activenode->addChild($field, $row[$field]);
				}*/
			}
		}		
		
		//$this->writeXmlRecursive($node, $fields, $rowset, $types, $xmlgrouping, $nodename, 0);
		
		$handle = fopen($filename, "wb");
		fwrite($handle, $xml->asXML());
		fclose($handle);

	}
	
	public function execute(Zend_Controller_Request_Http $request) {
	
		$sql = $this->_getQuery($request->getParam('_sql', null), array(), array(), $request->getParams());

		$result = Zend_Db_Table::getDefaultAdapter()->query($sql);
		
	}
}

