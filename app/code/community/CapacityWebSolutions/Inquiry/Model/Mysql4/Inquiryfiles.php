<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/class CapacityWebSolutions_Inquiry_Model_Mysql4_Inquiryfiles extends Mage_Core_Model_Mysql4_Abstract
{
  	public function _construct()
	{
  		$this->_init('inquiry/inquiryfiles', 'dealer_file_id');
	}
}

