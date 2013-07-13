<?php

class Cli_Opt extends Zend_Console_Getopt {

	/**
	 * Singleton instance
	 *
	 * @var Zend_Auth
	 */
	private static $_instance = null;
	
	/**
	 * Returns an instance
	 *
	 * Singleton pattern implementation
	 *
	 * @return Provides a fluent interface
	 */
	public static function getInstance()
	{
		if (null === self::$_instance) {
			self::$_instance = new self(
					array(
							'help|h' => 'Displays usage information.',
							/*'rest|r-s' => 'Create rest backends from database table as zend php files. If optional parameter is set then only for a specific table.',
							 'prefix|p=s' => 'Creates only php files for tables with the prefix.',
					'store|s-s' => 'Create json stores and models for given table. Requires an extjs module app (-m).',
					'module|m=s' => 'Sets the specific extjs module app. Requires an extjs module app (-m).',
					'applytabs|t' => 'Apply tabs to combine forms and grids within a module. This allows GUI with tabpanels. Optional an extjs module app (-m); if none module given it will combine all forms per module.',
					'createapp|a' => 'Creates the extjs module app.',
					'createform|f-s' => 'Create an extjs form panel for the specified table. Requires an extjs module app (-m).',
					'creategrid|g-s' => 'Create an extjs grid panel for the specified table. Requires an extjs module app (-m).',*/
							//'ext|e=s' => 'Create ext application. Specify app name.',
							'schema|e=s' => 'DB Schema',
							'rest|r' => 'Create rest backends from database table as zend php files.',
							'overwrite|o' => 'Overwrite existing files.',
							'verbose|v' => 'Verbose messages will be dumped to the default output.',
							//'appname|n' => 'Slug of the app.',
					)
					);
		}
		return self::$_instance;
	}
	
	public function verbose($string) {
		if (isset($this->v)) {
			echo $string . "\n";
		}
	}
		
}