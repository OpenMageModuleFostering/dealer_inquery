<?php
/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2015 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/

class CapacityWebSolutions_Inquiry_Adminhtml_InquiryController extends Mage_Adminhtml_Controller_action
{
	const EMAIL_TEMPLATE_XML_PATH = 'inquiry/create_account/email_template';
	
	protected function _initAction() {
		$this->loadLayout()->_setActiveMenu('cws');
		return $this;
	}   
 
	public function indexAction() {
		$helper = Mage::helper('inquiry');
		$helper->updateDetails();
		$this->_title($this->__('CWS Extension'))->_title($this->__('Dealer Inquiries'))->_title($this->__('Dealer Management'));
		$this->_initAction()
			->renderLayout();
	}

	public function editAction() {
		$this->_title($this->__('CWS Extension'))->_title($this->__('Dealer Inquiries'))->_title($this->__('Dealer Management'))->_title($this->__('Dealer Information'));
		$id     = $this->getRequest()->getParam('id');
		$model  = Mage::getModel('inquiry/inquiry')->load($id);
		
		$inquiry_file_collection = Mage::getModel('inquiry/inquiry')->getCollection()->inquiryFilter($id);
		$filename = "";
		$collection_data = $inquiry_file_collection->getData();
		foreach($collection_data as $data){
			if(next($collection_data)){
				$filename.= $data['filename']."|";
			}else{
				$filename.= $data['filename'];
			}
		}
		
		$model->setData('filename',$filename);
		if ($model->getId() || $id == 0) {
			$data = Mage::getSingleton('adminhtml/session')->getFormData(true);
			if (!empty($data)) {
				$model->setData($data);
			}

			Mage::register('inquiry_data', $model);

			$this->loadLayout();
			$this->_setActiveMenu('cws');

			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

			$this->_addContent($this->getLayout()->createBlock('inquiry/adminhtml_inquiry_edit'))
				->_addLeft($this->getLayout()->createBlock('inquiry/adminhtml_inquiry_edit_tabs'));

			$this->renderLayout();
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('inquiry')->__('Dealer does not exist'));
			$this->_redirect('*/*/');
		}
	}
 
	public function newAction() {
		$this->_forward('edit');
	}
 
	public function deleteAction() {
		if( $this->getRequest()->getParam('id') > 0 ) {
			try {
				$model = Mage::getModel('inquiry/inquiry');
				 
				$model->setId($this->getRequest()->getParam('id'))
					->delete();
					 
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Dealer was successfully deleted'));
				$this->_redirect('*/*/');
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
			}
		}
		$this->_redirect('*/*/');
	}

    public function massDeleteAction() {
        $dealerIds = $this->getRequest()->getParam('dealer');
		if(!is_array($dealerIds)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } else {
            try {
                foreach ($dealerIds as $dealerId) {
                    $inquiry = Mage::getModel('inquiry/inquiry')->load($dealerId);
                    $inquiry->delete();
                }
					Mage::getSingleton('adminhtml/session')->addSuccess(
						Mage::helper('adminhtml')->__(
							'Total of %d record(s) were successfully deleted', count($dealerIds)
						)
					);
				} catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
	
	public function createCustomerAction()
	{
		$dealer_data = array();
		$id = $this->getRequest()->getParam('id');
		$collection = Mage::getModel("inquiry/inquiry")->load($id);
		$dealer_data = $collection->getData();
		$dealer_data['country'] = explode('$$$',$dealer_data['country']);
		
		$randompass = $this->randomPassword(9,'standard');//generate random password
		
		$websiteId = Mage::getModel('core/store')->load($dealer_data['storeid'])->getWebsiteId();
		
		$customer = Mage::getModel("customer/customer");
		$customer->setWebsiteId($websiteId);
		$customer->loadByEmail($dealer_data['email']);
		
		$groupid = Mage::getStoreConfig('inquiry/general/customer',$dealer_data['storeid']);
		
		if(!$customer->getId()) {
			$customer	->setGroupId($groupid)
						->setWebsiteId($websiteId)
						->setStoreId($dealer_data['storeid'])
						->setFirstname($dealer_data['firstname'])
						->setLastname($dealer_data['lastname'])
						->setEmail($dealer_data['email'])
						->setPassword($randompass)
						->setTaxvat($dealer_data['taxvat'])
						->setCustomerActivated('1')
						->setConfirmation(null);
								 
			try{
				$customer->save();
				$this->addAddress($dealer_data,$customer->getId());
				$this->sendMail($dealer_data,$randompass);
				Mage::getSingleton('core/session')->addSuccess("Customer Account Created successfully.");
					
				//for update is customer created status
				$dealer_coll = Mage::getModel('inquiry/inquiry')->getCollection()
								->addFieldToFilter('email',$customer->getEmail())
								->addFieldToFilter('websiteid',$customer->getWebsiteId());
				
				foreach($dealer_coll as $d){
						$coll = Mage::getModel("inquiry/inquiry")->load($d->getDealerid());
						$coll->setData('iscustcreated','1');
						$coll->save();
				}
					
			}
			catch (Exception $e) {
				Zend_Debug::dump($e->getMessage());
			}
			
		}else{
			Mage::getSingleton('core/session')->addError("Customer Account Already Created.");
		}
		$this->_redirectReferer();
	}

	public function addAddress($dealer_data,$id){
		$_custom_address = array (
		'street' => array (
			'0' => $dealer_data['address'],
		),
		'firstname' => $dealer_data['firstname'],
		'lastname' => $dealer_data['lastname'],
		'company' => $dealer_data['company'],
		'city' => $dealer_data['city'],
		'region_id' => '',
		'region' => $dealer_data['state'],
		'postcode' => $dealer_data['zip'],
		'country_id' => $dealer_data['country'][0], 
		'telephone' => $dealer_data['phone'],
		);
		
		$customAddress = Mage::getModel('customer/address');
		$customAddress->setData($_custom_address)
		->setCustomerId($id)
		->setIsDefaultBilling('1')
		->setIsDefaultShipping('1')
		->setSaveInAddressBook('1');
		
		try {
			$customAddress->save();
		}
		catch (Exception $ex) {
		}
		return;
	}
	
	public function randomPassword($pwdLength=8, $pwdType='standard')
    {
		// $pwdType can be one of these:
		//    test .. .. .. always returns the same password = "test"
		//    any  .. .. .. returns a random password, which can contain strange characters
		//    alphanum . .. returns a random password containing alphanumerics only
		//    standard . .. same as alphanum, but not including l10O (lower L, one, zero, upper O)
		$ranges='';
	 
		if('test'==$pwdType)         return 'test';
		elseif('standard'==$pwdType) $ranges='65-78,80-90,97-107,109-122,50-57';
		elseif('alphanum'==$pwdType) $ranges='65-90,97-122,48-57';
		elseif('any'==$pwdType)      $ranges='40-59,61-91,93-126';
	 
		if($ranges<>'')
		{
			$range=explode(',',$ranges);
			$numRanges=count($range);
			mt_srand(time()); //not required after PHP v4.2.0
			$p='';
			for ($i = 1; $i <= $pwdLength; $i++)
				{
				$r=mt_rand(0,$numRanges-1);
				list($min,$max)=explode('-',$range[$r]);
				$p.=chr(mt_rand($min,$max));
				}
			return $p;			
		}
    }
	
	public function sendMail($dealer_data, $randompass){
		$templateId = Mage::getStoreConfig(self::EMAIL_TEMPLATE_XML_PATH, $dealer_data['storeid']);
		$customerEmailId = $dealer_data['email']; 
		$customerName = $dealer_data['firstname']." ".$dealer_data['lastname'];
		$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email', $dealer_data['storeid']); 
		$adminName = Mage::getStoreConfig('trans_email/ident_general/name', $dealer_data['storeid']);
		$store_name = Mage::getStoreConfig('general/store_information/name', $dealer_data['storeid']);
		$mailSubject = Mage::getStoreConfig('inquiry/create_account/heading',$dealer_data['storeid']);
 		
		$sender = Array('name'  => $adminName,
					  'email' => $adminEmail);
		 
		$storeId = $dealer_data['storeid']; 
		$loginlink = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
		$vars = Array();
		$vars = Array('password'=>$randompass,'loginlink'=>$loginlink,'subject'=>$mailSubject);
		$translate  = Mage::getSingleton('core/translate');
		Mage::getModel('core/email_template')
			  ->setTemplateSubject($mailSubject)
			  ->sendTransactional($templateId, $sender, $customerEmailId, $customerName, $vars, $storeId);
		$translate->setTranslateInline(true);	
	}
}