<?php 

class Cli_Cli {
	
	protected $_application = null;
	
	public function __construct($application) {
		$this->_application = $application;
	}
	
	public function getApplication() {
		return $this->_application;
	}
	
	public function run() {		
		if (Cli_Opt::getInstance()->h === true) {
			echo Cli_Opt::getInstance()->getUsageMessage();			
		}
		
		
		$rest = new Cli_Rest($this);
		$rest->run();	
	}
	
}