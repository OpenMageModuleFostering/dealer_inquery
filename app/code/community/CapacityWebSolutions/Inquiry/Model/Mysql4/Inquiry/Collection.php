<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Model_Mysql4_Inquiry_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
	{
  		parent::_construct();
        $this->_init('inquiry/inquiry');
	}
		
	public function inquiryFilter($dealerid) {
		$this->getSelect()->join(
				array('dealerinquiry_files' => $this->getTable('inquiry/inquiryfiles')),
				'main_table.dealerid = dealerinquiry_files.dealerid',
				array('*')
				)
				->where('dealerinquiry_files.dealerid = ?', $dealerid);
		return $this;
	}
}

