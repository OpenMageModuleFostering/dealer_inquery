<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function updateDetails(){
		$inquiry_model = Mage::getModel('inquiry/inquiry');
		$collection = $inquiry_model->getCollection();
		foreach($collection as $data){
			if(!$data->getWebsiteid()){
				$websiteid = Mage::getModel('core/store')->load($data->getStoreid())->getWebsiteId();
				$inquiry_model->load($data->getDealerid())
				->setData('websiteid',$websiteid)
				->save();
				
				$customer = Mage::getModel("customer/customer"); 
				$customer->setWebsiteId($websiteid); 
				$customer->loadByEmail($data->getEmail());
				
				if($customer->getId()){
					$dealerbyid = $inquiry_model->load($data->getDealerid())
					->setData('iscustcreated','1')
					->save();
						
				}
			}
		}
	}
}
