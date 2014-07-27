<?php

class Auth_IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->_helper->contextSwitch()
    	->clearActionContexts()
    	->addActionContext('login', 'json')
    	->setDefaultContext('json')
    	->initContext('json')
    	;
    }

    public function indexAction()
    {
        // action body
    }

    public function loginAction()
    {
        // action body
        try {
        	
        	$body = $this->getRequest()->getRawBody();
        	$data = Zend_Json::decode($body,Zend_Json::TYPE_OBJECT);
        	
	        $username = $data->username;
	        $password = $data->password;
	        
	        if (empty($username) || empty($password)) {
	        	throw new Exception('Die Logindaten sind unvollständig.');
	        }
	        
	    	$authAdapter = new Zend_Auth_Adapter_DbTable(
	    			Zend_Db_Table::getDefaultAdapter(),
	    			'user_user',
	    			'username',
	    			'password',
	    			'SHA1(CONCAT(?,salt))'
	    	);
	    	
	    	$authAdapter->setIdentity($username);
	    	$authAdapter->setCredential($password);
	    	
	    	$auth = Zend_Auth::getInstance();
	    	$result = $auth->authenticate($authAdapter);
	    	if ($result->isValid()) {
	    		$userUser = $authAdapter->getResultRowObject();
	    		$auth->getStorage()->write($userUser);
	    		$this->view->assign('success', true);
	    		
	    		$userServiceUser = new User_Service_User();
	    		$userResources = $userServiceUser->getResources($userUser);
	    		
	    		if (empty($userResources)) {
	    			throw new Exception('Keine Berechtigungen für diesen Benutzer.');
	    		}
	    		
	    		
	    		
	    		$this->view->assign('data', $userResources);
	    		
	    		return true;
	    	}
	    	throw new Exception('Login fehlgeschlagen.');
        } catch (Exception $ex) {	
        	$this->_response->clearBody();
        	$this->_response->clearHeaders();
        	$this->_response->setHttpResponseCode(500);
        	
        	$this->view->assign('success', false);
        	$this->view->assign('message', $ex->getMessage());
        }
        return false;
    }

}



