<?php

class Mail_ImapController extends Zend_Controller_Action
{

   public function init()
    {
    	$this->_helper->contextSwitch()
    	->clearActionContexts()
    	->addActionContext('index', 'json')
    	->addActionContext('put', 'json')
    	->addActionContext('get', 'json')
    	->addActionContext('post', 'json')
    	->addActionContext('delete', 'json')
    	->addActionContext('meta','json')
    	->setDefaultContext('json')
    	->initContext('json')
    	;
    }

    public function indexAction()
    {
    	$data = array();
    	try {
	        /*if($this->getRequest()->getMethod() === 'POST')
	    	{
	    		$data =  $this->_forward('post');
	    	}
			*/
	    	if($this->getRequest()->getMethod() === 'GET')
	    	{
	    		$data = $this->_forward('get');
	    	}
	    	/*
	    	if($this->getRequest()->getMethod() === 'PUT')
	    	{
	    		$data =  $this->_forward('put');
	    	}
	    	
	    	if($this->getRequest()->getMethod() === 'DELETE')
	    	{
	    		$data =  $this->_forward('delete');
	    	}
	    	if($this->getRequest()->getMethod() === 'META')
	    	{
	    		$data =  $this->_forward('meta');
	    	}
	    	if($this->getRequest()->getMethod() === 'EXPORT')
	    	{
	    		$data =  $this->_forward('export');
	    	}
	    	*/
    	} catch (Exception $ex) {
    		// {"message":"Applicationerror","exception":{},"request":{}}
    		$data = array(
    				"message" => $ex->getMessage(),
    				);
    	}
    	return $data;
    }
    
    protected function getMails(Zend_Mail_Storage_Imap $storage, $filter) {
    	$partner_nr = reset($filter);
    	// if (empty($partner_nr)) $partner_nr = "99999999";
    	$partner_nr = $partner_nr->value;
    	if (empty($partner_nr)) return; // $this->getRequest()->getParam('partner_nr', "00000000");
    	$messages = $storage->search($partner_nr);
    	
    	$data = array();
    	foreach ($messages as $messageid) {
    		$datamsg = new stdClass();
    		$msg = $storage->getMessage($messageid);
    		$datamsg->id = $messageid;
    		$datamsg->mailFrom = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('from')));
    		$datamsg->mailTo = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('to')));
    		$datamsg->mailSubject = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('subject')));
    		$datamsg->mailDate = date('Y-m-d H:i:s', strtotime($msg->getHeaderField('date')));
    		$datamsg->mailMessage = "";

    		/*
    		$foundPart = null;
    		foreach (new RecursiveIteratorIterator($msg) as $part) {
    			try {
    	
    				if (strtok($part->contentType, ';') == 'text/plain' || strtok($part->contentType, ';') == 'text/html') {
    					$foundPart = $part;
    					break;
    				}
    			} catch (Zend_Mail_Exception $e) {
    				// ignorieren
    			}
    		}
    		if (empty($foundPart)) {
    			$datamsg->mailMessage = iconv("ISO-8859-1", "UTF-8", strip_tags(quoted_printable_decode($msg->getContent())));
    		} else {
    			$datamsg->mailMessage = iconv("ISO-8859-1", "UTF-8", strip_tags(quoted_printable_decode($foundPart)));
    		}
    		 
    		$testc = new stdClass();
    		$testc->test = utf8_decode($datamsg->mailMessage);
    		$testc = json_decode(json_encode($testc));
    		if (!empty($testc->test)) {
    			$datamsg->mailMessage = utf8_decode($datamsg->mailMessage);
    		}
    		*/
    			
    		 
    		$data[] = $datamsg;
    	}
    	return $data;
    }
    
    protected function getMail(Zend_Mail_Storage_Imap $storage, $messageid) {
    	// if (empty($partner_nr)) $partner_nr = "99999999";
    	$messageid = $messageid->value;
    	if (empty($messageid)) return; // $this->getRequest()->getParam('partner_nr', "00000000");
    	
    	$data = array();

    		$datamsg = new stdClass();
    		$msg = $storage->getMessage($messageid);
    		$datamsg->id = $messageid;
    		$datamsg->mailFrom = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('from')));
    		$datamsg->mailTo = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('to')));
    		$datamsg->mailSubject = iconv("ISO-8859-1", "UTF-8", quoted_printable_decode($msg->getHeaderField('subject')));
    		$datamsg->mailDate = date('Y-m-d H:i:s', strtotime($msg->getHeaderField('date')));
    		$datamsg->mailMessage = "";

    		
    		$foundPart = null;
    		foreach (new RecursiveIteratorIterator($msg) as $part) {
    			try {
    	
    				if (strtok($part->contentType, ';') == 'text/plain' || strtok($part->contentType, ';') == 'text/html') {
    					$foundPart = $part;
    					break;
    				}
    			} catch (Zend_Mail_Exception $e) {
    				// ignorieren
    			}
    		}
    		if (empty($foundPart)) {
    			$datamsg->mailMessage = iconv("ISO-8859-1", "UTF-8", strip_tags(quoted_printable_decode($msg->getContent())));
    		} else {
    			$datamsg->mailMessage = iconv("ISO-8859-1", "UTF-8", strip_tags(quoted_printable_decode($foundPart)));
    		}
    		 
    		$testc = new stdClass();
    		$testc->test = utf8_decode($datamsg->mailMessage);
    		$testc = json_decode(json_encode($testc));
    		if (!empty($testc->test)) {
    			$datamsg->mailMessage = utf8_decode($datamsg->mailMessage);
    		}
    		
    			
    		 
    		$data[] = $datamsg;

    	return $data;
    }

    public function getAction()
    {
        $storage = new Zend_Mail_Storage_Imap(array(
	    'host'     => 'mgate3.dm-mundschenk.de',
	    'user'     => 'fleurop',
	    'password' => 'fleurop1234'));

        $filter = json_decode($this->getRequest()->getParam('filter', null));
        
        // if (empty($filter)) return;
        
        $property = reset($filter);
        
        switch($property->property) {
        	case 'partner_nr':
        		$data = $this->getMails($storage, $filter);
        		break;
        	case 'id':
        		$data = $this->getMail($storage, $property->value);
        		break;
        	default:
        		$messageid = $this->getRequest()->getParam('id', null);
        		if (!empty($messageid)) {
        			$data = $this->getMail($storage, $messageid);
        		}
        		break;
        	
        }
        
        $this->view->data = $data;
        // $this->view->success = true;
        $this->view->total = strval(count($data));
        
        return $data;
    }


}

