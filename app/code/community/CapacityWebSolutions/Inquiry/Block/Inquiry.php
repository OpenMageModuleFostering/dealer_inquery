<?php

/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2014 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Block_Inquiry extends Mage_Core_Block_Template
{

/*Start fo functions for admin section.*/
	public function getAllInquires()
	{
		if($collection = Mage::getModel("inquiry/inquiry")->getCollection())
			$collection->setOrder('createddt',"Asc")->load(); 
		return $collection;
	}
	
	public function getAllDealer($delId)
	{
		$collection = Mage::getModel("inquiry/inquiry")->load($delId)->getData();
		return $collection;
	}
	public function getIsCreated($email,$website_id)
	{
		$collection = Mage::getModel("customer/customer")->getCollection()->addFieldToFilter("email",$email)->addFieldToFilter("website_id",$website_id);
	
		if($collection->count())
			return 1;
		else
			return 0;
	}
	public function getRandomCode()
	{
		$an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$su = strlen($an) - 1;
		return substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1);
	}  
	
/*End of functions for admin section.*/
}

