<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/ 
class CapacityWebSolutions_Inquiry_Block_Inquiry extends Mage_Core_Block_Template
{
	
	public function __construct()
	{
		//general settings
		$this->setLinkEnabled((bool)Mage::getStoreConfig("inquiry/general/enable_toplink"));
		$this->setUrlKey(trim(Mage::getStoreConfig("inquiry/general/url_key")));
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
		$this->setCaptcha(trim(Mage::getStoreConfig('inquiry/change_label/captcha')));
				
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
	
	public function getAllDealer($delId){
		$collection = Mage::getModel("inquiry/inquiry")->load($delId)->getData();
		return $collection;
	}
	
	//for add top link
	public function addTopLinkStores(){	
		$enable_link = $this->getLinkEnabled();
		$url_key =	$this->getUrlKey()?$this->getUrlKey():"inquiry";
		if($enable_link){
			$label = trim($this->getLinkLabel());
			$storeID = Mage::app()->getStore()->getId();
			$toplinkBlock = $this->getParentBlock();
			if($toplinkBlock)
			$toplinkBlock->addLink($this->__($label),$url_key,$label,true,array(),90);
		}
	}
	
	//for get form data from session after captcha not match
	public function getFormData(){
		$data = $this->getData('inquiry_data');
		if (is_null($data)) {
			$formData = Mage::getSingleton('core/session')->getInquiryFormData();
            $data = new Varien_Object();
            if ($formData) {
                $data->addData($formData);
			}
			$this->setData('inquiry_data', $data);
		}
		return $data;
	}
	
	public function getStateFromCountry($countrycode){
		$code = explode('$$$',$countrycode);
		$statearray = array();
        if ($countrycode != '') {
            $statearray = Mage::getModel('directory/region_api')->items($code[0]);
		}
		return $statearray;
	}
	
}