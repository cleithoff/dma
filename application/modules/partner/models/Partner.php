<?php

class Partner_Model_Partner extends Rest_Model_DbRow
{

	protected $_partner_address_invoice = null;
	protected $_partner_address_delivery = null;
	
	/**
	 * 
	 * @param bool $refresh
	 * @return Partner_Model_Address
	 */
	public function getPartnerAddressInvoice(bool $refresh = null) {
		if (empty($this->_partner_address_invoice) || $refresh) {
			$partner_addresses = new Partner_Model_DbTable_Address();
			$this->_partner_address_invoice = $partner_addresses->find($this->partner_address_id_invoice)->current();
		}
		return $this->_partner_address_invoice;
	}
	
	/**
	 * 
	 * @param bool $refresh
	 * @return Partner_Model_Address
	 */
	public function getPartnerAddressDelivery(bool $refresh = null) {
		if (empty($this->_partner_address_delivery) || $refresh) {
			$partner_addresses = new Partner_Model_DbTable_Address();
			$this->_partner_address_delivery = $partner_addresses->find($this->partner_address_id_delivery)->current();
		}
		return $this->_partner_address_delivery;
	}
	
}

