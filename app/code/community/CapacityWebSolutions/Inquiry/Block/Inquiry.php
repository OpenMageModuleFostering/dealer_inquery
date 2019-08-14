<?php
/**
 * @copyright  Copyright (c) 2010 Capacity Web Solutions Pvt. Ltd  (http://www.capacitywebsolutions.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
 
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

