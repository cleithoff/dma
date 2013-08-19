<?php

class Order_Model_Order extends Rest_Model_DbRow
{

	protected $_partner_partner = null;
	
	/**
	 * 
	 * @param bool $refresh
	 * @return Partner_Model_Partner
	 */
	public function getPartnerPartner(bool $refresh = null) {
		if (empty($this->_partner_partner) || $refresh) {
			$partner_partners = new Partner_Model_DbTable_Partner();
			$this->_partner_partner = $partner_partners->find($this->partner_partner_id)->current();
		}
		return $this->_partner_partner;
	}
	
}

