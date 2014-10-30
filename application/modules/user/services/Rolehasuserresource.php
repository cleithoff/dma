<?php

class User_Service_Rolehasuserresource
{

	protected function ruleToInt($rules) {
		$rules = explode(',', $rules);
		$res = 0;
		foreach($rules as $rule) {
			switch ($rule) {
				case 'create': $res = $res | 1;
					break;
				case 'read': $res = $res | 2;
					break;
				case 'update': $res = $res | 4;
					break;
				case 'delete': $res = $res | 8;
					break;
				case 'execute': $res = $res | 16;
					break;
				case 'release': $res = $res | 32;
					break;
			}
		}
		return $res;
	}
	
	public function getResources($userRole) {
		if (empty($userRole)) throw new Exception('Benutzerrolle nicht gefunden.');
		
		$userRoleHasUserResourceMapper = new User_Model_DbTable_Rolehasuserresource();
		
		$userResources = array();
		
		$userRoleHasUserResources = $userRoleHasUserResourceMapper->fetchAll("user_role_id = " . intval($userRole->id));
		
		$userResourcesDbTable = new User_Model_DbTable_Resource();
		
		foreach($userRoleHasUserResources as $userRoleHasUserResource) {
			$array = $userResourcesDbTable->find($userRoleHasUserResource->user_resource_id)->current()->toArray();
			$obj = json_decode(json_encode($array), FALSE);
			
			$obj->allow = $this->ruleToInt($userRoleHasUserResource->allow);
			$obj->deny = $this->ruleToInt($userRoleHasUserResource->deny);
			$userResources[] = $obj;
		}
		
		return $userResources;
	}
}

