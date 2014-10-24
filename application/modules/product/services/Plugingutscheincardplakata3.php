<?php 

class Product_Service_Plugingutscheincardplakata3 extends Product_Service_Plugingutscheincard {

	protected $width = 97;
	protected $height = 97;
	protected $border = 5;
	protected $dpi = 300;
	
	public function postProcess(Order_Model_Item $order_item, $viewmode, $resourcePdfFile, $publicDeployFile) {
		switch($viewmode) {
			case Product_Model_Layout::VIEW_PREVIEW_FRONT:
				$exec = 'convert -density 96 "' . $resourcePdfFile . '"  -quality 80 -resize 50% "' . $publicDeployFile . '"';
				echo $exec;die();
				exec($exec);
				break;
		}
	}
}