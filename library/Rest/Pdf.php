<?php 

class Rest_Pdf {
	
	public static function fop($xml, $xsl, $pdf) {
		$fop = APPLICATION_PATH . Zend_Registry::getInstance()->config->cli->fop;
		$fopconfig = APPLICATION_PATH . Zend_Registry::getInstance()->config->cli->fopconfig;
		$exec = $fop . ' -c ' . $fopconfig . ' -xml ' . $xml . ' -xsl ' . $xsl . ' -pdf ' . $pdf;
		//echo $exec;die();
		exec($exec);
	}
	
	public static function getPDFPages($document)
	{
		$cmd = Zend_Registry::getInstance()->config->cli->pdfinfo;
		//$cmd = "pdfinfo";           // Linux
		//$cmd = "C:\\path\\to\\pdfinfo.exe";  // Windows
	
		// Parse entire output
		exec("$cmd $document", $output);
	
		// Iterate through lines
		$pagecount = 0;
		foreach($output as $op)
		{
			// Extract the number
			if(preg_match("/Pages:\s*(\d+)/i", $op, $matches) === 1)
			{
				$pagecount = intval($matches[1]);
				break;
			}
		}
	
		return $pagecount;
	}
	
	public static function overlay($overlay, $document, $pdf) {
		if (empty($overlay) || empty($document)) return;
		$pdfbox = 'java -jar ' . APPLICATION_PATH . '/../vendor/pdfbox-app-1.8.7.jar';
		// $exec = $pdfbox . ' Overlay ' . $overlay . ' ' . $document . ' ' . $pdf;
		if (self::getPDFPages($overlay) == 1) {
			$exec = $pdfbox . ' Overlay ' . $overlay . ' ' . $document . ' ' . $pdf;
		} else {
			$exec = $pdfbox . ' OverlayPDF ' . $overlay . ' -odd ' . $document . ' -even  ' . $document . ' -nonSeq ' . $pdf;
		}
		//echo $exec;die();
		exec($exec);
	}
	
	public static function toImage($pdf, $type = 'png') {
		if (empty($pdf)) return;
		
		$info = pathinfo(realpath($pdf));

		$exec = "convert -density 600x600 " . $pdf . " " . $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . $type;
		
		exec($exec);
	}
	
}