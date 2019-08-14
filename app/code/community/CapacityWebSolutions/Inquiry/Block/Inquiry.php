<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Block_Inquiry extends Mage_Core_Block_Template
{
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
	
	public function getRandomCode()
	{
		$an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
		$su = strlen($an) - 1;
		return substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1) .
			substr($an, rand(0, $su), 1);
	}  
	
	//for add top link
	public function addTopLinkStores()
	{	
		$label = trim(Mage::getStoreConfig('inquiry/general/toplink'));
		$storeID = Mage::app()->getStore()->getId();
		$toplinkBlock = $this->getParentBlock();
		$toplinkBlock->addLink($this->__($label),'inquiry/',$label,true,array(),90);
	}

}

