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
	public function updateStatusAfterDeleteCustomer($observer)
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
		
	public function updateStatusAfterCreateCustomer($observer)
	{	
		$event = $observer->getEvent();
		$email = $event->getCustomer()->getData('email');
		$websiteid = $event->getCustomer()->getData('website_id');
		$dealer_coll = Mage::getModel('inquiry/inquiry')->getCollection()
								->addFieldToFilter('email',$email)
								->addFieldToFilter('websiteid',$websiteid);
				
		foreach($dealer_coll as $d){
			$coll = Mage::getModel("inquiry/inquiry")->load($d->getDealerid());
			$coll->setData('iscustcreated','1');
			$coll->save();
		}
	} 
	
		
}
     

