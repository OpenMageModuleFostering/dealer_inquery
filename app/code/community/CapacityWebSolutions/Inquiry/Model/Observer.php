<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
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
     

