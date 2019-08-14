<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Model_Mysql4_Inquiryfiles_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
	{
  		parent::_construct();
        $this->_init('inquiry/inquiryfiles');
	}
}

