<?php

class User_UserController extends Rest_Controller_Action_DbTable
{

	public function putAction() {
		$body = $this->getRequest()->getRawBody();
		$data = Zend_Json::decode($body);
		
		$userUsers = new User_Model_DbTable_User();
		
		$userUser = $userUsers->find($data['id'])->current();
		
		if (!empty($data['password'])) {
			$data['salt'] = uniqid() . "secret";
			$userUser->password = sha1($data['password'] . $data['salt']);
			$userUser->salt = $data['salt'];
		}
		
		$userUser->username = $data['username'];
		$userUser->user_role_id = $data['user_role_id'];
		
		$userUser->save();
		
		$this->view->data = is_array($userUser) ? $userUser : $userUser->toArray();
	
		$this->getRequest()->setParam('filter', '[{"property":"id", "value":' . $this->view->data['id'] . '}]');
		return $this->getAction();
	}
	
	public function postAction() {
		$body = $this->getRequest()->getRawBody();
		$data = Zend_Json::decode($body);
		
		if (!empty($data['password'])) {
			$data['salt'] = uniqid() . "secret";
			$data['password'] = sha1($data['password'] . $data['salt']);
		}
		
		$this->view->data = $this->getMapper()->insert($data)->toArray(); 
		 
		$this->getRequest()->setParam('filter', '[{"property":"id", "value":' . $this->view->data['id'] . '}]');
		return $this->getAction();
	}
	
	public function getAction() {
		//$table = new Eja_Model_DbTable_User();
		 
		$data = $this->getMapper()->fetch($this->getRequest());
		
		if (is_array($data)) {
			//$data = $data;
		} else {
			$data = $data->toArray();
		}
		
		foreach ($data as $key => $row) {
			$data[$key]['password'] = null;
		}
		
		$this->view->data = $data;
		
		$this->view->total = $this->getMapper()->rowCount();
		return $this->view->data;
	}
}
