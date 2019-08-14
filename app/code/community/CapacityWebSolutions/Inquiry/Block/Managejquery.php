<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Managejquery extends Mage_Core_Block_Template
{ 
	public function addJquery()
	{
		$enabled = Mage::getStoreConfig('inquiry/general/enable_js');
	 	
		$_head = $this->__getHeadBlock();
		if($enabled){
			$_head->addItem('js', 'inquiry/jquery.min.js');
		}
		
		return $_head;
	}
	
	private function __getHeadBlock() {
		return $this->getLayout()->getBlock('head');
	}
}
