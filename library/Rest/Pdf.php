<?php 

class Rest_Pdf {
	
	public static function fop($xml, $xsl, $pdf) {
		$fop = APPLICATION_PATH . Zend_Registry::getInstance()->config->cli->fop;
		$fopconfig = APPLICATION_PATH . Zend_Registry::getInstance()->config->cli->fopconfig;
		$exec = $fop . ' -c ' . $fopconfig . ' -xml ' . $xml . ' -xsl ' . $xsl . ' -pdf ' . $pdf;
		// echo $exec;die();
		exec($exec);
	}
	
	public static function overlay($overlay, $document, $pdf) {
		if (empty($overlay) || empty($document)) return;
		$pdfbox = 'java -jar ' . APPLICATION_PATH . '/../vendor/pdfbox-app-1.8.7.jar';
		$exec = $pdfbox . ' Overlay ' . $overlay . ' ' . $document . ' ' . $pdf;
		exec($exec);
	}
	
	public static function toImage($pdf, $type = 'png') {
		if (empty($pdf)) return;
		
		$info = pathinfo(realpath($pdf));

		$exec = "convert -density 1200 " . $pdf . " -resize -50% " . $info['dirname'] . DIRECTORY_SEPARATOR . $info['filename'] . '.' . $type;
		
		//$pdfbox = 'java -jar ' . APPLICATION_PATH . '/../vendor/pdfbox-app-1.8.7.jar';
		//$exec = $pdfbox . ' PDFToImage -imageType ' . $type . ' ' . $pdf;
		//echo $exec; die();
		exec($exec);
	}
	
}