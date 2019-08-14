<?php
/***************************************************************************
 Extension Name	: Dealer Inquiry
 Extension URL	: http://www.magebees.com/magento-dealer-inquiry-extension.html
 Copyright		: Copyright (c) 2015 MageBees, http://www.magebees.com
 Support Email	: support@magebees.com 
 ***************************************************************************/
class CapacityWebSolutions_Inquiry_IndexController extends Mage_Core_Controller_Front_Action
{	
	const OWNER_EMAIL_TEMPLATE_XML_PATH = 'inquiry/admin_email/email_template';
	const CUSTOMER_EMAIL_TEMPLATE_XML_PATH = 'inquiry/customer_email/email_template';
	const INQUIRY_FORM_TITLE = 'inquiry/general/page_title';
	const INQUIRY_META_DESC = 'inquiry/general/meta_description';
	const INQUIRY_META_KEYWORDS = 'inquiry/general/meta_keywords';
	
    public function indexAction() {
		$title = Mage::getStoreConfig(self::INQUIRY_FORM_TITLE);
		//$this->_title($this->__($title));
		$page_layout = Mage::helper("inquiry")->getDealerPageLayout();
		$this->loadLayout()->getLayout()->getBlock('root')->setTemplate('page/'.$page_layout);
		//set meta data
		$this->getLayout()->getBlock('head')->setTitle($this->__($title));
		$this->getLayout()->getBlock('head')->setDescription($this->__(Mage::getStoreConfig(self::INQUIRY_META_DESC)));
        $this->getLayout()->getBlock('head')->setKeywords($this->__(Mage::getStoreConfig(self::INQUIRY_META_KEYWORDS)));
		$this->renderLayout();
		Mage::getSingleton('core/session')->setInquiryFormData(false);
	}
		
	public function saveAction() {
		if($this->getRequest()->getPost())
		{
			$data = $this->getRequest()->getPost();
			
			$captcha =  $this->getRequest()->getParam("captcha");
			$captcha_code =  Mage::getSingleton('core/session')->getCaptcha();
			if(!empty($captcha) && $captcha != $captcha_code)	{ 
				Mage::getSingleton('core/session')->addError(Mage::helper('inquiry')->__('Captcha code does not match!'));
				Mage::getSingleton('core/session')->setInquiryFormData($data);
				//$this->_redirect('*/*/');return;
				$this->_redirectReferer();return;
			}
			$storeid = Mage::app()->getStore()->getStoreId();
			$websiteid = Mage::app()->getWebsite()->getId(); 
			
			$data['storeid']=$storeid;
			$data['websiteid']=$websiteid;
			
			$model = Mage::getModel("inquiry/inquiry");
			$collection = $model->getCollection()
									->addFieldToFilter('email',$data['email'])
									->addFieldToFilter('storeid',$storeid);
				   
			if(!$collection->getSize())	{ 	
				$data['createddt']=Mage::getModel('core/date')->date('Y-m-d H:i:s');
				$customer = Mage::getModel("customer/customer"); 
				$customer->setWebsiteId($data['websiteid']); 
				$customer->loadByEmail($data['email']);
				
				if($customer->getId()){
					$data['iscustcreated']=1;
				}
						
				if(!empty($data['date_time'])){
					$data['date_time'] = preg_replace('#(\d{2})/(\d{2})/(\d{4})\s(.*)#', '$3-$2-$1 $4', $data['date_time']);//convert datetime to mysql format
				}
				
				if(!empty($_FILES['file']['name'][0]))
				{
					$filetypes = Mage::getStoreConfig('inquiry/label_hide/file_type');
					$filetype_array = array();
					$filetype_array = explode(',',$filetypes);
					foreach($_FILES['file']['name'] as $key => $fname)
					{
						try {
							$path = Mage::getBaseDir('media') . DS . 'inquiry' . DS . 'upload' . DS;
							$uploader = new Varien_File_Uploader(
														array(
													'name' => $_FILES['file']['name'][$key],
													'type' => $_FILES['file']['type'][$key],
													'tmp_name' => $_FILES['file']['tmp_name'][$key],
													'error' => $_FILES['file']['error'][$key],
													'size' => $_FILES['file']['size'][$key]
														)
												);
							$fname = preg_replace('/[^a-zA-Z0-9._]/s', '', $_FILES['file']['name'][$key]);
							$uploader->setAllowedExtensions($filetype_array);  //Allowed extension for file
							$uploader->setAllowRenameFiles(false);             
							$uploader->setFilesDispersion(false);
							$path_parts = pathinfo($fname);
							$fileName = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];//rename file
							$uploader->save($path, $fileName);
							$data['file'][] = $fileName;
						}catch (Exception $e) {
							$data['file'] = '';
							Mage::getSingleton('core/session')->addError($e->getMessage());
							//$this->_redirect('*/*/');return;
							$this->_redirectReferer();return;
						}
					}
				}
				$model->setData($data);
				$model->save();
	
				$this->sendOwnerMail($data);//send mail to owner
				$this->sendCustomerMail($data);//send mail to dealer
			}
			else
			{
				Mage::getSingleton('core/session')->addError(Mage::helper('inquiry')->__('Email id already exits !'));
				Mage::getSingleton('core/session')->setInquiryFormData($data);
				//$this->_redirect('*/*/');return;
				$this->_redirectReferer();return;
			}
		}
		//$this->_redirect('*/*/success');
		$success_des = Mage::getStoreConfig('inquiry/general/success_des');
		Mage::getSingleton('core/session')->addSuccess(Mage::helper('inquiry')->__($success_des));
		$this->_redirectReferer();
		return;
	}
	
	public function sendOwnerMail($data){
		
		$config_change_label = Mage::getStoreConfig('inquiry/change_label');
		$adminSubject = Mage::getStoreConfig('inquiry/admin_email/heading');
		$adminName = Mage::getStoreConfig('trans_email/ident_general/name'); //sender name
		$send_to = Mage::getStoreConfig('inquiry/admin_email/send_to');
		$adminEmail = Mage::helper("inquiry")->getOwnerEmail();
				
		//template variables
		$emailTemplateVariables = array();
		$emailTemplateVariables['subject'] = $adminSubject;	
		$emailTemplateVariables['name'] = $adminName;
		
		$emailTemplateVariables['firstname'] = $data['firstname'];
		$lastname = " ";
		if(!empty($data['lastname'])){
			$lastname = $emailTemplateVariables['lastname'] = $data['lastname'];
		}
		$emailTemplateVariables['dealername'] = $data['firstname'].' '.$lastname;
		$emailTemplateVariables['company'] = $data['company'];
		if(!empty($data['taxvat'])){
			$emailTemplateVariables['vat_number'] = $data['taxvat'];
		}
		if(!empty($data['address'])){
			$emailTemplateVariables['address'] = $data['address'];
		}
		if(!empty($data['city'])){
			$emailTemplateVariables['city'] = $data['city'];
		}
		if(!empty($data['state'])){
			$emailTemplateVariables['state'] = $data['state'];
		}
		if(!empty($data['country'])){
			$country_name = explode('$$$',$data['country']);
			$emailTemplateVariables['country'] = $country_name[1];
		}
		if(!empty($data['zip'])){
			$emailTemplateVariables['zip'] = $data['zip'];
		}
		if(!empty($data['phone'])){
			$emailTemplateVariables['phone'] = $data['phone'];
		}
		$emailTemplateVariables['dealeremail'] = $data['email'];
		if(!empty($data['website'])){
			$emailTemplateVariables['website'] = $data['website'];
		}
		if(!empty($data['desc'])){
			$emailTemplateVariables['description'] =$data['desc'];
		}
		if(!empty($data['date_time'])){
			$emailTemplateVariables['datetime'] =$data['date_time'];
		}
		
		if(!empty($data['file'])){
			foreach($data['file'] as $key=>$fname){
				$file_link[$key] = "<a href='".Mage::getBaseUrl('media')."inquiry/upload/".$fname."' target='_blank'>".$fname."</a>";
			}
			$emailTemplateVariables['upload_file'] = implode(', ',$file_link);
		}
	
		if(!empty($data['extra_field_one'])){
			$emailTemplateVariables['extra_field_one'] =$data['extra_field_one'];
		}
		if(!empty($data['extra_field_two'])){
			$emailTemplateVariables['extra_field_two'] =$data['extra_field_two'];
		}
		if(!empty($data['extra_field_three'])){
			$emailTemplateVariables['extra_field_three'] =$data['extra_field_three'];
		}
		
		//labels
		$emailTemplateVariables['firstname_label'] = $config_change_label['f_name']?$config_change_label['f_name']:"First Name";
		
		$emailTemplateVariables['lastname_label'] = $config_change_label['l_name']?$config_change_label['l_name']:"Last Name";
		
		$emailTemplateVariables['company_label'] = $config_change_label['company_name']?$config_change_label['company_name']:"Company Name";
				
		$emailTemplateVariables['vat_number_label'] = $config_change_label['vat_number']?$config_change_label['vat_number']:"TAX/VAT Number";
	
		$emailTemplateVariables['address_label'] = $config_change_label['address']?$config_change_label['address']:"Address";
		
		$emailTemplateVariables['city_label'] = $config_change_label['city']?$config_change_label['city']:"City";
		
		$emailTemplateVariables['state_label'] = $config_change_label['state']?$config_change_label['state']:"State/Province";
			
		$emailTemplateVariables['country_label'] = $config_change_label['country']?$config_change_label['country']:"Country";
		
		$emailTemplateVariables['zip_label'] = $config_change_label['postal_code']?$config_change_label['postal_code']:"ZIP/Postal Code";
			
		$emailTemplateVariables['phone_label'] = $config_change_label['contact_number']?$config_change_label['contact_number']:"Contact Number";
			
		$emailTemplateVariables['email_label'] = $config_change_label['email']?$config_change_label['email']:"Email";
		
		if(!empty($data['website'])){	
			$emailTemplateVariables['website_label'] = $config_change_label['website']?$config_change_label['website']:"Website";
		}
		
		if(!empty($data['desc'])){
			$emailTemplateVariables['description_label'] = $config_change_label['description']?$config_change_label['description']:"Business Description";
		}
		
		if(!empty($data['date_time'])){
			$emailTemplateVariables['datetime_label'] = $config_change_label['datetime']?$config_change_label['datetime']:"Date Time";
		}
		
		if(!empty($data['file'])){
			$emailTemplateVariables['upload_file_label'] = $config_change_label['upload_file']?$config_change_label['upload_file']:"Uploaded File(s)";
		}
		
		if(!empty($data['extra_field_one'])){
			$emailTemplateVariables['extra_field_one_label'] = $config_change_label['extra_field_one']?$config_change_label['extra_field_one']:"Extra Field 2";
		}
		
		if(!empty($data['extra_field_two'])){
			$emailTemplateVariables['extra_field_two_label'] = $config_change_label['extra_field_two']?$config_change_label['extra_field_two']:"Extra Field 2";
		}
		
		if(!empty($data['extra_field_three'])){
			$emailTemplateVariables['extra_field_three_label'] = $config_change_label['extra_field_three']?$config_change_label['extra_field_three']:"Extra Field 3";
		}
				
		//load the custom template to the email
		$templateId = Mage::getStoreConfig(self::OWNER_EMAIL_TEMPLATE_XML_PATH);
		
		$sender = Array('name'  => $adminName,
					  'email' => $adminEmail);
		
		$translate  = Mage::getSingleton('core/translate');
		Mage::getModel('core/email_template')
			  ->setTemplateSubject($adminSubject)
			  ->sendTransactional($templateId, $sender, $adminEmail, $adminName, $emailTemplateVariables);
		$translate->setTranslateInline(true); 
	}
	
	public function sendCustomerMail($data){
		$subject_title = Mage::getStoreConfig('inquiry/customer_email/heading');
		$customerEmailId = $data['email'];
		$lastname = " ";
		if(!empty($data['lastname'])){
			$lastname = $data['lastname'];
		}
		$customerName = $data['firstname']." ".$lastname;
		$store_name = Mage::getStoreConfig('general/store_information/name');
		$adminName = Mage::getStoreConfig('trans_email/ident_general/name'); //sender name
		$adminEmail = Mage::helper("inquiry")->getOwnerEmail();			
		
		//load the custom template to the email 
		$templateId = Mage::getStoreConfig(self::CUSTOMER_EMAIL_TEMPLATE_XML_PATH);
		$sender = Array('name'  => $adminName,'email' => $adminEmail);
					  
		$vars = Array();
		$vars = Array('name'=>$customerName,'subject'=>$subject_title);
		$translate  = Mage::getSingleton('core/translate');
		Mage::getModel('core/email_template')
			  ->setTemplateSubject($subject_title)
			  ->sendTransactional($templateId, $sender, $customerEmailId, $customerName, $vars);
		$translate->setTranslateInline(true);	
	}
	
	public function successAction(){
		$title = Mage::getStoreConfig(self::INQUIRY_FORM_TITLE);
		$this->_title($this->__($title));
		//$this->loadLayout(array('default'));
		$page_layout = Mage::helper("inquiry")->getDealerPageLayout();
		$this->loadLayout()->getLayout()->getBlock('root')->setTemplate('page/'.$page_layout);
		$this->renderLayout();
	}
		
	public function refreshAction() {  
		$image_name = Mage::helper('inquiry')->createCaptchaImage();
		$this->getResponse()->clearHeaders()->setHeader('Content-type','application/json',true);
        $this->getResponse()->setBody(json_encode($image_name));
	}  
	
}	

