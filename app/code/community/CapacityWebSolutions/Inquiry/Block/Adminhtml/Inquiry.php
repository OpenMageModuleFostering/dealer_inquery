<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
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