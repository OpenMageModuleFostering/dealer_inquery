<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Block_Adminhtml_Inquiry_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{
		echo "  <style type='text/css'>
		.cws_created
		{
			color: green;
		} 
		.cws_notcreated  {
			color: red;
		}
		</style>";
		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('inquiry_form', array('legend'=>Mage::helper('inquiry')->__('Dealer Information')));

		$data = Mage::registry('inquiry_data');
		$storename = Mage::getModel('core/store')->load($data->getStoreid())->getName();
		$createddt = date("d/m/Y H:i:s", strtotime($data->getCreateddt()));
		$datetimeformat = date("d/m/Y H:i:s", strtotime($data->getDateTime()));
		
		$date_time = trim(Mage::getStoreConfig('inquiry/change_label/datetime'));
		$extra_field_one = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_one'));
		$extra_field_two = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_two'));
		$extra_field_three = trim(Mage::getStoreConfig('inquiry/change_label/extra_field_three'));


		if(Mage::registry('inquiry_data')->getIscustcreated()){
			$iscustomer = 'Created';
			$cust_class = 'cws_created';
		}else{
			$cust_class = 'cws_notcreated';
			$iscustomer = 'Not Created';
		}

		$fieldset->addField('firstname', 'label', array(
			'label'     => Mage::helper('inquiry')->__('First Name'),
			'name'      => 'firstname',
			'value'		=> $data->getFirstname(),
		));

		if($data->getLastname()){
			$fieldset->addField('lastname', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Last Name'),
				'name'      => 'lastname',
				'value'		=> $data->getLastname(),
			));
		}

		$fieldset->addField('company', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Company Name'),
			'name'      => 'company',
			'value'		=> $data->getCompany(),
		));

		if($data->getTaxvat()){
			$fieldset->addField('taxvat', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Tax/VAT Number'),
				'name'      => 'taxvat',
				'value'		=> $data->getTaxvat(),
			));
		}

		if($data->getAddress()){
			$fieldset->addField('address ', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Address'),
				'name'      => 'address ',
				'value'		=> $data->getAddress(),
			));
		}

		if($data->getCity()){
			$fieldset->addField('city', 'label', array(
				'label'     => Mage::helper('inquiry')->__('City'),
				'name'      => 'city',
				'value'		=> $data->getCity(),
			));
		}

		if($data->getState()){
			$fieldset->addField('state', 'label', array(
				'label'     => Mage::helper('inquiry')->__('State'),
				'name'      => 'state',
				'value'		=> $data->getState(),
			));
		}
		
		if($data->getCountry()){
			$country = explode('$$$',$data->getCountry());
			$fieldset->addField('country', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Country'),
				'name'      => 'country',
				'value'		=> $country[1],
			));
		}
		
		if($data->getZip()){
			$fieldset->addField('zip', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Zip'),
				'name'      => 'zip',
				'value'		=> $data->getZip(),
			));
		}

		$fieldset->addField('phone', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Phone'),
			'name'      => 'phone',
			'value'		=> $data->getPhone(),
		));

		$fieldset->addField('email', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Email'),
			'name'      => 'email',
			'value'		=> $data->getEmail(),
		));

		if($data->getWebsite()){
			$fieldset->addField('website', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Website'),
				'name'      => 'website',
				'value'		=> $data->getWebsite(),
			));
		}

		$fieldset->addField('iscustcreated', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Is Created'),
			'name'      => 'iscustcreated',
			'after_element_html' => '<span class="'.$cust_class.'">'.$iscustomer.'</span>',
			
		));

		$fieldset->addField('storeid', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Store View'),
			'name'      => 'storeid',
			'value'		=> $storename,
		));
		
		$fieldset->addField('createddt', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Created Date'),
			'name'      => 'createddt',
			'value'		=> $createddt,
		));
		
		if($data->getDesc()){
			$fieldset->addField('desc', 'label', array(
				'label'     => Mage::helper('inquiry')->__('Business Description'),
				'name'      => 'desc',
				'value'		=> $data->getDesc(),
			));
		}
	
		if($data->getDateTime()){
			$fieldset->addField('date_time', 'label', array(
				'label'     => Mage::helper('inquiry')->__($date_time),
				'name'      => 'date_time',
				'value'		=> $datetimeformat,
			));
		}
		
		if($data->getFilename()){
			$file_names = explode('|',$data->getFilename());
			foreach($file_names as $key=>$file){
			$file_value = "<a href='".Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA)."inquiry/upload/".$file."' target='_blank'>".$file."</a>";
			$fieldset->addField('filename_'.$key, 'label', array(
				'label'     => Mage::helper('inquiry')->__('Uploaded File'),
				'name'      => 'filename_'.$key,
				'after_element_html'		=> $file_value,
			));
			}
		}
		
		if($data->getExtraFieldOne()){
			$fieldset->addField('extra_field_one', 'label', array(
				'label'     => Mage::helper('inquiry')->__($extra_field_one),
				'name'      => 'extra_field_one',
				'value'		=> $data->getExtraFieldOne(),
			));
		}
		
		if($data->getExtraFieldTwo()){
			$fieldset->addField('extra_field_two', 'label', array(
				'label'     => Mage::helper('inquiry')->__($extra_field_two),
				'name'      => 'extra_field_two',
				'value'		=> $data->getExtraFieldTwo(),
			));
		}
		
		if($data->getExtraFieldThree()){
			$fieldset->addField('extra_field_three', 'label', array(
				'label'     => Mage::helper('inquiry')->__($extra_field_three),
				'name'      => 'extra_field_three',
				'value'		=> $data->getExtraFieldThree(),
			));
		}
		
				
		if ( Mage::getSingleton('adminhtml/session')->getInquiryData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getInquiryData());
			Mage::getSingleton('adminhtml/session')->setInquiryData(null);
		} 
		return parent::_prepareForm();
	}
}