<?php

/***************************************************************************
	@extension	: Dealer Inquiry Extension.
	@copyright	: Copyright (c) 2014 Capacity Web Solutions.
	( http://www.capacitywebsolutions.com )
	@author		: Capacity Web Solutions Pvt. Ltd.
	@support	: magento@capacitywebsolutions.com	
***************************************************************************/
 
class CapacityWebSolutions_Inquiry_Manage_InquiryController extends Mage_Adminhtml_Controller_Action 
{
    public function indexAction()
    { 
		$action = $custId = "";
		$action = $this->getRequest()->getParam('ac');
		$delid = $this->getRequest()->getParam('delid');
		
		if($action == "del" && !empty($delid))
		{
			$collection = Mage::getModel("inquiry/inquiry")->load($delid);
			
			if($collection->delete())
			{
				Mage::getSingleton('core/session')->addSuccess("Inquiry deleted successfully.");
			}
			else
			{
				Mage::getSingleton('core/session')->addError("Sorry inquiry is not deleted.");
			}
		}
		
    	$this->_title($this->__('Dealer Inquiry'));
        $this->loadLayout();
        $this->_setActiveMenu('inquiry');
       	$this->_addContent($this->getLayout()->createBlock('core/template'));
        $this->renderLayout();
    }
	public function viewAction()
    { 
		$delid = $this->getRequest()->getParam('delid');
		
		$this->_title($this->__('Dealer Detail'));

        $this->loadLayout();
        $this->_setActiveMenu('inquiry');
		$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Dashboard'), Mage::helper('adminhtml')->__('Dashboard'));
		$this->_addContent($this->getLayout()->createBlock('core/template'));
        $this->renderLayout();
		
	}
	public function createCustomerAction()
	{	
		$del = $this->getRequest()->getParam('multival');
		$values = explode('~~',$del); 
			
		
		$country11 = $values[9];
		$country1 = explode('$$$',$country11);
	function RandomPassword($PwdLength=8, $PwdType='standard')
    {
		// $PwdType can be one of these:
		//    test .. .. .. always returns the same password = "test"
		//    any  .. .. .. returns a random password, which can contain strange characters
		//    alphanum . .. returns a random password containing alphanumerics only
		//    standard . .. same as alphanum, but not including l10O (lower L, one, zero, upper O)
    $Ranges='';
 
    if('test'==$PwdType)         return 'test';
    elseif('standard'==$PwdType) $Ranges='65-78,80-90,97-107,109-122,50-57';
    elseif('alphanum'==$PwdType) $Ranges='65-90,97-122,48-57';
    elseif('any'==$PwdType)      $Ranges='40-59,61-91,93-126';
 
    if($Ranges<>'')
        {
        $Range=explode(',',$Ranges);
        $NumRanges=count($Range);
        mt_srand(time()); //not required after PHP v4.2.0
        $p='';
        for ($i = 1; $i <= $PwdLength; $i++)
            {
            $r=mt_rand(0,$NumRanges-1);
            list($min,$max)=explode('-',$Range[$r]);
            $p.=chr(mt_rand($min,$max));
            }
        return $p;			
        }
    }
	$randompass = RandomPassword(9,'standard');
		
		$customer = Mage::getModel('customer/customer');
		$website_id = Mage::getModel('core/store')->load($values[11])->getWebsiteId();
		
		$customer->setWebsiteId($website_id);
		$customer->loadByEmail($values[0]);
		
	
		if(!$customer->getId()) 
		{
			$groups = Mage::getResourceModel('customer/group_collection')->getData();
			$groupID = '1';
			$customer->setData('group_id', $groupID );
			$customer->setData('website_id', $website_id);
			$customer->setData('is_active', '1');
			$customer->setData('customer_activated', '1');
			$customer->setStatus(1);
			$customer->setEmail($values[0]);
			$customer->setFirstname($values[1]);
			$customer->setLastname($values[2]);
			$customer->setTaxvat($values[10]);
			$customer->setPassword($randompass);
			//$customer->setPassword($values[1].'pass');
			$customer->setConfirmation(null);
			$customer->save();
			if($customer->save())
			{
				$adminEmail = Mage::getStoreConfig('trans_email/ident_general/email', $values[11]); 
				$adminName = Mage::getStoreConfig('trans_email/ident_general/name', $values[11]);
				$fromEmail = $adminEmail;
				$fromName = $adminName;
			 
				$toEmail = $values[0]; 
				$toName = $values[1].$values[2];
				
				$email_logo = Mage::getStoreConfig('design/email/logo' ,$values[11]);
				$subject_title = Mage::getStoreConfig('inquiry/register_email/heading',$values[11]);
				$email_desc = Mage::getStoreConfig('inquiry/register_email/description',$values[11]);
				$store_name = Mage::getStoreConfig('general/store_information/name', $values[11]);
								
				$img_media =  Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'email/logo/'; 
				$img_logo_final = $img_media.$email_logo;
                
                $skin = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN);
				$skin_name = Mage::getStoreConfig('design/theme/skin',$values[11]);
				if($skin_name == "")
				{
				$skin_name = "default";
				}
				else
				{
				$skin_name = $skin_name;
				}
				$package = Mage::getStoreConfig('design/package/name',$values[11]);
				$default_logo =  Mage::getStoreConfig('design/header/logo_src',$values[11]);	
		
				$logo_default = $skin."/frontend/".$package."/".$skin_name."/".$default_logo;
			
			    if($img_logo_final == $img_media)
				{
				$logo_img = "<img src='$logo_default'/>"; 
				}
				else
				{
				$logo_img = "<img src='$img_logo_final'/>";
				}
		
		        $email_desc = str_replace("{{Name}}",$values[1].' '.$values[2],$email_desc);	 
		        $email_desc = str_replace("{{username}}",$values[0],$email_desc);	 
		        $email_desc = str_replace("{{password}}",$randompass,$email_desc);
                $url = Mage::app()->getStore($values[11])->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK).'customer/account/login/'; 				
		        $email_desc = str_replace("{{url}}",$url,$email_desc);	 
		        $email_desc = str_replace("{{storename}}",$store_name,$email_desc);	 
		
		      //  echo $email_desc;
              //  die;				
		
		
				$body = '<table border="0">
							<tr>
								<td>
									<table border="0">
										<tr>
											<Td>'.$logo_img.'</Td>
										</tr>
											<tr>
												<td colspan="2">&nbsp;</td></tr>
											<tr>
											
										
										<tr>
											<Td><p>'.$email_desc.'</p></Td>
										</tr>
										
									</table>
								</td>
							</tr>
						</table>';
			
			$headers = "";
	
			$custSubject = $subject_title;
			$headers  .= 'MIME-Version: 1.0'."\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From:'. $adminName.' <'.$adminEmail.'>';
			
			
			if(mail($toEmail,$custSubject,$body,$headers)){
										
			}else{
				Mage::getSingleton('core/session')
						->addError('Unable to send email.');
			}
					
			Mage::getSingleton('core/session')->addSuccess("Customer Account Created successfully.");
		}
		
		$_custom_address = array (
		'street' => array (
		'0' => $values[4],
		//'1' => $values[11],
		),
		'firstname' => $values[1],
		'lastname' => $values[2],
		'company' => $values[3],
		'city' => $values[5],
		'region_id' => '',
		'region' => $values[6],
		'postcode' => $values[7],
		'country_id' => $country1[0], 
		'telephone' => $values[8],
		);
		
		$customAddress = Mage::getModel('customer/address');
		$customAddress->setData($_custom_address)
		->setCustomerId($customer->getId())
		->setIsDefaultBilling('1')
		->setIsDefaultShipping('1')
		->setSaveInAddressBook('1');
		
		try {
		$customAddress->save();
		}
		catch (Exception $ex) {
		}
			$this->_redirectReferer();
		}
	}
}
?>
