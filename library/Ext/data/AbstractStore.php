<?php

class Ext_data_AbstractStore extends Ext_Base {
	
	public $storeId = null;
	public $remoteFilter = true;
	public $remoteSort = true;	
	public $filterOnLoad = false;
	public $sortOnLoad = false;
	public $autoLoad = null;
	public $autoSync = null;
	
}