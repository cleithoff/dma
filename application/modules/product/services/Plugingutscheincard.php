<?php

class Product_Service_Plugingutscheincard extends Product_Service_Plugin {

	protected $width = 50;
	protected $height = 50;
	protected $border = 2;
	protected $dpi = 300;
	
	const mm2inch = 0.03937007874;
	
	/**
	 * 
	 * @param int $width in mm
	 * @param int $height in mm
	 * @param int $dpi in dpi
	 * @param int $border in mm
	 * @return string
	 */
	protected function initBackground($width, $height, $dpi = 300, $border = 0) {
		
		$widthpx = intval(floatval($dpi) * self::mm2inch * floatval($width));
		
		$heightpx = intval(floatval($dpi) * self::mm2inch * floatval($height));
		
		$borderpx = intval(floatval($dpi) * self::mm2inch * floatval($border));
		
		$filename = APPLICATION_PATH . '/../resource/logo_assembly/background_' . intval($widthpx) . 'x' . intval($heightpx) . '.png';
		
		if (file_exists($filename)) {
			unlink($filename);
		}
		
		$exec = "convert -size " . $widthpx . "x" . $heightpx . " xc:black -fill white -stroke black -draw \"circle " . intval($widthpx/2) . "," . intval($heightpx/2) . " " . intval($widthpx/2) . "," . intval($heightpx - $borderpx) . "\" -define png:compression-level=0 " . $filename;
		exec($exec);
				
		return $filename;
	}
	
	protected function overlayLogo($width, $height, $dpi = 300, $useWidth, $useHeight, $filename, $backgroundFilename, $x) {
		
		$widthpx = intval(floatval($dpi) * self::mm2inch * floatval($width));
		
		$heightpx = intval(floatval($dpi) * self::mm2inch * floatval($height));
		
		// $borderpx = intval(floatval($dpi) * self::mm2inch * floatval($border));
		
		$useWidthpx = intval(floatval($dpi) * self::mm2inch * floatval($useWidth));
		
		$useHeightpx = intval(floatval($dpi) * self::mm2inch * floatval($useHeight));
		
		$overlayFilename = APPLICATION_PATH . '/../resource/logo_assembly/overlay_' . intval($widthpx) . 'x' . intval($heightpx) . '.png';
		
		$assembledFilename = APPLICATION_PATH . '/../resource/logo_assembly/assembled_' . intval($widthpx) . 'x' . intval($heightpx) . '.png';
		
		$exec = "convert " . $filename . " -monochrome -resize " . intval($useWidthpx) . "x" . intval($useHeightpx) . " -size " . intval($widthpx) . "x" . intval($heightpx) . " xc:white +swap -gravity center -composite -negate " . $overlayFilename;
		exec($exec);
		
		$exec = "convert " . $backgroundFilename . " " . $overlayFilename . " -compose plus -composite -define png:compression-level=0 " . $assembledFilename;
		exec($exec);

		return $assembledFilename;
		 // === radialmaskbw.png
		
	}
	
	protected function setLogo($width, $height, $dpi = 300, $useWidth, $useHeight, $filename) {
		$pathinfo = pathinfo($filename);
		
		$widthpx = intval(floatval($dpi) * self::mm2inch * floatval($width));
		$heightpx = intval(floatval($dpi) * self::mm2inch * floatval($height));
		
		$useWidthpx = intval(floatval($dpi) * self::mm2inch * floatval($useWidth));
		$useHeightpx = intval(floatval($dpi) * self::mm2inch * floatval($useHeight));
		
		$logoFilename = APPLICATION_PATH . '/../resource/logo_assembled/' . $pathinfo['filename'] . '_' . intval($widthpx) . 'x' . intval($heightpx) .'.png';
		
		$exec = "convert " . $filename . " -resize " . intval($useWidthpx) . "x" . intval($useHeightpx) . " -size " . intval($widthpx) . "x" . intval($heightpx) . " xc:transparent +swap -gravity center -composite  " . $logoFilename;
		exec($exec);
		
		return $logoFilename;
	}
	
	public function execute(Order_Model_Item $order_item, $xml) {
		
		$productPersonalize = $order_item->getProductPersonalize();
		
		if (empty($productPersonalize['filename_graphics'])) throw new Exception('Kein Logo verfügbar.');
		
		$filename_graphics = APPLICATION_PATH . '/../resource/logo_original/' . $productPersonalize['filename_graphics'];
		
		if (!file_exists($filename_graphics)) throw new Exception('Keine Logodatei ' . $filename_graphics . ' verfügbar.');
		 
		$pathinfo = pathinfo(APPLICATION_PATH . '/../resource/logo_original/' . $productPersonalize['filename_graphics']);
		
		$width = $this->width;
		$height = $this->height;
		$border = $this->border;
		$dpi = $this->dpi;
		
		$backgroundFilename = $this->initBackground($width,$height,$dpi,$border);
		
		for ($x = intval($width - $border);$x > $border; $x = $x - $border) {
			$assembledFilename = $this->overlayLogo($width,$height,$dpi,$x,$x,$filename_graphics,$backgroundFilename, $x);
			
			if (filesize($backgroundFilename) <= filesize($assembledFilename)) {
				break;
			}
		}
		
		$logoFilename = $this->setLogo($width,$height,$dpi,$x,$x,$filename_graphics);
		
		return array(
				'logo' => str_replace('\\', '/', realpath($logoFilename))
		);
		
	}
	
}