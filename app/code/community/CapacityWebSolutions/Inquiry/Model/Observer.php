<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Model_Observer
{ 
	public function updateStatus($observer)
	{
		$event = $observer->getEvent();
		$email = $event->getCustomer()->getData('email');
		$websiteid = $event->getCustomer()->getData('website_id');
		$dealer_coll = Mage::getModel('inquiry/inquiry')->getCollection()
								->addFieldToFilter('email',$email)
								->addFieldToFilter('websiteid',$websiteid);
				
		foreach($dealer_coll as $d){
				$coll = Mage::getModel("inquiry/inquiry")->load($d->getDealerid());
				$coll->setData('iscustcreated','0');
				$coll->save();
		}
	}
	
	public function prepareLayoutBefore(Varien_Event_Observer $observer)
    {
		$enabled = Mage::getStoreConfig('inquiry/general/enabled');
		
		$disable_output = Mage::getStoreConfig('advanced/modules_disable_output/CapacityWebSolutions_Inquiry');
		
		if (!$enabled) {
            return $this;
        }
		if ($disable_output) {
		    return $this;
        }

        $block = $observer->getEvent()->getBlock();
		
		$route = Mage::app()->getRequest()->getRouteName();
		
        if ("head" == $block->getNameInLayout() && $route=="inquiry") {
      		$jquery_enabled = Mage::getStoreConfig('inquiry/general/enable_js');;
			if($jquery_enabled){
				$block->addJs('inquiry/jquery.min.js');
			}
		}
        return $this;
    }
	
}
     

