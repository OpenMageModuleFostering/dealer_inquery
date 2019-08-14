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
	public function __construct()
	{
		//general settings
		$this->setLinkEnabled((bool)Mage::getStoreConfig("inquiry/general/enable_toplink"));
		$this->setLinkLabel(Mage::getStoreConfig("inquiry/general/toplink"));
		$this->setHeading(trim(Mage::getStoreConfig('inquiry/general/heading')));
		$this->setIndicates(trim(Mage::getStoreConfig('inquiry/general/indicates')));
		$this->setDesc(trim(Mage::getStoreConfig('inquiry/general/description')));
		$this->setBtnText(trim(Mage::getStoreConfig('inquiry/general/btn_text')));
				
		//change label settings
		$this->setFirstName(trim(Mage::getStoreConfig('inquiry/change_label/f_name')));
		$this->setLastName(trim(Mage::getStoreConfig('inquiry/change_label/l_name'))); 
		$this->setCompanyName(trim(Mage::getStoreConfig('inquiry/change_label/company_name'))); 
		$this->setVatNumber(trim(Mage::getStoreConfig('inquiry/change_label/vat_number'))); 
		$this->setAddress(trim(Mage::getStoreConfig('inquiry/change_label/address'))); 
		$this->setCity(trim(Mage::getStoreConfig('inquiry/change_label/city'))); 
		$this->setState(trim(Mage::getStoreConfig('inquiry/change_label/state'))); 
		$this->setCountry(trim(Mage::getStoreConfig('inquiry/change_label/country'))); 
		$this->setPostalCode(trim(Mage::getStoreConfig('inquiry/change_label/postal_code'))); 
		$this->setContactNumber(trim(Mage::getStoreConfig('inquiry/change_label/contact_number'))); 
		$this->setEmail(trim(Mage::getStoreConfig('inquiry/change_label/email'))); 
		$this->setWebsite(trim(Mage::getStoreConfig('inquiry/change_label/website')));
		$this->setDescription(trim(Mage::getStoreConfig('inquiry/change_label/description')));
		$this->setDateTime(trim(Mage::getStoreConfig('inquiry/change_label/datetime')));
		$this->setUploadFile(trim(Mage::getStoreConfig('inquiry/change_label/upload_file')));
		$this->setExtraFieldOne(trim(Mage::getStoreConfig('inquiry/change_label/extra_field_one')));
		$this->setExtraFieldTwo(trim(Mage::getStoreConfig('inquiry/change_label/extra_field_two')));
		$this->setExtraFieldThree(trim(Mage::getStoreConfig('inquiry/change_label/extra_field_three')));
		
		//show/hide labels settings
		$this->setLastNameHide((bool)Mage::getStoreConfig('inquiry/label_hide/l_name'));  
		$this->setVatNumberHide((bool)Mage::getStoreConfig('inquiry/label_hide/vat_number')); 
		$this->setAddressHide((bool)Mage::getStoreConfig('inquiry/label_hide/address')); 
		$this->setCityHide((bool)Mage::getStoreConfig('inquiry/label_hide/city')); 
		$this->setStateHide((bool)Mage::getStoreConfig('inquiry/label_hide/state')); 
		$this->setCountryHide((bool)Mage::getStoreConfig('inquiry/label_hide/country')); 
		$this->setPostalCodeHide((bool)Mage::getStoreConfig('inquiry/label_hide/postal_code')); 
		$this->setWebsiteHide((bool)Mage::getStoreConfig('inquiry/label_hide/website'));
		$this->setCaptchaHide((bool)Mage::getStoreConfig('inquiry/label_hide/captcha'));
		$this->setDateTimeHide((bool)Mage::getStoreConfig('inquiry/label_hide/datetime'));
		$this->setUploadFileHide((bool)Mage::getStoreConfig('inquiry/label_hide/upload_file'));
		$this->setFieldOneHide((bool)Mage::getStoreConfig('inquiry/label_hide/field_one'));
		$this->setFieldTwoHide((bool)Mage::getStoreConfig('inquiry/label_hide/field_two'));
		$this->setFieldThreeHide((bool)Mage::getStoreConfig('inquiry/label_hide/field_three'));
	}
	
	public function getAllInquires()
	{
		if($collection = Mage::getModel("inquiry/inquiry")->getCollection())
			$collection->setOrder('createddt',"ASC")->load(); 
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
		$enable_link = $this->getLinkEnabled();
		if($enable_link){
			$label = trim($this->getLinkLabel());
			$storeID = Mage::app()->getStore()->getId();
			$toplinkBlock = $this->getParentBlock();
			$toplinkBlock->addLink($this->__($label),'inquiry/',$label,true,array(),90);
		}
	}

}

