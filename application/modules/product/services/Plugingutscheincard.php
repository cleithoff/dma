<?php

class Product_Service_Plugingutscheincard extends Product_Service_Plugin {

	protected $width = 49;
	protected $height = 49;
	protected $border = 1.5;
	protected $dpi = 150;
	protected $steps = 10;
	
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
		$filenameNegate = APPLICATION_PATH . '/../resource/logo_assembly/background_' . intval($widthpx) . 'x' . intval($heightpx) . '_negate.png';
		$filenameBlack = APPLICATION_PATH . '/../resource/logo_assembly/background_' . intval($widthpx) . 'x' . intval($heightpx) . '_black.png';
		
		if (file_exists($filename)) {
			unlink($filename);
		}
		
		$exec = "convert -size " . $widthpx . "x" . $heightpx . " xc:black -fill white -stroke black -draw \"circle " . intval($widthpx/2) . "," . intval($heightpx/2) . " " . intval($widthpx/2) . "," . intval($heightpx - $borderpx) . "\" -define png:compression-level=1 " . $filename;
		exec($exec);
		
		$exec = "convert -size " . $widthpx . "x" . $heightpx . " xc:white -fill black -stroke white -draw \"circle " . intval($widthpx/2) . "," . intval($heightpx/2) . " " . intval($widthpx/2) . "," . intval($heightpx - $borderpx + 3) . "\" -define png:compression-level=1 " . $filenameNegate;
		exec($exec);
		
		$exec = "convert " . $filenameNegate . " " . $filename . " -compose darken -composite -define png:compression-level=1 " . $filenameBlack;
		exec($exec);

		return $filename;
	}
	
	protected function overlayLogo($width, $height, $dpi = 300, $useWidth, $useHeight, $filename, $backgroundFilename, $x) {
		
		$backgroundFilenameNegate = str_replace('.png', '_negate.png', $backgroundFilename);
		
		$widthpx = intval(floatval($dpi) * self::mm2inch * floatval($width));
		
		$heightpx = intval(floatval($dpi) * self::mm2inch * floatval($height));
		
		// $borderpx = intval(floatval($dpi) * self::mm2inch * floatval($border));
		
		$useWidthpx = intval(floatval($dpi) * self::mm2inch * floatval($useWidth));
		
		$useHeightpx = intval(floatval($dpi) * self::mm2inch * floatval($useHeight));
		
		$overlayFilename = APPLICATION_PATH . '/../resource/logo_assembly/overlay_' . intval($widthpx) . 'x' . intval($heightpx) . '.png';
		
		$assembledFilename = APPLICATION_PATH . '/../resource/logo_assembly/assembled_' . intval($widthpx) . 'x' . intval($heightpx) . '_a_' . intval($x) . '.png';
		$assembledFilenameProof = APPLICATION_PATH . '/../resource/logo_assembly/assembled_' . intval($widthpx) . 'x' . intval($heightpx) . '_' . intval($x) . '.png';
		
		$exec = "convert " . $filename . " -monochrome -resize " . intval($useWidthpx) . "x" . intval($useHeightpx) . " -size " . intval($widthpx) . "x" . intval($heightpx) . " xc:white +swap -gravity center -composite -negate " . $overlayFilename;
		exec($exec);
		
		$exec = "convert " . $backgroundFilename . " " . $overlayFilename . " -compose plus -composite -define png:compression-level=0 " . $assembledFilename;
		exec($exec);

		$exec = "convert " . $backgroundFilename . " " . $overlayFilename . " -compose plus -composite -define png:compression-level=0 " . $assembledFilenameProof;
		exec($exec);
		
		$exec = "convert " . $backgroundFilenameNegate . " " . $assembledFilename . " -compose darken -composite -define png:compression-level=1 " . $assembledFilename;
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
	
	public function postProcess(Order_Model_Item $order_item, $viewmode, $resourcePdfFile, $publicDeployFile) {
		
	}
	
	public function execute(Order_Model_Item $order_item, $xml) {
		
		$productPersonalize = $order_item->getProductPersonalize();
		
		if (empty($productPersonalize['filename_graphics'])) throw new Exception('Kein Logo verf�gbar.');
		
		$filename_graphics = APPLICATION_PATH . '/../resource/logo_original/' . $productPersonalize['filename_graphics'];
		
		if (!file_exists($filename_graphics)) throw new Exception('Keine Logodatei ' . $filename_graphics . ' verf�gbar.');
		 
		$pathinfo = pathinfo(APPLICATION_PATH . '/../resource/logo_original/' . $productPersonalize['filename_graphics']);
		
		$width = $this->width;
		$height = $this->width;
		$border = 0;
		$dpi = $this->dpi;
		
		$backgroundFilename = $this->initBackground($width,$height,$dpi,$border);
		$backgroundFilenameBlack = str_replace('.png', '_black.png', $backgroundFilename); 
		
		//$sizeback = getimagesize($backgroundFilename);
		//$sizefile = getimagesize($filename_graphics);
		
		
		$success = false;
		
		$oddeven = false;
		
		$x = $width;
		
		/*
		do {
			
			$oddeven = !$oddeven;
			
			$assembledFilename = $this->overlayLogo($width,$height,$dpi,$x,$x,$filename_graphics,$backgroundFilename, $x);
			
			if (filesize($backgroundFilename) == filesize($assembledFilename)) {
				$count++;
			} else {
				
			}
			
			if ($count == 2) break;
			
		} while (!$success);
		*/
		
		$steps = $this->steps;
		
		$count = 0;
		for ($x = intval($width); $x > intval($width/2); $x = intval($x - $width/$steps)) {
			
			$count++;
			
			$assembledFilename = $this->overlayLogo($width,$height,$dpi,$x,$x,$filename_graphics,$backgroundFilename, $x);
				
			if (filesize($backgroundFilenameBlack) == filesize($assembledFilename)) {
				break;
			}
		}
		
		//$assembledFilename = $this->overlayLogo($width,$height,$dpi,$x,$x,$filename_graphics,$backgroundFilename, $x);

		/*
		for ($x = intval($width - $border);$x > $border; $x = $x - $border) {
			$assembledFilename = $this->overlayLogo($width,$height,$dpi,$x,$x,$filename_graphics,$backgroundFilename, $x);
			
			if (filesize($backgroundFilename) == filesize($assembledFilename)) {
				break;
			}
		}
		*/
		
		$width = $this->width; // - $border;
		$height = $this->height; // - $border;
		
		$x = $x - ($this->border + 2);
		
		$dpi = $this->dpi;
		
		//$x = intval($width * $x / 10);
		
		$logoFilename = $this->setLogo($width,$height,$dpi,$x,$x,$filename_graphics);

		$logoFilenamePdf = str_replace('.png', '.pdf', $filename_graphics);
		if (file_exists($logoFilenamePdf)) {
			list($sizewidth, $sizeheight, $sizetype, $sizeattr) = getimagesize($filename_graphics);
				
			$sizewidthmm = $sizewidth / ($dpi * self::mm2inch);
			$sizeheightmm = $sizeheight / ($dpi * self::mm2inch);
				
			$newheight = ($sizeheightmm * $x / $sizewidthmm);
				
			$logoFilename = $logoFilenamePdf;
			return array(
					'width' => $x . "mm",
					'height' => $x . "mm",
					'paddingtop' => round(($height - $newheight) / 2, 2) . "mm",
					'paddingleft' => round(($width - $x) / 2, 2) . "mm",
					'logo' => str_replace('\\', '/', realpath($logoFilename)) . "#page=1",
			);
		}
		
		$logoFilenameSvg = str_replace('.png', '.svg', $filename_graphics);
		if (file_exists($logoFilenameSvg)) {
			list($sizewidth, $sizeheight, $sizetype, $sizeattr) = getimagesize($filename_graphics);
			
			$sizewidthmm = $sizewidth / ($dpi * self::mm2inch); 
			$sizeheightmm = $sizeheight / ($dpi * self::mm2inch);
			
			$newheight = ($sizeheightmm * $x / $sizewidthmm);
			
			$logoFilename = $logoFilenameSvg;
			return array(
					'width' => $x . "mm",
					'height' => $x . "mm",
					'paddingtop' => round(($height - $newheight) / 2, 2) . "mm",
					'paddingleft' => round(($width - $x) / 2, 2) . "mm",
					'logo' => str_replace('\\', '/', realpath($logoFilename))
			);
		}
		
		//$logoFilename = $this->setLogo($width,$height,$dpi,$x-$border,$x-$border,$filename_graphics);

		return array(
				'width' => $width . "mm",
				'height' => $height . "mm",
				'paddingtop' => "0mm",
				'paddingleft' => "0mm",
				'logo' => str_replace('\\', '/', realpath($logoFilename))
		);
		
	}
	
}