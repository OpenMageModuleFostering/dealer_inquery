<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
	public function render(Varien_Object $row)
	{
		$url = $this->getUrl('*/*/createCustomer', array('id' => $row->getId()));
		$val = Mage::getBaseUrl('media')."inquiry/create_user.png";
		 $out = "<a href='".$url."'><img src=". $val ." width='30px' height='30px' title='Create Customer' alt='Create Customer'/></a>";
        return $out;
	}
}