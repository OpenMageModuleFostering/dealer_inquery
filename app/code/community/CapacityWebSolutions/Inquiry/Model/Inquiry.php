<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Model_Inquiry extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
  		parent::_construct();
	    $this->_init('inquiry/inquiry');
	}
}

