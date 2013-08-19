<?php 

class Rest_Pdf {
	
	public static function fop($xml, $xsl, $pdf) {
		$fop = APPLICATION_PATH . '/../vendor/fop/fop';
		$exec = $fop . ' -xml ' . $xml . ' -xsl ' . $xsl . ' -pdf ' . $pdf;
		exec($exec);
	}
	
	public static function overlay($overlay, $document, $pdf) {
		$pdfbox = 'java -jar ' . APPLICATION_PATH . '/../vendor/pdfbox-app-1.7.1.jar';
		$exec = $pdfbox . ' Overlay ' . $overlay . ' ' . $document . ' ' . $pdf;
		exec($exec);
	}
	
	public static function toImage($pdf, $type = 'png') {
		$pdfbox = 'java -jar ' . APPLICATION_PATH . '/../vendor/pdfbox-app-1.7.1.jar';
		$exec = $pdfbox . ' PDFToImage -imageType ' . $type . ' ' . $pdf;
		exec($exec);
	}
	
}