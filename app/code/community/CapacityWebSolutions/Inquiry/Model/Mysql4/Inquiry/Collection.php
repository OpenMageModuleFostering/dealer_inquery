<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
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

