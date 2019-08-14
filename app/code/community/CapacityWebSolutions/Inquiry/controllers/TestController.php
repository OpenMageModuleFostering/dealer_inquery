<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Inquiry_TestController extends Mage_Core_Controller_Front_Action
{	
	const OWNER_EMAIL_TEMPLATE_XML_PATH = 'inquiry/admin_email/email_template';
	const CUSTOMER_EMAIL_TEMPLATE_XML_PATH = 'inquiry/customer_email/email_template';
	const INQUIRY_FORM_TITLE = 'inquiry/general/page_title';
	const INQUIRY_META_DESC = 'inquiry/general/meta_description';
	const INQUIRY_META_KEYWORDS = 'inquiry/general/meta_keywords';
	
   
	public function successAction(){
		$title = Mage::getStoreConfig(self::INQUIRY_FORM_TITLE);
		$this->_title($this->__($title));
		//$this->loadLayout(array('default'));
		$page_layout = Mage::helper("inquiry")->getDealerPageLayout();
		$this->loadLayout()->getLayout()->getBlock('root')->setTemplate('page/'.$page_layout);
		$this->renderLayout();
	}
		
 
	
}	

