<?php

class User_Service_User
{

	public function getResources($userUser) {
		if (empty($userUser)) throw new Exception('Benutzer nicht gefunden.');
		
		$userRoleMapper = new User_Model_DbTable_Role();
		$userRole = $userRoleMapper->find($userUser->user_role_id)->current();
		
		$userServiceRoleHasUserResource = new User_Service_RoleHasUserResource();
		
		return $userServiceRoleHasUserResource->getResources($userRole);
		
	}
	
}

