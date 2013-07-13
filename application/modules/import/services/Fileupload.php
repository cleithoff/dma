<?php

class Import_Service_Fileupload
{
	
	public function upload($destination) {
		$adapter = new Zend_File_Transfer_Adapter_Http();
		
		$adapter->setDestination(APPLICATION_PATH . '/../upload/' . $destination);
		
		$result = new stdClass();
		
		if (!@$adapter->receive()) {
			//$messages = $adapter->getMessages();
			//$message = implode("\n", $messages);
			$result->success = false;
			$message = 'Die Datei konnte nicht hochgeladen werden. Bitte schließen Sie die Datei, falls diese noch geöffnet ist, z.B. in Excel.';
		} else {
			$result->success = true;
			$message = 'Upload erfolgreich.';			
		}
		$result->msg = $message;

		$files = $adapter->getFileInfo();
		$file = reset($files);
		$result->filename = $file['name'];
		return $result;
	}
	
}