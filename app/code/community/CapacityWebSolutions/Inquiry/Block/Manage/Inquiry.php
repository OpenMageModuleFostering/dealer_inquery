<?php

/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2014 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Block_Manage_Inquiry extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function getAll()
	{
		$collection = Mage::getModel("inquiry/inquiry")->getCollection();
	}
	
}

