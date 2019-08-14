<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_inquiry';
		$this->_blockGroup = 'inquiry';
		$this->_headerText = Mage::helper('inquiry')->__('Dealer Manager');
		parent::__construct();
		$this->_removeButton('add');
	}
}