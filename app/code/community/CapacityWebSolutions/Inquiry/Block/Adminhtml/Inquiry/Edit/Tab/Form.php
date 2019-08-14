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
		$country = explode('$$$',$data->getCountry());


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

		$fieldset->addField('lastname', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Last Name'),
			'name'      => 'lastname',
			'value'		=> $data->getLastname(),
		));

		$fieldset->addField('company', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Company Name'),
			'name'      => 'company',
			'value'		=> $data->getCompany(),
		));

		$fieldset->addField('taxvat', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Tax/VAT Number'),
			'name'      => 'taxvat',
			'value'		=> $data->getTaxvat(),
		));

		$fieldset->addField('address ', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Address'),
			'name'      => 'address ',
			'value'		=> $data->getAddress(),
		));

		$fieldset->addField('city', 'label', array(
			'label'     => Mage::helper('inquiry')->__('City'),
			'name'      => 'city',
			'value'		=> $data->getCity(),
		));

		$fieldset->addField('state', 'label', array(
			'label'     => Mage::helper('inquiry')->__('State'),
			'name'      => 'state',
			'value'		=> $data->getState(),
		));

		$fieldset->addField('country', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Country'),
			'name'      => 'country',
			'value'		=> $country[1],
		));

		$fieldset->addField('zip', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Zip'),
			'name'      => 'zip',
			'value'		=> $data->getZip(),
		));

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

		$fieldset->addField('website', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Website'),
			'name'      => 'website',
			'value'		=> $data->getWebsite(),
		));

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
		
		$fieldset->addField('desc', 'label', array(
			'label'     => Mage::helper('inquiry')->__('Business Description'),
			'name'      => 'desc',
			'value'		=> $data->getDesc(),
		));
				
		if ( Mage::getSingleton('adminhtml/session')->getInquiryData() )
		{
			$form->setValues(Mage::getSingleton('adminhtml/session')->getInquiryData());
			Mage::getSingleton('adminhtml/session')->setInquiryData(null);
		} 
		return parent::_prepareForm();
	}
}