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
				case 'static':
					$static = $str[1];
					switch($static) {
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

	public function export(Zend_Controller_Request_Http $request) {
		
		$report_report_id = $request->getParam('report_report_id', null);
		
		if ($report_report_id === null) return;
		
		$report = new Report_Model_DbTable_Report();
		
		$row = $report->find($report_report_id)->current();
		
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
		header('Content-Disposition: attachment; filename="' . $row->fileprefix . '_' . date('YmdHis') . '.csv"');
		
		$file = $this->writeCsv($fields, $rowset, $types);
		
		
	}
	
}

