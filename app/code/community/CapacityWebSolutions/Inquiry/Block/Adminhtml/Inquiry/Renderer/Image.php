<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
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